<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ApiCobrandController;
use App\Http\Controllers\Api\v1\QuizEventController;
use App\Http\Controllers\Api\v1\PageEventController;

Route::prefix('v1')->group(function () {
    Route::get('/cobrand/{token}', [ApiCobrandController::class, 'show']);
    Route::post('/quiz/event', [QuizEventController::class, 'store'])
        ->middleware('throttle:60,1');
    Route::post('/page/event', [PageEventController::class, 'store'])
        ->middleware('throttle:60,1');
});
