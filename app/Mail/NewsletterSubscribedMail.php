<?php

namespace App\Mail;

use App\Models\NewsletterSubscription;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscribedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterSubscription $subscription,
        public Profile $profile,
        public string $unsubscribeUrl,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are subscribed to '.$this->profile->owner_name,
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
            view: 'mail.newsletter-subscribed',
            text: 'mail.newsletter-subscribed-text',
        );
    }
}
