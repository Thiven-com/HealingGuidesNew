<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MedicineCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $categories = MedicineCategory::query();

        if ($request->filled('search')) {

            $search = trim($request->search);

            $categories->where(function ($query) use ($search) {

                $query->where(
                    'category_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'description',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        if ($request->filled('status')) {
            $categories->where(
                'status',
                $request->status
            );
        }

        $categories = $categories
            ->orderBy('sort_order')
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.medicine_categories.index',
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
            'admin.medicine_categories.create'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'category_name' =>
                'required|string|max:255|unique:medicine_categories,category_name',

            'description' =>
                'nullable|string',

            'sort_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|in:0,1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/medicine-categories');

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $imagePath =
                'uploads/medicine-categories/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug(
            $request->category_name
        );

        $originalSlug = $slug;

        $counter = 1;

        while (
            MedicineCategory::where('slug', $slug)->exists()
        ) {
            $slug =
                $originalSlug .
                '-' .
                $counter++;

        }

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        MedicineCategory::create([

            'category_name' =>
                trim($request->category_name),

            'slug' =>
                $slug,

            'image' =>
                $imagePath,

            'description' =>
                $request->description,

            'sort_order' =>
                $request->sort_order ?? 0,

            'status' =>
                $request->status,
        ]);

        return redirect()
            ->route('admin.medicine-categories.index')
            ->with(
                'success',
                'Medicine Category Created Successfully'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $category = MedicineCategory::findOrFail($id);

        return view(
            'admin.medicine_categories.edit',
            compact('category')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $category = MedicineCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [

            'category_name' =>
                'required|string|max:255|unique:medicine_categories,category_name,' .
                $category->id,

            'description' =>
                'nullable|string',

            'sort_order' =>
                'nullable|integer|min:0',

            'status' =>
                'required|in:0,1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Category Name / Slug
        |--------------------------------------------------------------------------
        */

        if (
            trim($category->category_name) !==
            trim($request->category_name)
        ) {

            $slug = Str::slug(
                $request->category_name
            );

            $originalSlug = $slug;

            $counter = 1;

            while (
                MedicineCategory::where('slug', $slug)
                    ->where('id', '!=', $category->id)
                    ->exists()
            ) {

                $slug =
                    $originalSlug .
                    '-' .
                    $counter++;
            }

            $category->slug = $slug;
        }

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (
                !empty($category->image) &&
                file_exists(public_path($category->image))
            ) {
                @unlink(
                    public_path($category->image)
                );
            }

            $file = $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/medicine-categories');

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $category->image =
                'uploads/medicine-categories/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $category->category_name =
            trim($request->category_name);

        $category->description =
            $request->description;

        $category->sort_order =
            $request->sort_order ?? 0;

        $category->status =
            $request->status;

        $category->save();

        return redirect()
            ->route('admin.medicine-categories.index')
            ->with(
                'success',
                'Medicine Category Updated Successfully'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $category = MedicineCategory::findOrFail($id);

        $category->status =
            $category->status ? 0 : 1;

        $category->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Medicine Category Status Updated Successfully'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $category = MedicineCategory::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Don't Delete If Medicines Exist
        |--------------------------------------------------------------------------
        */

        if ($category->medicines()->exists()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Cannot delete this category because medicines are assigned to it.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            !empty($category->image) &&
            file_exists(public_path($category->image))
        ) {

            @unlink(
                public_path($category->image)
            );
        }

        $category->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Medicine Category Deleted Successfully'
            );
    }
}