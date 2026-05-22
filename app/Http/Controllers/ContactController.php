<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageConfirmationMail;
use App\Mail\ContactMessageReceivedMail;
use App\Models\ContactMessage;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('portfolio.contact', [
            'profile' => Profile::site(),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $profile = Profile::site();
        $message = ContactMessage::create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:3000'],
        ]));

        try {
            Mail::to($profile->email)->send(new ContactMessageReceivedMail($message, $profile));
            Mail::to($message->email)->send(new ContactMessageConfirmationMail($message, $profile));
        } catch (Throwable $exception) {
            report($exception);
        }

        $status = 'Message received. Thanks for reaching out. I will contact you soon.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $status]);
        }

        return redirect()
            ->to(Str::before(url()->previous(), '#').'#contact-form')
            ->with('status', $status);
    }
}
