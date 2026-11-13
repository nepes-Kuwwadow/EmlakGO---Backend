<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Emläklere gelen soraglaryň sanawy
     * Admin ählisini görýär, adaty ulanyjy diňe öz emläklerine gelen soraglary görýär
     */
    public function index()
    {
        $user = auth()->user();

        $query = ContactInquiry::with(['property.user']);

        if (!$user->isAdmin()) {
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $inquiries = $query->latest()->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Soragy pozmak
     */
    public function destroy(ContactInquiry $inquiry)
    {
        $user = auth()->user();

        // Diňe admin ýa-da emlägiň eýesi pozup biler
        if (!$user->isAdmin() && $inquiry->property && $inquiry->property->user_id !== $user->id) {
            abort(403, 'Rugsat ýok.');
        }

        $inquiry->delete();

        return redirect()->back()->with('success', 'Sorag üstünlikli pozuldy.');
    }
}
