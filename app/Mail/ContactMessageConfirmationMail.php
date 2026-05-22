<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $message,
        public Profile $profile,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'I received your message',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-message-confirmation',
            text: 'mail.contact-message-confirmation-text',
        );
    }
}
