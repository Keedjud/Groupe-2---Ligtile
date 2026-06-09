<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Mail\CollectionKitMail;
use App\Models\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Envoi du kit de communication. */
class CollectionKitController extends Controller
{
    public function send(Collection $collecte)
    {
        $collecte->loadMissing('company');
        $destinataire = $collecte->company?->email;

        if (! $destinataire) {
            return response()->json([
                'success' => false,
                'message' => "Aucune adresse e-mail n'est renseignée pour cette entreprise.",
            ], 422);
        }

        try {
            Mail::to($destinataire)->send(new CollectionKitMail($collecte));

            return response()->json([
                'success' => true,
                'message' => 'Le kit de communication a été envoyé à ' . $destinataire . '.',
            ]);
        } catch (\Exception $e) {
            Log::error('CollectionKitMail failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "L'envoi du kit a échoué. Veuillez réessayer plus tard.",
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
