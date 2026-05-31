<?php

use App\Http\Controllers\Api\v1\ApiTropheeController;
use App\Http\Controllers\Api\v1\LabelCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContactController;


Route::post('/contact', [ContactController::class, 'contact']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 — site public
Route::prefix('v1')->group(function () {
    Route::get('/label-companies', [LabelCompanyController::class, 'index']);
    Route::get('/label-years', [LabelCompanyController::class, 'years']);
    Route::get('/trophees', [ApiTropheeController::class, 'index']);
});
