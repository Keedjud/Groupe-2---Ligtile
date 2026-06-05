<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageCompanyController extends Controller
{
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

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:100', Rule::unique('companies')->ignore($company->id)],
            'nb_employee'         => ['required', 'integer', 'min:1'],
            'address.street'      => ['required', 'string', 'max:100'],
            'address.number'      => ['required', 'string', 'max:10'],
            'address.postal_code' => ['required', 'digits:4'],
            'address.city'        => ['required', 'string', 'max:100'],
            'contact.email'       => ['required', 'email', 'max:100'],
            'contact.phone'       => ['nullable', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated, $company) {
            $company->update([
                'name'        => $validated['name'],
                'nb_employee' => $validated['nb_employee'],
            ]);

            if ($company->address) {
                $company->address->update($validated['address']);
            }

            if ($company->contact) {
                $company->contact->update([
                    'email' => $validated['contact']['email'],
                    'phone' => $validated['contact']['phone'] ?? null,
                ]);
            }
        });

        return response()->json(
            $company->load(['contact', 'address'])
                     ->loadCount('collections')
        );
    }
}
