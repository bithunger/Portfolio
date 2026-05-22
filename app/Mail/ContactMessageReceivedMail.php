<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceivedMail extends Mailable
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
            replyTo: [
                new Address($this->message->email, $this->message->name),
            ],
            subject: 'New portfolio inquiry from '.$this->message->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-message-received',
            text: 'mail.contact-message-received-text',
        );
    }
}
