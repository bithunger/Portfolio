<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterSubscribedMail;
use App\Models\NewsletterSubscription;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class NewsletterController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'source' => ['nullable', 'string', 'max:120'],
        ]);

        $email = Str::lower(trim($data['email']));
        $profile = Profile::site();
        $subscription = NewsletterSubscription::firstOrNew(['email' => $email]);

        $subscription->fill([
            'name' => $data['name'] ?? $subscription->name,
            'source' => $data['source'] ?? 'blog',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'unsubscribe_token' => $subscription->unsubscribe_token ?: NewsletterSubscription::makeUnsubscribeToken(),
        ])->save();

        try {
            $unsubscribeUrl = route('newsletter.unsubscribe', $subscription->unsubscribe_token);

            Mail::to($subscription->email)->send(new NewsletterSubscribedMail($subscription, $profile, $unsubscribeUrl));
        } catch (Throwable $exception) {
            report($exception);
        }

        $message = 'You are on the list. A welcome email with an unsubscribe link is on its way.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()
            ->to($this->previousUrlWithFragment('newsletter-form'))
            ->with('newsletter_status', $message);
    }

    public function unsubscribe(string $token): View
    {
        $profile = Profile::site();
        $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();

        if ($subscription && $subscription->isSubscribed()) {
            $subscription->update(['unsubscribed_at' => now()]);
        }

        return view('portfolio.newsletter-unsubscribed', [
            'profile' => $profile,
            'subscription' => $subscription,
        ]);
    }

    private function previousUrlWithFragment(string $fragment): string
    {
        return Str::before(url()->previous(), '#').'#'.$fragment;
    }
}
