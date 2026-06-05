<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



class ContactPmeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $validated;

    /**
     * Create a new message instance.
     */
    public function __construct($validated)
    {
        $this->validated = $validated;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact PME : ' . ($this->validated['company_name'] ?? 'Entreprise inconnue'),
            replyTo: $this->validated['email'] ?? null,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contactPme',
            with: [
                'email' => $this->validated['email'] ?? '',
                'company_name' => $this->validated['company_name'] ?? '',
                'messageContenu' => $this->validated['message'] ?? '',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
