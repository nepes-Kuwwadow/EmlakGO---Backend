<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Ähli kategoriýalary sanaw
     */
    public function index()
    {
        $categories = Category::withCount('properties')->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }
}
