<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitCategory;
use Illuminate\Http\Request;

class HomeVisitCategoryController extends Controller
{
    /**
     * Get Home Visit Categories
     */
    public function index(Request $request)
    {
        $categories = HomeVisitCategory::select(
            'id',
            'name',
            'slug',
            'image',
            'description'
        )
            ->latest()
            ->get();

        $categories->transform(function ($category) {

            $category->image = $category->image
                ? asset($category->image)
                : null;

            return $category;
        });

        return response()->json([
            'status' => 1,
            'message' => 'Home visit categories fetched successfully.',
            'data' => $categories,
        ]);
    }
}
