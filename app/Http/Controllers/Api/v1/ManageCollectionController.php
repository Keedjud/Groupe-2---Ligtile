<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Company;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManageCollectionController extends Controller
{
    /** Nom du label unique délivré par le CTS. */
    private const NOM_LABEL_CTS = 'Label CTS';
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
            'secondary_color'   => ['required', 'string', 'max:10'],
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

    /**
     * Recalcule l'historique de labellisation d'une entreprise à partir de
     * l'ensemble de ses collectes.
     *
     * Règle : le label CTS est valable 2 ans à partir de la date de fin de
     * chaque collecte. Une nouvelle collecte dont la date de fin tombe dans la
     * fenêtre encore active prolonge le label ; au-delà (label échu), une
     * nouvelle période démarre. L'historique est reconstruit intégralement à
     * chaque appel, ce qui couvre la création, la modification et la suppression
     * de collectes sans risque de dérive.
     */
    private function synchroniserLabel(Company $company): void
    {
        $label = Label::firstOrCreate(['name' => self::NOM_LABEL_CTS]);

        $finsCollectes = $company->collections()
            ->orderBy('end_date')
            ->pluck('end_date');

        // Reconstruit les périodes de label par fenêtre glissante de 2 ans.
        $periodes = [];
        foreach ($finsCollectes as $fin) {
            $fin      = Carbon::parse($fin);
            $finLabel = $fin->copy()->addYears(2);

            $derniere = array_key_last($periodes);
            if ($derniere !== null && $fin->lessThanOrEqualTo($periodes[$derniere]['end_date'])) {
                // Collecte dans la fenêtre active → prolonge la période courante.
                $periodes[$derniere]['end_date'] = $finLabel;
            } else {
                // Première collecte, ou label échu → nouvelle période.
                $periodes[] = ['start_date' => $fin, 'end_date' => $finLabel];
            }
        }

        // Remplace l'historique de label de l'entreprise pour ce label.
        DB::transaction(function () use ($company, $label, $periodes) {
            $company->labels()->detach($label->id);
            foreach ($periodes as $p) {
                $company->labels()->attach($label->id, [
                    'start_date' => $p['start_date'],
                    'end_date'   => $p['end_date'],
                ]);
            }
        });
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

        $valide = $request->validate($rules, [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
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
            'secondary_color'   => $valide['secondary_color'],
            'logo_url'          => $valide['logo_url'],
            'onedoc_url'        => $valide['onedoc_url'],
            'kit_url'           => $valide['kit_url'],
            'public_token'      => Str::random(32),
        ]);

        $this->synchroniserLabel($collecte->company);

        return response()->json(
            $collecte->load('company')->loadCount($this->comptageInscrits()),
            201
        );
    }

    public function update(Request $request, Collection $collecte)
    {
        $valide = $request->validate($this->reglesValidation(), [
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
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
            'secondary_color'   => $valide['secondary_color'],
            'logo_url'          => $valide['logo_url'],
        ]);

        $this->synchroniserLabel($collecte->company);

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
        $this->synchroniserLabel($company);

        return response()->noContent();
    }
}
