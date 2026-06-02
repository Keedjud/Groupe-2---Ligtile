<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require __DIR__ . '/api/public.php';
require __DIR__ . '/api/dashboard.php';
require __DIR__ . '/api/cobrand.php';
