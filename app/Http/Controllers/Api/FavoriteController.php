<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Halananlara goşmak/aýyrmak (toggle)
     */
    public function toggle(Request $request, Property $property)
    {
        $user = $request->user();

        $favorite = Favorite::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'message'      => 'Halalanlardan aýyryldy.',
                'is_favorited' => false,
            ]);
        }

        Favorite::create([
            'user_id'     => $user->id,
            'property_id' => $property->id,
        ]);

        return response()->json([
            'message'      => 'Halalanlar sanawyna goşuldy.',
            'is_favorited' => true,
        ]);
    }

    /**
     * Ulanyjynyň halananlary
     */
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with(['property.primaryImage', 'property.category'])
            ->latest()
            ->paginate(12);

        return response()->json($favorites);
    }
}
