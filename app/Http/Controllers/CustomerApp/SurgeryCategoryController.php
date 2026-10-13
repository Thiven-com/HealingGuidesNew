<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\SurgeryCategory;
use Illuminate\Http\Request;

class SurgeryCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SurgeryCategory::query();

        // Search by category name
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('name', 'like', '%' . $search . '%');
        }

        $categories = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => 1,
            'message' => 'Surgery categories fetched successfully.',
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image
                        ? asset($category->image)
                        : null,
                    'description' => $category->description,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ];
            })->values(),
        ]);
    }
}
