<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $transactionId;

    public function __construct($name, $id)
    {
        $this->userName = $name;
        $this->transactionId = $id;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SmartAttend - Payment Confirmed ✅',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_confirmed', // Waxaan u baahanahay inaan abuurno faylkan
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
