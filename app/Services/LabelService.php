<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Label;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LabelService
{
    /** Nom du label unique délivré par le CTS. */
    public const NOM_LABEL_CTS = 'Label CTS';

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
    public function synchronise(Company $company): void
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
}
