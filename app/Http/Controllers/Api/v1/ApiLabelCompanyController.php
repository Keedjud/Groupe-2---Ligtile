<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiLabelCompanyController extends Controller
{
    /**
     * Retourne les entreprises labellisées avec filtres et pagination.
     *
     * ?status=active  (défaut) — au moins un label en cours (end_date >= aujourd'hui)
     *                            → charge le label actif le plus récent
     * ?status=expired           — au moins un label échu (end_date < aujourd'hui)
     *                            → charge le label échu le plus récent
     *
     * Une entreprise avec un ancien label échu ET un nouveau label actif
     * apparaît dans les deux sections, avec le label approprié dans chaque cas.
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status', 'active');
        $now    = now();

        if ($status === 'expired') {
            $query = Company::with(['labels' => function ($q) use ($now) {
                    // Label échu le plus récent.
                    $q->where('end_date', '<', $now)
                      ->orderByDesc('end_date')
                      ->limit(1);
                }])
                ->with(['collections' => function ($q) {
                    $q->orderByDesc('start_date')->limit(1);
                }])
                // A eu au moins un label échu (indépendamment des labels actifs).
                ->whereHas('labels', fn ($q) => $q->where('end_date', '<', $now));
        } else {
            $query = Company::with(['labels' => function ($q) use ($now) {
                    // Label actif le plus récent.
                    $q->where('end_date', '>=', $now)
                      ->orderByDesc('start_date')
                      ->limit(1);
                }])
                ->with(['collections' => function ($q) {
                    $q->orderByDesc('start_date')->limit(1);
                }])
                // Au moins un label encore valide.
                ->whereHas('labels', fn ($q) => $q->where('end_date', '>=', $now));
        }

        // Filtre par recherche texte (nom de l'entreprise)
        if ($search = $request->query('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filtre par taille d'entreprise
        if ($size = $request->query('size')) {
            [$min, $max] = $this->sizeRange($size);
            $query->whereBetween('nb_employee', [$min, $max]);
        }

        $companies = $query->orderBy('name')->paginate(8);

        return response()->json($companies);
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
