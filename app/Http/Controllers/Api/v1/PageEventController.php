<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PageEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'collection_id' => 'required|integer|exists:collections,id',
            'session_id'    => 'required|uuid',
            'event_type'    => 'required|string|in:prevention_entered,prevention_exited',
            'engaged'       => 'nullable|boolean',
            'time_on_page'  => 'nullable|integer|min:0',
        ]);

        PageEvent::create($validated);

        return response()->json(null, 201);
    }
}
