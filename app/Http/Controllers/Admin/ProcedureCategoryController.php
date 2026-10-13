<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProcedureCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProcedureCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procedureCategories = ProcedureCategory::latest()->paginate(10);

        return view(
            'admin.procedure-categories.index',
            compact('procedureCategories')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.procedure-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $counter = 1;

        while (ProcedureCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $category = new ProcedureCategory();
        $category->name = $validated['name'];
        $category->slug = $slug;
        $category->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            $category->image = $request->file('image')
                ->store('procedure-categories', 'public');
        }

        $category->save();

        return redirect()
            ->route('admin.procedure-categories.index')
            ->with('success', 'Procedure category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $procedureCategory = ProcedureCategory::findOrFail($id);

        return view(
            'admin.procedure-categories.edit',
            compact('procedureCategory')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        ProcedureCategory $procedureCategory
    ) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['name']);

        $originalSlug = $slug;
        $counter = 1;

        while (
            ProcedureCategory::where('slug', $slug)
                ->where('id', '!=', $procedureCategory->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $procedureCategory->name = $validated['name'];
        $procedureCategory->slug = $slug;
        $procedureCategory->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            if ($procedureCategory->image) {
                Storage::disk('public')->delete($procedureCategory->image);
            }

            $procedureCategory->image = $request->file('image')
                ->store('procedure-categories', 'public');
        }

        $procedureCategory->save();

        return redirect()
            ->route('admin.procedure-categories.index')
            ->with('success', 'Procedure category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProcedureCategory $procedureCategory)
    {
        if ($procedureCategory->image) {
            Storage::disk('public')->delete($procedureCategory->image);
        }

        $procedureCategory->delete();

        return redirect()
            ->route('admin.procedure-categories.index')
            ->with('success', 'Procedure category deleted successfully.');
    }
}
