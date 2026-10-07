<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitServiceCategory;
use Illuminate\Http\Request;

class HomeVisitServiceCategoryController extends Controller
{
    /**
     * Get all Home Visit Service Categories
     */
    public function index(Request $request)
    {

        $query = HomeVisitServiceCategory::query();

        // Filter by category type
        if ($request->filled('category_type')) {
            $query->where(
                'category_type',
                $request->category_type
            );
        }
        $categories = $query->latest()->get();


        $data = $categories->map(function ($category) {

            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                // Parent Home Visit Category
                'home_visit_category_id' =>
                    $category->home_visit_category_id,

                'home_visit_category_name' =>
                    $category->homeVisitCategory
                    ? $category->homeVisitCategory->name
                    : null,
                'category_type' => $category->category_type,
                'image' => $category->image
                    ? asset($category->image)
                    : null,
            ];
        });

        return response()->json([
            'success' => 1,
            'message' => 'Home Visit Service Categories fetched successfully.',
            'data' => $data,
        ], 200);
    }


    /**
     * Get single Home Visit Service Category
     */
    public function show($id)
    {
        $category = HomeVisitServiceCategory::find($id);

        if (!$category) {
            return response()->json([
                'success' => 0,
                'message' => 'Home Visit Service Category not found.',
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Home Visit Service Category fetched successfully.',
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                // Parent Home Visit Category
                'home_visit_category_id' =>
                    $category->home_visit_category_id,

                'home_visit_category_name' =>
                    $category->homeVisitCategory
                    ? $category->homeVisitCategory->name
                    : null,
                'category_type' => $category->category_type,
                'image' => $category->image
                    ? asset($category->image)
                    : null,
            ],
        ], 200);
    }
}
