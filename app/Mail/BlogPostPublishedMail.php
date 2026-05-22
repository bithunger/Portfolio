<?php

namespace App\Mail;

use App\Models\BlogPost;
use App\Models\NewsletterSubscription;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class BlogPostPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BlogPost $post,
        public NewsletterSubscription $subscription,
        public Profile $profile,
        public string $blogUrl,
        public string $unsubscribeUrl,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New article: '.$this->post->title,
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe' => '<'.$this->unsubscribeUrl.'>',
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.blog-post-published',
            text: 'mail.blog-post-published-text',
        );
    }
}
