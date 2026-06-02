<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Trophee;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;

class ApiTropheeController extends Controller
{
    public function index(): JsonResponse
    {
        // Récupère toutes les années distinctes, triées par ordre décroissant
        $years = Trophee::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            return response()->json([
                'podium' => null,
                'history' => [],
            ]);
        }

        $allYears = [];

        foreach ($years as $year) {
            $trophees = Trophee::where('year', $year)
                ->with(['companies' => function ($query) {
                    $query->orderBy('rank');
                }])
                ->get();

            $companyIds = [];
            foreach ($trophees as $trophee) {
                foreach ($trophee->companies as $company) {
                    $companyIds[] = $company->id;
                }
            }

            // Charge tous les logos en une seule requête pour éviter le N+1
            $logos = Collection::whereIn('company_id', array_unique($companyIds))
                ->whereNotNull('logo_url')
                ->orderByDesc('start_date')
                ->get()
                ->groupBy('company_id')
                ->map(fn($cols) => $cols->first()->logo_url);

            $companies = [];
            foreach ($trophees as $trophee) {
                foreach ($trophee->companies as $company) {
                    $companies[] = [
                        'rank' => $company->pivot->rank,
                        'name' => $company->name,
                        'logo_url' => $logos[$company->id] ?? null,
                        'participant_count' => 0, // provisoire — remplacé par quiz_events en Phase 6
                    ];
                }
            }

            usort($companies, fn($a, $b) => $a['rank'] <=> $b['rank']);

            $allYears[] = [
                'year' => (int) $year,
                'companies' => $companies,
                'participant_count' => count(array_unique($companyIds)),
            ];
        }

        return response()->json([
            'podium' => $allYears[0] ?? null,
            'history' => array_slice($allYears, 1),
        ]);
    }
}
