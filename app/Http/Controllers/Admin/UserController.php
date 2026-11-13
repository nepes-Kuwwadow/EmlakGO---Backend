<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Ähli ulanyjylaryň sanawyny getirmek (Diňe Admin)
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bu sahypa diňe admin üçin elýeterlidir.');
        }

        $users = User::withCount(['properties', 'favorites'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Ulanyjyny pozmak (Diňe Admin)
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Bu amal diňe admin üçin elýeterlidir.');
        }

        // Admini pozmak gadagan
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Admini pozup bolmaýar.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Ulanyjy üstünlikli pozuldy.');
    }
}
