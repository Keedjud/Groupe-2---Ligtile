<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\QuizEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'collection_id' => 'required|integer|exists:collections,id',
            'session_id'    => 'required|uuid',
            'event_type'    => 'required|string|in:quiz_started,question_answered,question_skipped,form_skipped_from,p1_eliminated,p1_completed,p2_completed,quiz_completed,onedoc_clicked',
            'part'          => 'nullable|integer|in:1,2',
            'question_slug' => 'nullable|string|max:100',
            'answer_result' => 'nullable|string|in:correct,incorrect',
        ]);

        QuizEvent::create($validated);

        return response()->json(null, 201);
    }
}
