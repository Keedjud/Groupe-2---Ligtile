<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Accusé de réception envoyé à une PME après son message de contact. */
class ContactPmeConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $validated;

    public function __construct($validated)
    {
        $this->validated = $validated;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Merci pour votre message',
            replyTo: ['contact@hug-collecte.ch'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contactPme-confirmation',
            with: [
                'entreprise' => $this->validated['company_name'] ?? '',
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
