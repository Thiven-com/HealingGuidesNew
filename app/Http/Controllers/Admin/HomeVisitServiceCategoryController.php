<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitCategory;
use App\Models\HomeVisitServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HomeVisitServiceCategoryController extends Controller
{
    /**
     * Display all Home Visit Service Categories.
     */
    public function index()
    {
        $categories = HomeVisitServiceCategory::latest()->paginate(20);

        $homeVisitCategories = HomeVisitCategory::orderBy('name')
            ->get();

        return view('admin.home_visit_service_categories.index', compact('categories', 'homeVisitCategories'));
    }

    /**
     * Store a new Home Visit Service Category.
     */
  public function store(Request $request)
    {
        $request->validate([
            'home_visit_category_id' => [
                'required',
                'exists:home_visit_categories,id',
            ],
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:home_visit_service_categories,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Parent Home Visit Category
        |--------------------------------------------------------------------------
        */
        $homeVisitCategory = HomeVisitCategory::findOrFail(
            $request->home_visit_category_id
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Service Category Slug
        |--------------------------------------------------------------------------
        */
        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (
            HomeVisitServiceCategory::where('slug', $slug)->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        /*
        |--------------------------------------------------------------------------
        | Category Type
        |--------------------------------------------------------------------------
        | Automatically store home_visit_categories.slug
        */
        $categoryType = $homeVisitCategory->slug;

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'home_visit_service_categories',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Service Category
        |--------------------------------------------------------------------------
        */
        HomeVisitServiceCategory::create([
            'home_visit_category_id' => $homeVisitCategory->id,
            'name' => $request->name,
            'slug' => $slug,
            'category_type' => $categoryType,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.home-visit-service-categories.index')
            ->with(
                'success',
                'Home Visit Service Category added successfully.'
            );
    }

    /**
     * Show edit page.
     */
    public function edit($id)
    {
        $category = HomeVisitServiceCategory::findOrFail($id);

        return view(
            'admin.home_visit_service_categories.edit',
            compact('category')
        );
    }

    /**
     * Update Home Visit Service Category.
     */
     public function update(Request $request, $id)
    {
        $category = HomeVisitServiceCategory::findOrFail($id);

        $request->validate([
            'home_visit_category_id' => [
                'required',
                'exists:home_visit_categories,id',
            ],
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:home_visit_service_categories,slug,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Parent Home Visit Category
        |--------------------------------------------------------------------------
        */
        $homeVisitCategory = HomeVisitCategory::findOrFail(
            $request->home_visit_category_id
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */
        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (
            HomeVisitServiceCategory::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        /*
        |--------------------------------------------------------------------------
        | Automatically get category_type from parent category
        |--------------------------------------------------------------------------
        */
        $categoryType = $homeVisitCategory->slug;

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */
        $imagePath = $category->image;

        if ($request->hasFile('image')) {

            if (
                $category->image &&
                Storage::disk('public')->exists($category->image)
            ) {
                Storage::disk('public')->delete(
                    $category->image
                );
            }

            $imagePath = $request->file('image')->store(
                'home_visit_service_categories',
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        $category->update([
            'home_visit_category_id' => $homeVisitCategory->id,
            'name' => $request->name,
            'slug' => $slug,
            'category_type' => $categoryType,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.home-visit-service-categories.index')
            ->with(
                'success',
                'Home Visit Service Category updated successfully.'
            );
    }

    /**
     * Delete Home Visit Service Category.
     */
    public function destroy($id)
    {
        $category = HomeVisitServiceCategory::findOrFail($id);

        // Delete image
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.home-visit-service-categories.index')
            ->with('success', 'Home Visit Service Category deleted successfully.');
    }
}
