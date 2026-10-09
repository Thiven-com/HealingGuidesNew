<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyConnect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmergencyConnectController extends Controller
{
    /**
     * Display listing.
     */
    public function index(Request $request)
    {
        $query = EmergencyConnect::query();

        // Title search
        if ($request->filled('title')) {
            $query->where(
                'title',
                'like',
                '%' . trim($request->title) . '%'
            );
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $emergencyConnects = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.emergencyconnect.index',
            compact('emergencyConnects')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.emergencyconnect.create');
    }


    /**
     * Store new Emergency Connect.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:emergency_connects,slug',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->title);


        /*
        |--------------------------------------------------------------------------
        | Make Slug Unique
        |--------------------------------------------------------------------------
        */

        $originalSlug = $slug;
        $counter = 1;

        while (
            EmergencyConnect::where('slug', $slug)->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store('emergency-connect', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        EmergencyConnect::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'status' => $request->has('status')
                ? $request->boolean('status')
                : true,
        ]);


        return redirect()
            ->route('admin.emergencyconnect.index')
            ->with(
                'success',
                'Emergency Connect created successfully.'
            );
    }


    /**
     * Display single record.
     */
    public function show($id)
    {
        $emergencyConnect = EmergencyConnect::findOrFail($id);

        return view(
            'admin.emergencyconnect.show',
            compact('emergencyConnect')
        );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $emergencyConnect = EmergencyConnect::findOrFail($id);

        return view(
            'admin.emergencyconnect.edit',
            compact('emergencyConnect')
        );
    }


    /**
     * Update Emergency Connect.
     */
    public function update(Request $request, $id)
    {
        $emergencyConnect = EmergencyConnect::findOrFail($id);


        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:emergency_connects,slug,' . $id,
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->title);


        /*
        |--------------------------------------------------------------------------
        | Make Slug Unique
        |--------------------------------------------------------------------------
        */

        $originalSlug = $slug;
        $counter = 1;

        while (
            EmergencyConnect::where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }


        /*
        |--------------------------------------------------------------------------
        | Image Upload
        |--------------------------------------------------------------------------
        */

        $imagePath = $emergencyConnect->image;

        if ($request->hasFile('image')) {

            // Delete old image
            if (
                $emergencyConnect->image &&
                Storage::disk('public')->exists(
                    $emergencyConnect->image
                )
            ) {
                Storage::disk('public')->delete(
                    $emergencyConnect->image
                );
            }


            // Upload new image
            $imagePath = $request
                ->file('image')
                ->store('emergency-connect', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $emergencyConnect->update([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imagePath,
            'status' => $request->has('status')
                ? $request->boolean('status')
                : false,
        ]);


        return redirect()
            ->route('admin.emergencyconnect.index')
            ->with(
                'success',
                'Emergency Connect updated successfully.'
            );
    }


    /**
     * Delete Emergency Connect.
     */
    public function destroy($id)
    {
        $emergencyConnect = EmergencyConnect::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            $emergencyConnect->image &&
            Storage::disk('public')->exists(
                $emergencyConnect->image
            )
        ) {
            Storage::disk('public')->delete(
                $emergencyConnect->image
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Record
        |--------------------------------------------------------------------------
        */

        $emergencyConnect->delete();


        return redirect()
            ->route('admin.emergencyconnect.index')
            ->with(
                'success',
                'Emergency Connect deleted successfully.'
            );
    }


    /**
     * Toggle status.
     */
    public function toggleStatus($id)
    {
        $emergencyConnect = EmergencyConnect::findOrFail($id);

        $emergencyConnect->status =
            !$emergencyConnect->status;

        $emergencyConnect->save();


        return redirect()
            ->back()
            ->with(
                'success',
                'Emergency Connect status updated successfully.'
            );
    }
}
