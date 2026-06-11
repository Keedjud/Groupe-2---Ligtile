<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Mail\ContactConfirmationMail;
use App\Http\Controllers\Controller;
use App\Models\ContactStat;
use Illuminate\Support\Facades\Log;

class ApiContactController extends Controller
{
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string|regex:/^[+0-9\s]{7,20}$/|max:20',
            'email' => 'required|email|max:100',
            'company_name' => 'required|string|min:3|max:255',
            'employees_count' => 'required|integer|min:1',
            'street' => 'required|string|max:255|min:2',
            'postal_code' => 'required|string|min:1|max:255',
            'city' => 'required|string|min:3|max:255',
        ]);

        try {
            ContactStat::create([]);
            Mail::to('contact@hug-collecte.ch')->send(new ContactMail($validated));
            Mail::to($validated['email'])->send(new ContactConfirmationMail($validated));

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('ContactMail failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
}
}


