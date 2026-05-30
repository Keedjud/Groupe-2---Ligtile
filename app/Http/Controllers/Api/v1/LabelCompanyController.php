<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabelCompanyController extends Controller
{
    /**
     * Retourne les entreprises labellisées avec filtres et pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Company::whereHas('labels')
            ->with(['labels' => function ($q) {
                $q->orderByDesc('start_date')->limit(1);
            }])
            ->with(['collections' => function ($q) {
                $q->orderByDesc('start_date')->limit(1);
            }]);

        // Filtre par recherche texte (nom de l'entreprise)
        if ($search = $request->query('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filtre par taille d'entreprise
        if ($size = $request->query('size')) {
            [$min, $max] = $this->sizeRange($size);
            $query->whereBetween('nb_employee', [$min, $max]);
        }

        // Filtre par année de labellisation (basé sur start_date du pivot)
        if ($year = $request->query('year')) {
            $query->whereHas('labels', function ($q) use ($year) {
                $q->whereYear('start_date', $year);
            });
        }

        $companies = $query->orderBy('name')->paginate(8);

        return response()->json($companies);
    }

    /**
     * Retourne les années disponibles pour le filtre "Labellisée depuis".
     */
    public function years(): JsonResponse
    {
        $yearExpr = DB::connection()->getDriverName() === 'sqlite'
            ? 'strftime("%Y", start_date)'
            : 'YEAR(start_date)';

        $years = DB::table('company_label')
            ->selectRaw("DISTINCT {$yearExpr} as year")
            ->orderByDesc('year')
            ->pluck('year');

        return response()->json($years);
    }

    /**
     * Convertit une catégorie de taille en plage [min, max] pour nb_employee.
     */
    private function sizeRange(string $size): array
    {
        return match ($size) {
            'grande'  => [250, 1_000_000],
            'moyenne' => [50, 249],
            'petite'  => [10, 49],
            default   => [0, 9],
        };
    }
}
