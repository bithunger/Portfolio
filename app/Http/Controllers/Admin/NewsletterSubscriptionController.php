<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterSubscriptionController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter.index', [
            'subscriptions' => NewsletterSubscription::latest('subscribed_at')
                ->latest()
                ->get(),
        ]);
    }

    public function destroy(NewsletterSubscription $newsletterSubscription): RedirectResponse
    {
        $newsletterSubscription->delete();

        return redirect()->route('admin.newsletter.index')->with('status', 'Newsletter subscriber removed.');
    }
}
