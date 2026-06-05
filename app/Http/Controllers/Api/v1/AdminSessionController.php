<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSessionController extends Controller
{
    public function connect(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'message' => 'Connecté avec succès',
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'message' => 'Identifiants invalides',
            'errors' => [
                'email' => ['Les identifiants fournis ne correspondent pas']
            ]
        ], 422);
    }

    public function disconnect(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function currentUser(Request $request)
    {
        return response()->json($request->user());
    }
}
