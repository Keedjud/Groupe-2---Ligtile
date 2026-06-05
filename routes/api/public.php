<?php

use App\Http\Controllers\Api\v1\ApiContactController;
use App\Http\Controllers\Api\v1\ApiLabelCompanyController;
use App\Http\Controllers\Api\v1\ApiPmeContactController;
use App\Http\Controllers\Api\v1\ApiTropheeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/label-companies', [ApiLabelCompanyController::class, 'index']);
    Route::get('/trophees',        [ApiTropheeController::class,      'index']);
    Route::post('/contact',        [ApiContactController::class,      'contact']);
    Route::post('/pme-contact',    [ApiPmeContactController::class,   'contactPme']);
});
