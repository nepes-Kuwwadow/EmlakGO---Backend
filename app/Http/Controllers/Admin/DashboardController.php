<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Models\Favorite;
use App\Models\Property;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Admin panel baş sahypasy - statistika
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'users'      => User::where('role', 'user')->count(),
                'properties' => Property::count(),
                'pending'    => Property::where('status', 'pending')->count(),
                'active'     => Property::where('status', 'active')->count(),
                'rejected'   => Property::where('status', 'rejected')->count(),
                'inquiries'  => ContactInquiry::count(),
                'favorites'  => Favorite::count(),
            ];

            $recentProperties = Property::with(['user', 'category'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Eger adaty ulanyjy bolsa: diňe öz emläkleri we özüne degişli statistika
            $myPropertiesQuery = Property::where('user_id', $user->id);
            $myPropertyIds = (clone $myPropertiesQuery)->pluck('id');

            $stats = [
                'users'      => null,
                'properties' => (clone $myPropertiesQuery)->count(),
                'pending'    => (clone $myPropertiesQuery)->where('status', 'pending')->count(),
                'active'     => (clone $myPropertiesQuery)->where('status', 'active')->count(),
                'rejected'   => (clone $myPropertiesQuery)->where('status', 'rejected')->count(),
                'inquiries'  => ContactInquiry::whereIn('property_id', $myPropertyIds)->count(),
                'favorites'  => Favorite::whereIn('property_id', $myPropertyIds)->count(),
            ];

            $recentProperties = Property::where('user_id', $user->id)
                ->with(['category'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact('stats', 'recentProperties'));
    }
}
