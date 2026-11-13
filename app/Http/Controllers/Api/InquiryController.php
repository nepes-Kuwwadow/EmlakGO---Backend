<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Property;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Habarlaşma soragy döretmek
     */
    public function store(Request $request, Property $property)
    {
        if ($property->status !== 'active') {
            return response()->json(['message' => 'Bu emläk üçin sorag iberip bolmaýar.'], 422);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        $inquiry = ContactInquiry::create([
            'property_id' => $property->id,
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'message'     => $validated['message'],
        ]);

        return response()->json([
            'message' => 'Soragyňyz üstünlikli iberildi.',
            'inquiry' => $inquiry,
        ], 201);
    }
}
