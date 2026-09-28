<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacilityController extends Controller
{
    /**
     * Display facilities
     */
    public function index()
    {
        $facilities = Facility::latest()->paginate(10);

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.facilities.create');
    }

    /**
     * Store facility
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (Facility::where('slug', $slug)->exists()) {

            $slug = $originalSlug . '-' . $count;

            $count++;
        }

        $data['slug'] = $slug;

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $extension = strtolower(
                $request->file('image')->getClientOriginalExtension()
            );

            $imageName = $slug . '.' . $extension;

            $imagePath = $request->file('image')->storeAs(
                'facilities',
                $imageName,
                'public'
            );

            $data['image'] = $imagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Facility
        |--------------------------------------------------------------------------
        */

        Facility::create($data);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility Created Successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update facility
     */
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (
            Facility::where('slug', $slug)
                ->where('id', '!=', $facility->id)
                ->exists()
        ) {

            $slug = $originalSlug . '-' . $count;

            $count++;
        }

        $data['slug'] = $slug;

        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            | Delete old image
            */

            if (
                !empty($facility->image) &&
                Storage::disk('public')->exists($facility->image)
            ) {

                Storage::disk('public')->delete(
                    $facility->image
                );
            }

            /*
            | Get extension
            */

            $extension = strtolower(
                $request->file('image')->getClientOriginalExtension()
            );

            /*
            | Image name based on slug
            */

            $imageName = $slug . '.' . $extension;

            /*
            | Store image
            */

            $imagePath = $request->file('image')->storeAs(
                'facilities',
                $imageName,
                'public'
            );

            $data['image'] = $imagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Facility
        |--------------------------------------------------------------------------
        */

        $facility->update($data);

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility Updated Successfully');
    }

    /**
     * Delete facility
     */
    public function destroy(Facility $facility)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            !empty($facility->image) &&
            Storage::disk('public')->exists($facility->image)
        ) {

            Storage::disk('public')->delete(
                $facility->image
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Facility
        |--------------------------------------------------------------------------
        */

        $facility->delete();

        return redirect()
            ->route('admin.facilities.index')
            ->with('success', 'Facility Deleted Successfully');
    }
}