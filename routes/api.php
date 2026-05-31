<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ContactController;


Route::post('/contact', [ContactController::class, 'contact']);
