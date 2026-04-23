<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Halkan ku dar variable-ka qaadaya xogta
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        // 2. Halkan ku xir xogta la soo diray
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Waxaan ka dhigaynaa subject-ga mid dynamic ah
            subject: $this->details['subject'] ?? 'Notification from SmartAttend',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // 3. BEDDEL KAN: Waa inuu la mid yahay file-ka aad ku dhex sameysay resources/views/emails
            view: 'emails.notification', 
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}