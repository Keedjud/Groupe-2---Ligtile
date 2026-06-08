<?php

use Illuminate\Support\Facades\Route;

// Site public principal
Route::get('/', fn() => view('public'));

// Dashboard CTS (protégé côté Vue par useAuth — la route web est publique,
// l'authentification est gérée par Sanctum sur les routes API)
Route::get('/dashboard', fn() => view('dashboard'));

// Sites cobrandés — toutes les URLs restantes qui ne sont pas des routes réservées
Route::get('/{cobrandToken}', fn(string $cobrandToken) => view('cobrand', ['cobrandToken' => $cobrandToken]))
    ->where('cobrandToken', '[a-zA-Z0-9_\-]+');
