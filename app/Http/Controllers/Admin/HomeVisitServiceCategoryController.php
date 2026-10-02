<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('admin.home_visit_service_categories.index', compact('categories'));
    }

    /**
     * Store a new Home Visit Service Category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:home_visit_service_categories,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_type' => [
                'required',
                'in:care_service,physio,sleep_test',
            ],
        ]);

        $category = new HomeVisitServiceCategory();

        $category->name = $request->name;
        $category->category_type = $request->category_type;

        $category->slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store(
                'home_visit_service_categories',
                'public'
            );
        }

        $category->save();

        return redirect()
            ->route('admin.home-visit-service-categories.index')
            ->with('success', 'Home Visit Service Category added successfully.');
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:home_visit_service_categories,slug,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category_type' => [
                'required',
                'in:care_service,physio,sleep_test',
            ],
        ]);

        $category->name = $request->name;
        $category->category_type = $request->category_type;
        $category->slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        if ($request->hasFile('image')) {

            // Delete old image
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Upload new image
            $category->image = $request->file('image')->store(
                'home_visit_service_categories',
                'public'
            );
        }

        $category->save();

        return redirect()
            ->route('admin.home-visit-service-categories.index')
            ->with('success', 'Home Visit Service Category updated successfully.');
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
