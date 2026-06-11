<?php

use App\Http\Controllers\Api\v1\ContactController;
use App\Http\Controllers\Api\v1\LabelCompanyController;
use App\Http\Controllers\Api\v1\PmeContactController;
use App\Http\Controllers\Api\v1\TropheeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/label-companies', [LabelCompanyController::class, 'index']);
    Route::get('/trophees',        [TropheeController::class,      'index']);
    Route::post('/contact',        [ContactController::class,      'contact']);
    Route::post('/pme-contact',    [PmeContactController::class,   'contactPme']);
});
