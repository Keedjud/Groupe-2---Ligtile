<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ApiCobrandController;

Route::get('/cobrand/{token}', [ApiCobrandController::class, 'show']);

// À venir (Phase 6)
// POST /api/v1/quiz/event
// POST /api/v1/page/event
