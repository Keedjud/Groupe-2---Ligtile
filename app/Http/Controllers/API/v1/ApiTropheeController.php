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
            // Récupère tous les trophées de l'année avec leurs companies et le pivot rank
            $trophees = Trophee::where('year', $year)
                ->with(['companies' => function ($query) {
                    $query->orderBy('rank');
                }])
                ->get();

            // Collecte toutes les companies avec leur rank pour cette année
            $companies = [];
            $companyIds = [];

            foreach ($trophees as $trophee) {
                foreach ($trophee->companies as $company) {
                    $companyIds[] = $company->id;

                    // Calcule le nombre de participants pour cette company
                    // en sommant les nb_registered de ses collections dans l'année du trophée
                    $participantCount = Collection::where('company_id', $company->id)
                        ->whereYear('start_date', $year)
                        ->sum('nb_registered');

                    // Récupère le logo depuis la collection la plus récente de cette company
                    $latestCollection = Collection::where('company_id', $company->id)
                        ->whereNotNull('logo_url')
                        ->latest('start_date')
                        ->first();

                    $companies[] = [
                        'rank' => $company->pivot->rank,
                        'name' => $company->name,
                        'logo_url' => $latestCollection?->logo_url,
                        'participant_count' => (int) $participantCount,
                    ];
                }
            }

            // Trie par rank
            usort($companies, fn($a, $b) => $a['rank'] <=> $b['rank']);

            // Nombre total distinct de companies participantes cette année
            $participantCount = count(array_unique($companyIds));

            $allYears[] = [
                'year' => (int) $year,
                'companies' => $companies,
                'participant_count' => $participantCount,
            ];
        }

        return response()->json([
            'podium' => $allYears[0] ?? null,
            'history' => array_slice($allYears, 1),
        ]);
    }
}
