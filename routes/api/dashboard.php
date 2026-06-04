<?php

use App\Http\Controllers\Api\v1\AdminSessionController;
use App\Http\Controllers\Api\v1\CollectionKitController;
use App\Http\Controllers\Api\v1\DashboardMetricsController;
use App\Http\Controllers\Api\v1\LogoUploadController;
use App\Http\Controllers\Api\v1\ManageCollectionController;
use Illuminate\Support\Facades\Route;

// Routes dashboard CTS
Route::prefix('v1')->group(function () {
    // Authentification
    Route::post('/session/connect', [AdminSessionController::class, 'connect']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/session/disconnect',    [AdminSessionController::class, 'disconnect']);
        Route::get('/session/current-user',    [AdminSessionController::class, 'currentUser']);

        // Gestion des collectes
        Route::get('/manage-collections',              [ManageCollectionController::class, 'index']);
        Route::post('/manage-collections',             [ManageCollectionController::class, 'store']);
        Route::get('/manage-collections/{collecte}',    [ManageCollectionController::class, 'show']);
        Route::put('/manage-collections/{collecte}',    [ManageCollectionController::class, 'update']);
        Route::delete('/manage-collections/{collecte}', [ManageCollectionController::class, 'destroy']);

        // Upload logo collecte
        Route::post('/logos/upload', [LogoUploadController::class, 'upload']);

        // Kit de communication
        Route::post('/manage-collections/{collecte}/kit/send', [CollectionKitController::class, 'send']);

        // Analytics / KPI
        Route::get('/analytics-stats', [DashboardMetricsController::class, 'overview']);
    });
});
