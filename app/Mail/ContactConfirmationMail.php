<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Accusé de réception envoyé à l'entreprise après une demande de collecte. */
class ContactConfirmationMail extends Mailable
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
            subject: 'Votre demande de collecte de sang',
            replyTo: ['contact@hug-collecte.ch'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
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
