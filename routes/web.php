<?php

use Illuminate\Support\Facades\Route;

// Site public principal
Route::get('/', fn() => view('public'));

// Dashboard CTS (protégé côté Vue par useAuth — la route web est publique,
// l'authentification est gérée par Sanctum sur les routes API)
Route::get('/dashboard', fn() => view('dashboard'));

// Sites cobrandés — toutes les URLs restantes qui ne sont pas des routes réservées
Route::get('/{collecteId}', fn(string $collecteId) => view('cobrand', ['collecteId' => $collecteId]))
    ->where('collecteId', '[a-zA-Z0-9_\-]+');
