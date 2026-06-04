<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'logo' => ['required', 'file', 'image', 'mimes:jpeg,png,gif,webp,svg+xml', 'max:1024'],
        ]);

        $path = $request->file('logo')->store('logos', 'public');

        return response()->json([
            'logo_url' => '/storage/' . $path,
        ], 201);
    }
}
