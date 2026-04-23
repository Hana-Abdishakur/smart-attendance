<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // Ku dar kan
use Illuminate\Queue\SerializesModels;

class NewPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $pdf;

    /**
     * Markaad abuurayso Email-ka, ku soo dhex rid Payment-ka iyo PDF-ka
     */
    public function __construct($payment, $pdf)
    {
        $this->payment = $payment;
        $this->pdf = $pdf;
    }

    /**
     * Ciwaanka Email-ka
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Student Payment: #' . $this->payment->transaction_id,
        );
    }

    /**
     * Muuqaalka Email-ka (Gudaha waxa ku qoran)
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_payment', // Hubi in feylkani jiro
        );
    }

    /**
     * Lifaaqa PDF-ka ah
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf, 'Receipt_' . $this->payment->transaction_id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}