<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Trophee;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ApiTropheeController extends Controller
{
    public function index(): JsonResponse
    {
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

        $yearExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y', start_date)"
            : "YEAR(start_date)";

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

            $uniqueCompanyIds = array_unique($companyIds);

            // Logos — une requête pour éviter le N+1
            $logos = Collection::whereIn('company_id', $uniqueCompanyIds)
                ->whereNotNull('logo_url')
                ->orderByDesc('start_date')
                ->get()
                ->groupBy('company_id')
                ->map(fn($cols) => $cols->first()->logo_url);

            // Inscrits par entreprise (clics Onedoc distincts) — une requête batch
            $participantCounts = DB::table('quiz_events')
                ->join('collections', 'quiz_events.collection_id', '=', 'collections.id')
                ->where('quiz_events.event_type', 'onedoc_clicked')
                ->whereIn('collections.company_id', $uniqueCompanyIds)
                ->whereRaw("{$yearExpr} = ?", [$year])
                ->groupBy('collections.company_id')
                ->select('collections.company_id', DB::raw('COUNT(DISTINCT quiz_events.session_id) as count'))
                ->pluck('count', 'company_id');

            $companies = [];
            foreach ($trophees as $trophee) {
                foreach ($trophee->companies as $company) {
                    $companies[] = [
                        'rank'              => $company->pivot->rank,
                        'name'              => $company->name,
                        'logo_url'          => $logos[$company->id] ?? null,
                        'participant_count' => (int) ($participantCounts[$company->id] ?? 0),
                    ];
                }
            }

            usort($companies, fn($a, $b) => $a['rank'] <=> $b['rank']);

            $allYears[] = [
                'year'            => (int) $year,
                'companies'       => $companies,
                'companies_count' => count($uniqueCompanyIds),
            ];
        }

        return response()->json([
            'podium'  => $allYears[0] ?? null,
            'history' => array_slice($allYears, 1),
        ]);
    }
}
