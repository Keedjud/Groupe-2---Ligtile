<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Trophee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageTropheeController extends Controller
{
    private const RANG_NOMS = [1 => 'Or', 2 => 'Argent', 3 => 'Bronze'];

    public function index()
    {
        $trophees = Trophee::with('companies:id,name')
            ->orderBy('year', 'desc')
            ->orderBy('id')
            ->get();

        $parAnnee = $trophees->groupBy('year')->map(function ($groupe, $annee) {
            return [
                'year' => (int) $annee,
                'trophees' => $groupe->map(function ($t) {
                    $company = $t->companies->first();
                    return [
                        'id'      => $t->id,
                        'rank'    => $company?->pivot->rank,
                        'company' => $company ? ['id' => $company->id, 'name' => $company->name] : null,
                    ];
                })->sortBy('rank')->values(),
            ];
        })->values();

        return response()->json($parAnnee);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year'               => ['required', 'integer', 'min:2000', 'max:2100'],
            'ranks'              => ['required', 'array', 'size:3'],
            'ranks.*.rank'       => ['required', 'integer', 'in:1,2,3'],
            'ranks.*.company_id' => ['required', 'integer', 'exists:companies,id'],
        ]);

        if (Trophee::where('year', $validated['year'])->exists()) {
            return response()->json([
                'message' => "Un podium existe déjà pour l'année {$validated['year']}.",
            ], 422);
        }

        DB::transaction(function () use ($validated) {
            foreach ($validated['ranks'] as $item) {
                $nom = 'Trophée ' . self::RANG_NOMS[$item['rank']] . ' ' . $validated['year'];
                $trophee = Trophee::create(['name' => $nom, 'year' => $validated['year']]);
                $trophee->companies()->attach($item['company_id'], ['rank' => $item['rank']]);
            }
        });

        return response()->json(['year' => $validated['year']], 201);
    }

    public function update(Request $request, int $year)
    {
        $validated = $request->validate([
            'ranks'              => ['required', 'array', 'size:3'],
            'ranks.*.rank'       => ['required', 'integer', 'in:1,2,3'],
            'ranks.*.company_id' => ['required', 'integer', 'exists:companies,id'],
        ]);

        $trophees = Trophee::where('year', $year)->get();
        if ($trophees->isEmpty()) {
            return response()->json(['message' => "Aucun podium trouvé pour l'année {$year}."], 404);
        }

        DB::transaction(function () use ($validated, $trophees, $year) {
            foreach ($trophees as $t) {
                $t->companies()->detach();
                $t->delete();
            }
            foreach ($validated['ranks'] as $item) {
                $nom = 'Trophée ' . self::RANG_NOMS[$item['rank']] . ' ' . $year;
                $trophee = Trophee::create(['name' => $nom, 'year' => $year]);
                $trophee->companies()->attach($item['company_id'], ['rank' => $item['rank']]);
            }
        });

        return response()->json(['year' => $year]);
    }

    public function destroy(int $year)
    {
        $trophees = Trophee::where('year', $year)->get();
        if ($trophees->isEmpty()) {
            return response()->json(['message' => "Aucun podium trouvé pour l'année {$year}."], 404);
        }

        DB::transaction(function () use ($trophees) {
            foreach ($trophees as $t) {
                $t->companies()->detach();
                $t->delete();
            }
        });

        return response()->noContent();
    }
}
