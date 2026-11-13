<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Emläk sanawyny getirmek (süzgüçler bilen)
     */
    public function index(Request $request)
    {
        $query = Property::with(['category', 'primaryImage', 'user'])
            ->where('status', 'active');

        // Şäher boýunça süzgüç
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Kategoriýa boýunça süzgüç
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Iň pes baha süzgüji
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Iň ýokary baha süzgüji
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Otaglar sany boýunça süzgüç
        if ($request->filled('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        $properties = $query->latest()->paginate(12);

        return response()->json($properties);
    }

    /**
     * Bir emlägiň doly maglumatlaryny almak
     */
    public function show(Property $property)
    {
        if ($property->status !== 'active') {
            return response()->json(['message' => 'Emläk tapylmady.'], 404);
        }

        $property->load(['category', 'images', 'user', 'comments.user']);
        $property->loadCount('comments');

        return response()->json([
            'property' => $property,
        ]);
    }

    /**
     * Täze emläk döretmek
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'address'     => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'rooms'       => 'nullable|integer|min:0',
            'area'        => 'nullable|numeric|min:0',
            'images'      => 'nullable|array|max:10',
            'images.*'    => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $property = $request->user()->properties()->create([
            'category_id' => $validated['category_id'],
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'address'     => $validated['address'],
            'city'        => $validated['city'],
            'rooms'       => $validated['rooms'] ?? null,
            'area'        => $validated['area'] ?? null,
            'status'      => 'pending',
        ]);

        // Suratlary ýüklemek
        if ($request->hasFile('images')) {
            $isPrimary = true;
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('properties', 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path'  => $path,
                    'is_primary'  => $isPrimary,
                ]);
                $isPrimary = false;
            }
        }

        $property->load(['category', 'images']);

        return response()->json([
            'message'  => 'Emläk üstünlikli döredildi. Admin tassyklamasyna garaşylýar.',
            'property' => $property,
        ], 201);
    }

    /**
     * Emlägi täzelemek
     */
    public function update(Request $request, Property $property)
    {
        // Diňe eýesi ýa-da admin üýtgedip biler
        if ($request->user()->id !== $property->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Rugsat ýok.'], 403);
        }

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price'       => 'sometimes|numeric|min:0',
            'address'     => 'sometimes|string|max:255',
            'city'        => 'sometimes|string|max:100',
            'rooms'       => 'nullable|integer|min:0',
            'area'        => 'nullable|numeric|min:0',
        ]);

        $property->update($validated);

        return response()->json([
            'message'  => 'Emläk üstünlikli täzelendi.',
            'property' => $property->fresh(['category', 'images']),
        ]);
    }

    /**
     * Emlägi pozmak
     */
    public function destroy(Request $request, Property $property)
    {
        // Diňe eýesi ýa-da admin pozup biler
        if ($request->user()->id !== $property->user_id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Rugsat ýok.'], 403);
        }

        // Suratlary pozmak
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return response()->json([
            'message' => 'Emläk üstünlikli pozuldy.',
        ]);
    }
}
