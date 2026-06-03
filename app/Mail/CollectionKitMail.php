<?php

namespace App\Mail;

use App\Models\Collection;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/** Mailable du kit de communication co-brandé. */
class CollectionKitMail extends Mailable
{
    use Queueable, SerializesModels;

    public Collection $collecte;

    public function __construct(Collection $collecte)
    {
        $this->collecte = $collecte->loadMissing('company');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre kit de communication - collecte ' . ($this->collecte->company?->name ?? ''),
        );
    }

    public function content(): Content
    {
        $base = rtrim(config('app.url'), '/');
        [$logoUrl, $logoData, $logoMime] = $this->logo($base);

        return new Content(
            view: 'emails.collection-kit',
            with: [
                'entreprise'        => $this->collecte->company?->name ?? '',
                'lienCoBrande'      => $base . '/' . $this->collecte->public_token,
                'lienKitComm'       => $this->collecte->kit_url ?: null,
                'couleurPrimaire'   => $this->collecte->primary_color ?: '#681764',
                'couleurSecondaire' => $this->collecte->secondary_color ?: '#681764',
                'dateDebut'         => $this->formatDate($this->collecte->start_date),
                'dateFin'           => $this->formatDate($this->collecte->end_date),
                'logoUrl'           => $logoUrl,
                'logoData'          => $logoData,
                'logoMime'          => $logoMime,
            ],
        );
    }

    private function formatDate($date): string
    {
        return $date ? Carbon::parse($date)->format('d.m.Y') : '';
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        // Le kit de communication est hébergé sur KDrive (lienKitComm dans le contenu de l'email).
        // Aucune pièce jointe — le lien KDrive remplace les fichiers attachés.
        return [];
    }

    /** @return array{0: ?string, 1: ?string, 2: ?string} */
    private function logo(string $base): array
    {
        $logo = $this->collecte->logo_url;

        if (! $logo) {
            return [null, null, null];
        }
        if (str_starts_with($logo, 'data:')) {
            if (preg_match('/^data:(.*?);base64,(.*)$/s', $logo, $m)) {
                return [null, base64_decode($m[2]), $m[1]];
            }
            return [null, null, null];
        }
        if (str_starts_with($logo, 'http')) {
            return [$logo, null, null];
        }

        return [$base . '/' . ltrim($logo, '/'), null, null];
    }
}
