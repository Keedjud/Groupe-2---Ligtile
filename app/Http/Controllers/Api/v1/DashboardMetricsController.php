<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\PageEvent;
use App\Models\QuizEvent;
use Illuminate\Http\Request;

class DashboardMetricsController extends Controller
{
    /** KPI overview. */
    public function overview(Request $request)
    {
        $request->validate([
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
        ]);

        $companyId = $request->query('company_id');
        $entreprise = $companyId ? Company::find($companyId) : null;

        // Inscrits (onedoc_clicked)
        $collections = Collection::with('company')
            ->withCount(['quizEvents as inscrits' => fn ($q) => $q->where('event_type', 'onedoc_clicked')])
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->get();

        $ids = $collections->pluck('id');

        return response()->json([
            'contexte' => [
                'entreprise_id'  => $entreprise?->id,
                'entreprise_nom' => $entreprise?->name,
                'portee'         => $entreprise ? 'entreprise' : 'global',
            ],
            'groupe_a' => $this->vueEnsemble($collections),
            'groupe_b' => $this->engagementEntreprises($collections),
            'groupe_c' => $this->performanceQuiz($ids),
            'groupe_d' => ['questions' => $this->performanceParQuestion($ids)],
            'groupe_e' => $this->engagementPrevention($ids),
        ]);
    }

    /** Pourcentage arrondi. */
    private function pct(int $partie, int $total): int
    {
        return $total > 0 ? (int) round($partie / $total * 100) : 0;
    }

    /** Groupe A */
    private function vueEnsemble($collections): array
    {
        $evolution = $collections
            ->groupBy(fn ($c) => substr((string) $c->start_date, 0, 4))
            ->map(fn ($groupe, $annee) => [
                'annee'     => $annee,
                'collectes' => $groupe->count(),
                'inscrits'  => (int) $groupe->sum('inscrits'),
            ])
            ->sortKeys()
            ->values();

        return [
            'total_collectes'        => $collections->count(),
            'total_inscrits'         => (int) $collections->sum('inscrits'),
            'entreprises_distinctes' => $collections->pluck('company_id')->unique()->count(),
            'evolution'              => $evolution,
        ];
    }

    /** Groupe B */
    private function engagementEntreprises($collections): array
    {
        $tauxRemplissage = $collections
            ->filter(fn ($c) => $c->capacity > 0)
            ->map(fn ($c) => min(100, round($c->inscrits / $c->capacity * 100)));

        $topEntreprises = $collections
            ->groupBy('company_id')
            ->map(fn ($groupe) => [
                'nom'       => $groupe->first()->company?->name ?? 'Inconnu',
                'collectes' => $groupe->count(),
                'inscrits'  => (int) $groupe->sum('inscrits'),
            ])
            ->sortByDesc('collectes')
            ->take(5)
            ->values();

        return [
            'taux_remplissage_moyen' => $tauxRemplissage->isEmpty() ? 0 : (int) round($tauxRemplissage->average()),
            'collectes_recurrentes'  => $collections->groupBy('company_id')->filter(fn ($g) => $g->count() > 2)->count(),
            'demandes_contact'       => ContactRequest::count(),
            'top_entreprises'        => $topEntreprises,
        ];
    }

    /** Groupe C */
    private function performanceQuiz($ids): array
    {
        $compte = fn (string $type) => QuizEvent::whereIn('collection_id', $ids)
            ->where('event_type', $type)
            ->count();

        $started = $compte('quiz_started');
        $p1Done  = $compte('p1_completed');

        return [
            'taux_completion'     => $this->pct($compte('quiz_completed'), $started),
            'taux_elimination_p1' => $this->pct($compte('p1_eliminated'), $started),
            'taux_clic_onedoc'    => $this->pct($compte('onedoc_clicked'), $p1Done),
        ];
    }

    /** Groupe D */
    private function performanceParQuestion($ids): array
    {
        $libelles = [
            'age'             => 'Âge',
            'poids'           => 'Poids',
            'sante-generale'  => 'Santé générale',
            'medicaments'     => 'Médicaments',
            'voyages'         => 'Voyages récents',
        ];

        $compte = function (string $slug, string $type, ?string $result = null) use ($ids): int {
            $query = QuizEvent::whereIn('collection_id', $ids)
                ->where('question_slug', $slug)
                ->where('event_type', $type);

            if ($result !== null) {
                $query->where('answer_result', $result);
            }

            return $query->count();
        };

        $questions = [];
        foreach ($libelles as $slug => $label) {
            $bonnes    = $compte($slug, 'question_answered', 'correct');
            $mauvaises = $compte($slug, 'question_answered', 'incorrect');
            $skip      = $compte($slug, 'question_skipped');
            $total     = $bonnes + $mauvaises;

            if ($total === 0 && $skip === 0) {
                continue;
            }

            $questions[] = [
                'label'     => $label,
                'bonnes'    => $this->pct($bonnes, $total),
                'mauvaises' => $this->pct($mauvaises, $total),
                'skip'      => $skip,
            ];
        }

        return $questions;
    }

    /** Groupe E */
    private function engagementPrevention($ids): array
    {
        $sorties = fn () => PageEvent::whereIn('collection_id', $ids)->where('event_type', 'prevention_exited');

        $total      = $sorties()->count();
        $nonEngages = $sorties()->where('engaged', false)->count();
        $moyenne    = (int) round($sorties()->avg('time_on_page') ?? 0);

        return [
            'temps_moyen' => intdiv($moyenne, 60) . 'm ' . ($moyenne % 60) . 's',
            'taux_rebond' => $this->pct($nonEngages, $total),
        ];
    }
}
