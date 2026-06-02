<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactPmeMail;
use App\Http\Controllers\Controller;
use App\Models\ContactStat;

class ApiPmeContactController extends Controller
{
    public function contactPme(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:100',
            'company_name' => 'required|string|min:3|max:255',
            'message' => 'required|string|min:2'
        ]);

        try {
            ContactStat::create([]);
            Mail::to('contact@hug-collecte.ch')->send(new ContactPmeMail($validated));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
}
}


