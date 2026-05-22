<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class PasswordResetController extends Controller
{
    private const OTP_EXPIRY_MINUTES = 10;

    private const RESET_SESSION_MINUTES = 10;

    private const SEND_LIMIT_ATTEMPTS = 3;

    private const SEND_LIMIT_SECONDS = 600;

    private const VERIFY_LIMIT_ATTEMPTS = 5;

    private const VERIFY_LIMIT_SECONDS = 900;

    public function request(): View
    {
        return view('admin.auth.forgot-password');
    }

    public function email(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($validated['email']));
        $sendKey = $this->sendRateLimitKey($request, $email);

        if (RateLimiter::tooManyAttempts($sendKey, self::SEND_LIMIT_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => 'Please wait '.$this->formatSeconds(RateLimiter::availableIn($sendKey)).' before requesting another code.',
            ]);
        }

        RateLimiter::hit($sendKey, self::SEND_LIMIT_SECONDS);

        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput(['email' => $email])
                ->with('status', 'If that email matches an admin account, a reset code will be sent shortly.');
        }

        $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ],
        );

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($user, $code, self::OTP_EXPIRY_MINUTES));
        } catch (Throwable $exception) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            report($exception);

            throw ValidationException::withMessages([
                'email' => 'We could not send the reset code right now. Please check the mail settings and try again.',
            ]);
        }

        $request->session()->put('password_reset_email', $user->email);
        $request->session()->forget(['password_reset_verified_email', 'password_reset_verified_at']);

        return redirect()
            ->route('admin.password.verify')
            ->with('status', 'A 4-digit reset code was sent to '.$this->maskEmail($user->email).'.');
    }

    public function verifyForm(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('admin.password.request');
        }

        return view('admin.auth.verify-password-otp', [
            'email' => $email,
            'maskedEmail' => $this->maskEmail($email),
            'expiresInMinutes' => self::OTP_EXPIRY_MINUTES,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('admin.password.request');
        }

        $request->validate([
            'code' => ['required', 'digits:4'],
        ]);

        $verifyKey = $this->verifyRateLimitKey($request, $email);

        if (RateLimiter::tooManyAttempts($verifyKey, self::VERIFY_LIMIT_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'code' => 'Too many incorrect code attempts. Please request a new reset code.',
            ]);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $record || ! $record->created_at || Carbon::parse($record->created_at)->addMinutes(self::OTP_EXPIRY_MINUTES)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            throw ValidationException::withMessages([
                'code' => 'This reset code has expired. Please request a new one.',
            ]);
        }

        if (! Hash::check((string) $request->input('code'), $record->token)) {
            RateLimiter::hit($verifyKey, self::VERIFY_LIMIT_SECONDS);

            throw ValidationException::withMessages([
                'code' => 'The reset code is not correct.',
            ]);
        }

        RateLimiter::clear($verifyKey);

        $request->session()->put('password_reset_verified_email', $email);
        $request->session()->put('password_reset_verified_at', now()->timestamp);

        return redirect()
            ->route('password.reset')
            ->with('status', 'Code verified. Choose a new password.');
    }

    public function create(Request $request): View|RedirectResponse
    {
        $email = $this->verifiedResetEmail($request);

        if (! $email) {
            return redirect()
                ->route('admin.password.verify')
                ->withErrors(['code' => 'Please verify your reset code before choosing a new password.']);
        }

        return view('admin.auth.reset-password', [
            'email' => $email,
            'maskedEmail' => $this->maskEmail($email),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $email = $this->verifiedResetEmail($request);

        if (! $email) {
            throw ValidationException::withMessages([
                'password' => 'Your password reset session has expired. Please verify a new reset code.',
            ]);
        }

        $request->validate([
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = User::where('email', $email)->firstOrFail();

        $user->forceFill([
            'password' => (string) $request->input('password'),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $request->session()->forget(['password_reset_email', 'password_reset_verified_email', 'password_reset_verified_at']);
        $request->session()->regenerateToken();

        event(new PasswordReset($user));

        return redirect()->route('admin.login')->with('status', 'Your password has been reset. You can sign in now.');
    }

    private function verifiedResetEmail(Request $request): ?string
    {
        $email = $request->session()->get('password_reset_verified_email');
        $verifiedAt = (int) $request->session()->get('password_reset_verified_at', 0);

        if (! $email || ! $verifiedAt) {
            return null;
        }

        if ((now()->timestamp - $verifiedAt) > (self::RESET_SESSION_MINUTES * 60)) {
            $request->session()->forget(['password_reset_verified_email', 'password_reset_verified_at']);

            return null;
        }

        return $email;
    }

    private function sendRateLimitKey(Request $request, string $email): string
    {
        return 'admin-password-reset-send:'.sha1($email.'|'.$request->ip());
    }

    private function verifyRateLimitKey(Request $request, string $email): string
    {
        return 'admin-password-reset-verify:'.sha1($email.'|'.$request->ip());
    }

    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);
        $visible = Str::substr($local, 0, min(2, max(1, Str::length($local))));
        $hidden = str_repeat('*', max(2, Str::length($local) - Str::length($visible)));

        return $visible.$hidden.'@'.$domain;
    }

    private function formatSeconds(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds.' '.Str::plural('second', $seconds);
        }

        $minutes = (int) ceil($seconds / 60);

        return $minutes.' '.Str::plural('minute', $minutes);
    }
}
