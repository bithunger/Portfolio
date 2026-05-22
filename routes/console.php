<?php

use App\Models\BlogPost;
use App\Services\BlogNewsletterNotifier;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('newsletter:send-published-posts', function () {
    $notifier = app(BlogNewsletterNotifier::class);
    $sentCount = 0;

    BlogPost::published()
        ->whereNull('newsletter_sent_at')
        ->orderBy('published_at')
        ->orderBy('id')
        ->get()
        ->each(function (BlogPost $post) use ($notifier, &$sentCount): void {
            $sentCount += $notifier->sendIfNeeded($post);
        });

    $this->info("Sent {$sentCount} blog newsletter emails.");
})->purpose('Email active newsletter subscribers about newly visible blog posts');

Schedule::command('newsletter:send-published-posts')->everyFiveMinutes();
