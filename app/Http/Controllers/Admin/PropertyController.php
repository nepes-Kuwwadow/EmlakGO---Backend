<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Emläkleriň sanawyny getirmek
     * Eger admin bolsa ähli emläkler, eger ulanyjy bolsa diňe öz goýan emläkleri
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Property::with(['user', 'category']);

        // Eger admin däl bolsa, diňe öz goýan emläklerini görsün
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properties = $query->latest()->paginate(20);

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Emlägi tassyklamak (active edip bellemek) - Diňe Admin
     */
    public function approve(Property $property)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bu amal diňe adminler üçin elýeterlidir.');
        }

        $property->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Emläk tassyklandy.');
    }

    /**
     * Emlägi ret etmek - Diňe Admin
     */
    public function reject(Property $property)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bu amal diňe adminler üçin elýeterlidir.');
        }

        $property->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Emläk ret edildi.');
    }

    /**
     * Emlägi pozmak - Admin ýa-da emlägiň öz eýesi
     */
    public function destroy(Property $property)
    {
        $user = auth()->user();

        // Diňe admin ýa-da şol emlägi goýan ulanyjy pozup biler
        if (!$user->isAdmin() && $property->user_id !== $user->id) {
            abort(403, 'Bu emlägi pozmaga rugsadyňyz ýok.');
        }

        // Suratlary pozmak
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return redirect()->back()->with('success', 'Emläk üstünlikli pozuldy.');
    }
}
