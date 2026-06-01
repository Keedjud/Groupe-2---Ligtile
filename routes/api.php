<?php

use App\Http\Controllers\Api\v1\api_TropheeController;
use App\Http\Controllers\Api\v1\api_LabelCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\api_ContactController;


Route::post('/contact', [api_ContactController::class, 'contact']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 — site public
Route::prefix('v1')->group(function () {
    Route::get('/label-companies', [api_LabelCompanyController::class, 'index']);
    Route::get('/label-years', [api_LabelCompanyController::class, 'years']);
    Route::get('/trophees', [api_TropheeController::class, 'index']);
});
