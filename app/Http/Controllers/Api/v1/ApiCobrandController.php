<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Collection;

class ApiCobrandController extends Controller
{
    public function show(string $token)
    {
        $collection = Collection::where('public_token', $token)
            ->with('company')
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
            'contact_email'   => $collection->contact_email,
            'contact_phone'   => $collection->contact_phone,
            'venue'           => [
                'street'      => $collection->venue_street,
                'number'      => $collection->venue_number,
                'postal_code' => $collection->venue_postal_code,
                'city'        => $collection->venue_city,
            ],
        ]);
    }
}
