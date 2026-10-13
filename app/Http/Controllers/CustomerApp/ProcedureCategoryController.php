<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\ProcedureCategory;
use Illuminate\Http\Request;

class ProcedureCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcedureCategory::query();

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
            'message' => 'Procedure categories fetched successfully.',
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
