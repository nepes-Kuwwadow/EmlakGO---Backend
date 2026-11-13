<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Halananlaryň sanawy
     * Admin ählisini görýär, adaty ulanyjy diňe öz emläkleriniň halananlaryny görýär
     */
    public function index()
    {
        $user = auth()->user();

        $query = Favorite::with(['property.category', 'property.user', 'user']);

        if (!$user->isAdmin()) {
            $query->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $favorites = $query->latest()->paginate(20);

        return view('admin.favorites.index', compact('favorites'));
    }
}
