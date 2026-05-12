<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'source' => ['nullable', 'string', 'max:120'],
        ]);

        NewsletterSubscription::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'] ?? null,
                'source' => $data['source'] ?? 'blog',
                'subscribed_at' => now(),
            ],
        );

        return back()->with('newsletter_status', 'You are on the list. Fresh notes will land in your inbox.');
    }
}
