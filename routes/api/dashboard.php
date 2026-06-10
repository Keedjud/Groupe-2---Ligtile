<?php

use App\Http\Controllers\Api\v1\AdminSessionController;
use App\Http\Controllers\Api\v1\CollectionKitController;
use App\Http\Controllers\Api\v1\DashboardMetricsController;
use App\Http\Controllers\Api\v1\LogoUploadController;
use App\Http\Controllers\Api\v1\ManageCollectionController;
use App\Http\Controllers\Api\v1\ManageCompanyController;
use App\Http\Controllers\Api\v1\ManageTropheeController;
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
        Route::post('/manage-collections/{collecte}/link/generate', [ManageCollectionController::class, 'generateLink']);
        Route::post('/manage-collections/{collecte}/kit/send',      [CollectionKitController::class, 'send']);

        // Gestion des entreprises
        Route::get('/companies',              [ManageCompanyController::class, 'index']);
        Route::post('/companies',             [ManageCompanyController::class, 'store']);
        Route::get('/companies/{company}',    [ManageCompanyController::class, 'show']);
        Route::put('/companies/{company}',    [ManageCompanyController::class, 'update']);
        Route::delete('/companies/{company}', [ManageCompanyController::class, 'destroy']);

        // Gestion des trophées
        Route::get('/manage-trophees',         [ManageTropheeController::class, 'index']);
        Route::post('/manage-trophees',        [ManageTropheeController::class, 'store']);
        Route::put('/manage-trophees/{year}',  [ManageTropheeController::class, 'update']);
        Route::delete('/manage-trophees/{year}', [ManageTropheeController::class, 'destroy']);

        // Analytics / KPI
        Route::get('/analytics-stats', [DashboardMetricsController::class, 'overview']);
    });
});
