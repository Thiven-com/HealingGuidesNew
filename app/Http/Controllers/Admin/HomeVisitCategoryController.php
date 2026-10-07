<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeVisitCategoryController extends Controller
{
    /**
     * Display home visit categories
     */
    public function index()
    {
        $categories = HomeVisitCategory::latest()->paginate(20);

        return view('admin.home-visit-categories.index', compact('categories'));
    }

    /**
     * Store new home visit category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);

        // Make slug unique
        $originalSlug = $slug;
        $count = 1;

        while (HomeVisitCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('home_visit_categories', 'public');
        }

        HomeVisitCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.home-visit-categories.index')
            ->with('success', 'Home visit category added successfully.');
    }

    /**
     * Update home visit category
     */
    public function update(Request $request, $id)
    {
        $category = HomeVisitCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);

        // Make slug unique except current category
        $originalSlug = $slug;
        $count = 1;

        while (
            HomeVisitCategory::where('slug', $slug)
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $imagePath = $category->image;

        if ($request->hasFile('image')) {

            // Delete old image
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Upload new image
            $imagePath = $request->file('image')
                ->store('home_visit_categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.home-visit-categories.index')
            ->with('success', 'Home visit category updated successfully.');
    }

    /**
     * Delete home visit category
     */
    public function destroy($id)
    {
        $category = HomeVisitCategory::findOrFail($id);

        // Delete image
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.home-visit-categories.index')
            ->with('success', 'Home visit category deleted successfully.');
    }
}
