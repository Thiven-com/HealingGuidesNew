<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecializationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SpecializationCategoryController extends Controller
{
    /**
     * Display listing.
     */
    public function index()
    {
        $categories = SpecializationCategory::latest()->paginate(10);

        return view('admin.specialization-categories.index', compact('categories'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.specialization-categories.create');
    }

    /**
     * Store category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:specialization_categories,slug',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // 'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $category = new SpecializationCategory();

        $category->category_name = $request->category_name;

        $category->slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->category_name);

        // $category->description = $request->description;
        $category->status = $request->has('status') ? 1 : 0;

        // if ($request->hasFile('image')) {
        //     $category->image = $request->file('image')->store(
        //         'specialization_categories',
        //         'public'
        //     );
        // }

        $category->save();

        return redirect()
            ->route('admin.specialization-categories.index')
            ->with('success', 'Specialization category added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $category = SpecializationCategory::findOrFail($id);

        return view(
            'admin.specialization-categories.edit',
            compact('category')
        );
    }

    /**
     * Update category.
     */
    public function update(Request $request, $id)
    {
        $category = SpecializationCategory::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:specialization_categories,slug,' . $id,
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // 'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $category->category_name = $request->category_name;

        $category->slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->category_name);

        // $category->description = $request->description;
        $category->status = $request->has('status') ? 1 : 0;

        // if ($request->hasFile('image')) {

        //     // Delete old image
        //     if ($category->image && Storage::disk('public')->exists($category->image)) {
        //         Storage::disk('public')->delete($category->image);
        //     }

        //     $category->image = $request->file('image')->store(
        //         'specialization_categories',
        //         'public'
        //     );
        // }

        $category->save();

        return redirect()
            ->route('admin.specialization-categories.index')
            ->with('success', 'Specialization category updated successfully.');
    }

    /**
     * Delete category.
     */
    public function destroy($id)
    {
        $category = SpecializationCategory::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.specialization-categories.index')
            ->with('success', 'Specialization category deleted successfully.');
    }
}