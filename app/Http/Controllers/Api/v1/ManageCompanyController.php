<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageCompanyController extends Controller
{
    private function reglesValidation(int $ignoreId = 0): array
    {
        return [
            'name'                => ['required', 'string', 'max:100', Rule::unique('companies')->ignore($ignoreId)],
            'nb_employee'         => ['required', 'integer', 'min:1'],
            'address.street'      => ['required', 'string', 'max:100'],
            'address.number'      => ['required', 'string', 'max:10'],
            'address.postal_code' => ['required', 'digits:4'],
            'address.city'        => ['required', 'string', 'max:100'],
            'contact.email'       => ['required', 'email', 'max:100'],
            'contact.phone'       => ['required', 'string', 'max:50', 'regex:/^[+\d][\d\s()\/.\-]{5,}$/'],
            'participe_trophee'   => ['boolean'],
        ];
    }

    public function index(Request $request)
    {
        $search = $request->query('search');

        $companies = Company::with(['contact', 'address'])
            ->withCount('collections')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get();

        return response()->json($companies);
    }

    public function show(Company $company)
    {
        $company->load(['contact', 'address'])
                ->loadCount('collections');

        $company->load(['collections' => fn ($q) => $q->orderBy('start_date', 'desc')
            ->select(['id', 'company_id', 'start_date', 'end_date', 'capacity'])]);

        return response()->json($company);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->reglesValidation());

        $company = DB::transaction(function () use ($validated) {
            $address = Address::create($validated['address']);
            $company = Company::create([
                'name'              => $validated['name'],
                'nb_employee'       => $validated['nb_employee'],
                'participe_trophee' => $validated['participe_trophee'] ?? true,
                'address_id'        => $address->id,
            ]);
            Contact::create([
                'company_id' => $company->id,
                'email'      => $validated['contact']['email'],
                'phone'      => $validated['contact']['phone'],
            ]);
            return $company;
        });

        return response()->json(
            $company->load(['contact', 'address'])->loadCount('collections'),
            201
        );
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate($this->reglesValidation($company->id));

        DB::transaction(function () use ($validated, $company) {
            $company->update([
                'name'              => $validated['name'],
                'nb_employee'       => $validated['nb_employee'],
                'participe_trophee' => $validated['participe_trophee'] ?? true,
            ]);

            if ($company->address) {
                $company->address->update($validated['address']);
            }

            if ($company->contact) {
                $company->contact->update([
                    'email' => $validated['contact']['email'],
                    'phone' => $validated['contact']['phone'],
                ]);
            }
        });

        return response()->json(
            $company->load(['contact', 'address'])
                     ->loadCount('collections')
        );
    }

    public function destroy(Company $company)
    {
        if ($company->collections()->exists()) {
            return response()->json([
                'message' => "Impossible de supprimer : cette entreprise possède des collectes. Supprimez-les d'abord.",
            ], 422);
        }

        DB::transaction(function () use ($company) {
            $company->labels()->detach();
            $company->trophees()->detach();
            $company->contact?->delete();
            $company->delete();
        });

        return response()->noContent();
    }
}
