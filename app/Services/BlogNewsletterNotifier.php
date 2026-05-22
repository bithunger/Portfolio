<?php

namespace App\Services;

use App\Mail\BlogPostPublishedMail;
use App\Models\BlogPost;
use App\Models\NewsletterSubscription;
use App\Models\Profile;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BlogNewsletterNotifier
{
    public function sendIfNeeded(BlogPost $post): int
    {
        $post->refresh();

        if ($post->newsletter_sent_at || ! $post->isVisibleOnPortfolio()) {
            return 0;
        }

        $profile = Profile::site();
        $activeSubscriberCount = 0;
        $sentCount = 0;

        NewsletterSubscription::subscribed()
            ->orderBy('id')
            ->each(function (NewsletterSubscription $subscription) use ($post, $profile, &$activeSubscriberCount, &$sentCount): void {
                $activeSubscriberCount++;
                $subscription->ensureUnsubscribeToken();

                try {
                    Mail::to($subscription->email)->send(new BlogPostPublishedMail(
                        post: $post,
                        subscription: $subscription,
                        profile: $profile,
                        blogUrl: route('portfolio.blog.show', $post),
                        unsubscribeUrl: route('newsletter.unsubscribe', $subscription->unsubscribe_token),
                    ));

                    $sentCount++;
                } catch (Throwable $exception) {
                    report($exception);
                }
            });

        if ($activeSubscriberCount === 0 || $sentCount > 0) {
            $post->forceFill(['newsletter_sent_at' => now()])->save();
        }

        return $sentCount;
    }
}
