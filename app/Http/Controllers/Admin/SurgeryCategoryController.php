<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurgeryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SurgeryCategoryController extends Controller
{
    public function index()
    {
        $categories = SurgeryCategory::latest()->paginate(10);

        return view('admin.surgery-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug($validated['name']);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('surgery-categories', 'public');
        }

        SurgeryCategory::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.surgery-categories.index')
            ->with('success', 'Surgery category created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $category = SurgeryCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug(
            $validated['name'],
            $category->id
        );

        $imagePath = $category->image;

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')
                ->store('surgery-categories', 'public');
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('admin.surgery-categories.index')
            ->with('success', 'Surgery category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = SurgeryCategory::findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.surgery-categories.index')
            ->with('success', 'Surgery category deleted successfully.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug ?: 'surgery-category';

        $slug = $baseSlug;
        $counter = 1;

        while (
            SurgeryCategory::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
