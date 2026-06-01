<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'phone_number' => 'nullable|string|regex:/^[+0-9\s]{7,20}$/|max:20',
            'email' => 'required|email|max:100',
            'company_name' => 'required|string|max:255',
            'employees_count' => 'required|integer|min:1000',
        ]);

        try {
            Mail::to('contact@hug-collecte.ch')->send(new ContactMail($validated));

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


