<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



class ContactMail extends Mailable
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
            subject: 'New Contact Request from ' . ($this->validated['name'] ?? 'User'),
            from: $this->validated['email'] ?? null,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [

                'email' => $this->validated['email'] ?? '',
                'phone' => $this->validated['phone_number'] ?? '',
                'company_name' => $this->validated['company_name'] ?? '',
                'employees_count' => $this->validated['employees_count'] ?? '',
                'street' => $this->validated['street'] ?? '',
                'postal_code' => $this->validated['postal_code'] ?? '' ,
                'city' => $this->validated['city'] ?? '',
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
