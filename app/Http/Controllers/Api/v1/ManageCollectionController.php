<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Collection;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManageCollectionController extends Controller
{
    /** Règles de validation. */
    private function reglesValidation(): array
    {
        return [
            // Entreprise
            'company' => ['required', 'array'],
            'company.name' => ['required', 'string', 'max:255'],
            'company.nb_employee' => ['required', 'integer', 'min:1'],
            'company.email' => ['required', 'email', 'max:255'],
            'company.phone_number' => ['required', 'string', 'max:50', 'regex:/^[+\d][\d\s()\/.\-]{5,}$/'],

            // Adresse
            'address' => ['required', 'array'],
            'address.street' => ['required', 'string', 'max:255'],
            'address.number' => ['required', 'string', 'max:20', 'regex:/^\d+[a-zA-Z]?$/'],
            'address.postal_code' => ['required', 'digits:4'],
            'address.city' => ['required', 'string', 'max:255'],

            // Collecte
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'capacity' => ['required', 'integer', 'min:1'],
            'primary_color' => ['required', 'string', 'max:10'],
            'secondary_color' => ['required', 'string', 'max:10'],
            // Logo obligatoire : data URL base64 ou chemin d'image, envoyé en JSON.
            'logo_url' => ['required', 'string', 'max:5000000'],
            // Lien Onedoc : créé manuellement par le CTS sur la plateforme Onedoc.
            'onedoc_url' => ['required', 'url', 'max:2048'],
            // Lien KDrive du dossier kit de communication (optionnel, peut être ajouté après).
            'kit_url' => ['nullable', 'url', 'max:2048'],
        ];
    }

    /** Comptage inscrits (onedoc_clicked). */
    private function comptageInscrits(): array
    {
        return ['quizEvents as nb_inscrits' => fn ($q) => $q->where('event_type', 'onedoc_clicked')];
    }

    public function index()
    {
        $collectes = Collection::with(['company', 'address'])
            ->withCount($this->comptageInscrits())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($collectes);
    }

    public function show(Collection $collecte)
    {
        return response()->json(
            $collecte->load(['company', 'address'])->loadCount($this->comptageInscrits())
        );
    }

    public function store(Request $request)
    {
        $valide = $request->validate($this->reglesValidation(), [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
        ]);

        $collecte = DB::transaction(function () use ($valide, $request) {
            $adresse = Address::create($valide['address']);

            $entreprise = Company::create(array_merge($valide['company'], [
                'address_id' => $adresse->id,
            ]));

            return Collection::create([
                'company_id' => $entreprise->id,
                'user_id' => $request->user()->id,
                'address_id' => $adresse->id,
                'start_date' => $valide['start_date'],
                'end_date' => $valide['end_date'],
                'capacity' => $valide['capacity'],
                'primary_color' => $valide['primary_color'],
                'secondary_color' => $valide['secondary_color'],
                'logo_url' => $valide['logo_url'],
                'onedoc_url' => $valide['onedoc_url'],
                'kit_url' => $valide['kit_url'] ?? null,
                'public_token' => Str::random(32),
            ]);
        });

        return response()->json(
            $collecte->load(['company', 'address'])->loadCount($this->comptageInscrits()),
            201
        );
    }

    public function update(Request $request, Collection $collecte)
    {
        $valide = $request->validate($this->reglesValidation(), [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
        ]);

        DB::transaction(function () use ($valide, $collecte) {
            if ($collecte->address) {
                $collecte->address->update($valide['address']);
            } else {
                $adresse = Address::create($valide['address']);
                $collecte->address_id = $adresse->id;
            }

            if ($collecte->company) {
                $collecte->company->update($valide['company']);
            }

            $donnees = [
                'start_date' => $valide['start_date'],
                'end_date' => $valide['end_date'],
                'capacity' => $valide['capacity'],
                'onedoc_url' => $valide['onedoc_url'],
                'kit_url' => $valide['kit_url'] ?? null,
                'primary_color' => $valide['primary_color'],
                'secondary_color' => $valide['secondary_color'],
            ];

            if (array_key_exists('logo_url', $valide)) {
                $donnees['logo_url'] = $valide['logo_url'];
            }

            $collecte->update($donnees);
        });

        return response()->json($collecte->load(['company', 'address']));
    }

    public function destroy(Collection $collecte)
    {
        $collecte->delete();

        return response()->noContent();
    }
}
