<?php

use App\Http\Controllers\Api\v1\ApiTropheeController;
use App\Http\Controllers\Api\v1\ApiLabelCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ApiContactController;
use App\Http\Controllers\Api\v1\ApiPmeContactController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 — site public
Route::prefix('v1')->group(function () {
    Route::get('/label-companies', [ApiLabelCompanyController::class, 'index']);
    Route::get('/label-years', [ApiLabelCompanyController::class, 'years']);
    Route::get('/trophees', [ApiTropheeController::class, 'index']);
    Route::post('/contact', [ApiContactController::class, 'contact']);
    Route::post('/pme-contact', [ApiPmeContactController::class, 'contactPme']);
});
