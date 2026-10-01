<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surgery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurgeryController extends Controller
{
    /**
     * Display surgeries.
     */
    public function index(Request $request)
    {
        $query = Surgery::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere(
                        'short_description',
                        'like',
                        '%' . $search . '%'
                    );

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

        /*
        |--------------------------------------------------------------------------
        | Get Surgeries
        |--------------------------------------------------------------------------
        */

        $surgeries = $query
            ->orderBy('display_order')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.surgeries.index',
            compact('surgeries')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.surgeries.create'
        );
    }


    /**
     * Store surgery.
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:surgeries,slug',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'short_description' =>
                'nullable|string|max:500',

            'description' =>
                'nullable|string',

            'duration' =>
                'nullable|string|max:255',

            'recovery_time' =>
                'nullable|string|max:255',

            'preparation_instructions' =>
                'nullable|string',

            'post_surgery_care' =>
                'nullable|string',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        /*
        |--------------------------------------------------------------------------
        | Make Unique Slug
        |--------------------------------------------------------------------------
        */

        $originalSlug = $slug;

        $counter = 1;

        while (Surgery::where('slug', $slug)->exists()) {

            $slug = $originalSlug . '-' . $counter;

            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        $image = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/surgeries'),
                $filename
            );

            $image =
                'uploads/surgeries/' .
                $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Surgery::create([

            'name' =>
                $request->name,

            'slug' =>
                $slug,

            'image' =>
                $image,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'duration' =>
                $request->duration,

            'recovery_time' =>
                $request->recovery_time,

            'preparation_instructions' =>
                $request->preparation_instructions,

            'post_surgery_care' =>
                $request->post_surgery_care,

            'display_order' =>
                $request->display_order ?? 0,

            'status' =>
                $request->has('status')
                    ? 1
                    : 0,
        ]);

        return redirect()
            ->route('admin.surgeries.index')
            ->with(
                'success',
                'Surgery added successfully.'
            );
    }


    /**
     * Show surgery.
     */
    public function show($id)
    {
        $surgery = Surgery::findOrFail($id);

        return view(
            'admin.surgeries.show',
            compact('surgery')
        );
    }


    /**
     * Edit surgery.
     */
    public function edit($id)
    {
        $surgery = Surgery::findOrFail($id);

        return view(
            'admin.surgeries.edit',
            compact('surgery')
        );
    }


    /**
     * Update surgery.
     */
    public function update(
        Request $request,
        $id
    ) {
        $surgery = Surgery::findOrFail($id);

        $request->validate([

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:surgeries,slug,' .
                $surgery->id,

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'short_description' =>
                'nullable|string|max:500',

            'description' =>
                'nullable|string',

            'duration' =>
                'nullable|string|max:255',

            'recovery_time' =>
                'nullable|string|max:255',

            'preparation_instructions' =>
                'nullable|string',

            'post_surgery_care' =>
                'nullable|string',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->slug
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $originalSlug = $slug;

        $counter = 1;

        while (
            Surgery::where('slug', $slug)
                ->where('id', '!=', $surgery->id)
                ->exists()
        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter;

            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        $image = $surgery->image;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/surgeries'),
                $filename
            );

            $image =
                'uploads/surgeries/' .
                $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $surgery->update([

            'name' =>
                $request->name,

            'slug' =>
                $slug,

            'image' =>
                $image,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'duration' =>
                $request->duration,

            'recovery_time' =>
                $request->recovery_time,

            'preparation_instructions' =>
                $request->preparation_instructions,

            'post_surgery_care' =>
                $request->post_surgery_care,

            'display_order' =>
                $request->display_order ?? 0,

            'status' =>
                $request->has('status')
                    ? 1
                    : 0,
        ]);

        return redirect()
            ->route('admin.surgeries.index')
            ->with(
                'success',
                'Surgery updated successfully.'
            );
    }


    /**
     * Delete surgery.
     */
    public function destroy($id)
    {
        $surgery = Surgery::findOrFail($id);

        $surgery->delete();

        return redirect()
            ->route('admin.surgeries.index')
            ->with(
                'success',
                'Surgery deleted successfully.'
            );
    }


    /**
     * Toggle status.
     */
    public function status($id)
    {
        $surgery = Surgery::findOrFail($id);

        $surgery->status =
            !$surgery->status;

        $surgery->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Surgery status updated successfully.'
            );
    }
}