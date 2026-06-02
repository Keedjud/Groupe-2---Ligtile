<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ApiCobrandController;

Route::prefix('v1')->group(function () {
    Route::get('/cobrand/{token}', [ApiCobrandController::class, 'show']);

    // À venir (Phase 6)
    // Route::post('/quiz/event', ...)
    // Route::post('/page/event', ...)
});
