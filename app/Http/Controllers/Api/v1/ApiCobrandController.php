<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;

class ApiCobrandController extends Controller
{
    public function show(string $token)
    {
        $collection = Collection::where('public_token', $token)
            ->with(['company', 'address'])
            ->firstOrFail();

        return response()->json([
            'company_name'    => $collection->company->name,
            'start_date'      => $collection->start_date,
            'end_date'        => $collection->end_date,
            'capacity'        => $collection->capacity,
            'primary_color'   => $collection->primary_color,
            'secondary_color' => $collection->secondary_color,
            'logo_url'        => $collection->logo_url,
            'onedoc_url'      => $collection->onedoc_url,
            'address'         => [
                'street'      => $collection->address->street,
                'number'      => $collection->address->number,
                'postal_code' => $collection->address->postal_code,
                'city'        => $collection->address->city,
            ],
        ]);
    }
}
