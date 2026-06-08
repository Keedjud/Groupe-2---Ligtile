<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Services\ColorPaletteService;

class ApiCobrandController extends Controller
{
    public function show(string $token)
    {
        $collection = Collection::where('public_token', $token)
            ->with('company')
            ->firstOrFail();

        $nbInscrits = $collection->quizEvents()
            ->where('event_type', 'onedoc_clicked')
            ->distinct()
            ->count('session_id');

        $placesRestantes = max(0, $collection->capacity - $nbInscrits);
        $tauxRemplissage = $collection->capacity > 0
            ? round($nbInscrits / $collection->capacity * 100)
            : 0;

        return response()->json([
            'id'               => $collection->id,
            'company_name'     => $collection->company->name,
            'nb_employee'      => $collection->company->nb_employee,
            'logo_url'         => $collection->logo_url,
            'start_date'       => $collection->start_date,
            'end_date'         => $collection->end_date,
            'capacity'         => $collection->capacity,
            'nb_inscrits'      => $nbInscrits,
            'places_restantes' => $placesRestantes,
            'taux_remplissage' => $tauxRemplissage,
            'onedoc_url'       => $collection->onedoc_url,
            'contact_email'    => $collection->contact_email,
            'contact_phone'    => $collection->contact_phone,
            'venue'            => [
                'street'      => $collection->venue_street,
                'number'      => $collection->venue_number,
                'postal_code' => $collection->venue_postal_code,
                'city'        => $collection->venue_city,
            ],
            'theme'            => ColorPaletteService::fromTwo(
                $collection->primary_color,
                $collection->secondary_color,
            ),
        ]);
    }
}
