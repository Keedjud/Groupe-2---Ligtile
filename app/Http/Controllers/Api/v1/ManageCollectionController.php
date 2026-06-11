<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Company;
use App\Services\LabelService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManageCollectionController extends Controller
{
    public function __construct(private LabelService $labels) {}

    /** Règles communes création + édition (snapshot uniquement, sans données entreprise). */
    private function reglesValidation(): array
    {
        return [
            'contact_email'     => ['required', 'email', 'max:100'],
            'contact_phone'     => ['required', 'string', 'max:50', 'regex:/^[+\d][\d\s()\/.\-]{5,}$/'],
            'venue_street'      => ['required', 'string', 'max:255'],
            'venue_number'      => ['required', 'string', 'max:20', 'regex:/^\d+[a-zA-Z]?$/'],
            'venue_postal_code' => ['required', 'digits:4'],
            'venue_city'        => ['required', 'string', 'max:255'],
            'start_date'        => ['required', 'date',],
            'end_date'          => ['required', 'date', 'after:start_date'],
            'capacity'          => ['required', 'integer', 'min:1'],
            'primary_color'     => ['required', 'string', 'max:10'],
            'logo_url'          => ['required', 'string', 'max:255'],
            'onedoc_url'        => ['required', 'url', 'max:255'],
            'kit_url'           => ['required', 'url', 'max:255'],
        ];
    }

    /** Comptage inscrits (onedoc_clicked). */
    private function comptageInscrits(): array
    {
        return ['quizEvents as nb_inscrits' => fn ($q) => $q->where('event_type', 'onedoc_clicked')];
    }

    public function index()
    {
        $collectes = Collection::with('company')
            ->withCount($this->comptageInscrits())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($collectes);
    }

    public function show(Collection $collecte)
    {
        return response()->json(
            $collecte->load('company')->loadCount($this->comptageInscrits())
        );
    }

    public function store(Request $request)
    {
        $rules               = $this->reglesValidation();
        $rules['company_id'] = ['required', 'integer', 'exists:companies,id'];

        // La capacité ne peut pas dépasser l'effectif de l'entreprise.
        $nbEmployee = Company::find($request->input('company_id'))?->nb_employee;
        if ($nbEmployee !== null) {
            $rules['capacity'][] = 'max:' . $nbEmployee;
        }

        $valide = $request->validate($rules, [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'capacity.max'   => "La capacité ne peut pas dépasser l'effectif de l'entreprise ({$nbEmployee} employé·es).",
        ]);

        $collecte = Collection::create([
            'company_id'        => $valide['company_id'],
            'user_id'           => $request->user()->id,
            'contact_email'     => $valide['contact_email'],
            'contact_phone'     => $valide['contact_phone'],
            'venue_street'      => $valide['venue_street'],
            'venue_number'      => $valide['venue_number'],
            'venue_postal_code' => $valide['venue_postal_code'],
            'venue_city'        => $valide['venue_city'],
            'start_date'        => $valide['start_date'],
            'end_date'          => $valide['end_date'],
            'capacity'          => $valide['capacity'],
            'primary_color'     => $valide['primary_color'],
            'logo_url'          => $valide['logo_url'],
            'onedoc_url'        => $valide['onedoc_url'],
            'kit_url'           => $valide['kit_url'],
            'public_token'      => Str::random(32),
        ]);

        $this->labels->synchronise($collecte->company);

        return response()->json(
            $collecte->load('company')->loadCount($this->comptageInscrits()),
            201
        );
    }

    public function update(Request $request, Collection $collecte)
    {
        $rules = $this->reglesValidation();

        // La capacité ne peut pas dépasser l'effectif de l'entreprise.
        $nbEmployee = $collecte->company?->nb_employee;
        if ($nbEmployee !== null) {
            $rules['capacity'][] = 'max:' . $nbEmployee;
        }

        $valide = $request->validate($rules, [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
            'capacity.max'   => "La capacité ne peut pas dépasser l'effectif de l'entreprise ({$nbEmployee} employé·es).",
        ]);

        $collecte->update([
            'contact_email'     => $valide['contact_email'],
            'contact_phone'     => $valide['contact_phone'],
            'venue_street'      => $valide['venue_street'],
            'venue_number'      => $valide['venue_number'],
            'venue_postal_code' => $valide['venue_postal_code'],
            'venue_city'        => $valide['venue_city'],
            'start_date'        => $valide['start_date'],
            'end_date'          => $valide['end_date'],
            'capacity'          => $valide['capacity'],
            'onedoc_url'        => $valide['onedoc_url'],
            'kit_url'           => $valide['kit_url'],
            'primary_color'     => $valide['primary_color'],
            'logo_url'          => $valide['logo_url'],
        ]);

        $this->labels->synchronise($collecte->company);

        return response()->json(
            $collecte->load('company')->loadCount($this->comptageInscrits())
        );
    }

    public function generateLink(Collection $collecte)
    {
        if (! $collecte->link_generated_at) {
            $collecte->link_generated_at = now();
            $collecte->save();
        }

        return response()->json([
            'link_generated_at' => $collecte->link_generated_at,
        ]);
    }

    public function destroy(Collection $collecte)
    {
        $company = $collecte->company;

        $collecte->delete();

        // Recalcule le label : la suppression peut raccourcir ou supprimer une période.
        $this->labels->synchronise($company);

        return response()->noContent();
    }
}
