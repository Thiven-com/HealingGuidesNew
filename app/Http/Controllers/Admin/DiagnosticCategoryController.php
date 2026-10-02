<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiagnosticCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = DiagnosticCategory::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $categories = $query
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.diagnostic_categories.index',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.diagnostic_categories.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' =>
                'required|string|max:255|unique:diagnostic_categories,name',

            'slug' =>
                'nullable|string|max:255|unique:diagnostic_categories,slug',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|boolean',
        ]);

        $data = $request->only([

            'name',
            'short_description',
            'description',
            'display_order',
            'status',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $data['slug'] = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName =
                time() . '_' .
                Str::slug($request->name) . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('uploads/diagnostic_categories'),
                $imageName
            );

            $data['image'] =
                'uploads/diagnostic_categories/' . $imageName;
        }

        DiagnosticCategory::create($data);

        return redirect()
            ->route('admin.diagnostic-categories.index')
            ->with(
                'success',
                'Diagnostic category created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $category = DiagnosticCategory::with([
            'diagnostics'
        ])->findOrFail($id);

        return view(
            'admin.diagnostic_categories.show',
            compact('category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $category =
            DiagnosticCategory::findOrFail($id);

        return view(
            'admin.diagnostic_categories.edit',
            compact('category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $category =
            DiagnosticCategory::findOrFail($id);

        $request->validate([

            'name' =>
                'required|string|max:255|unique:diagnostic_categories,name,' .
                $category->id,

            'slug' =>
                'nullable|string|max:255|unique:diagnostic_categories,slug,' .
                $category->id,

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'short_description' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|boolean',
        ]);

        $data = $request->only([

            'name',
            'short_description',
            'description',
            'display_order',
            'status',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $data['slug'] = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            | Delete old image
            */

            if (
                $category->image &&
                file_exists(
                    public_path($category->image)
                )
            ) {
                unlink(
                    public_path($category->image)
                );
            }

            $image = $request->file('image');

            $imageName =
                time() . '_' .
                Str::slug($request->name) . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('uploads/diagnostic_categories'),
                $imageName
            );

            $data['image'] =
                'uploads/diagnostic_categories/' . $imageName;
        }

        $category->update($data);

        return redirect()
            ->route('admin.diagnostic-categories.index')
            ->with(
                'success',
                'Diagnostic category updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $category =
            DiagnosticCategory::findOrFail($id);

        $category->update([
            'status' => !$category->status,
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Diagnostic category status updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $category =
            DiagnosticCategory::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent Delete If Diagnostics Exist
        |--------------------------------------------------------------------------
        */

        if ($category->diagnostics()->exists()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This category cannot be deleted because diagnostics are associated with it.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            $category->image &&
            file_exists(
                public_path($category->image)
            )
        ) {
            unlink(
                public_path($category->image)
            );
        }

        $category->delete();

        return redirect()
            ->route(
                'admin.diagnostic-categories.index'
            )
            ->with(
                'success',
                'Diagnostic category deleted successfully.'
            );
    }
}