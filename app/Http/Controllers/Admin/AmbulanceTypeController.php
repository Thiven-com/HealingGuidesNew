<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AmbulanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ambulanceTypes = AmbulanceType::latest()->get();

        return view(
            'admin.ambulance-types.index',
            compact('ambulanceTypes')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ambulance-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'ambulance_type_name' => 'required|string|max:255|unique:ambulance_types,ambulance_type_name',

            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required|boolean',

        ]);

        // Generate Ambulance Type Code

        $lastType = AmbulanceType::latest()->first();

        if ($lastType && $lastType->ambulance_type_code) {

            $number = (int) substr($lastType->ambulance_type_code, 2);

            $number++;

        } else {

            $number = 1;
        }

        $ambulanceTypeCode = 'AT' . str_pad($number, 5, '0', STR_PAD_LEFT);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->ambulance_type_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/ambulance-types');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/ambulance-types/' . $imageName;
        }

        AmbulanceType::create([

            'ambulance_type_name' => $request->ambulance_type_name,

            'ambulance_type_code' => $ambulanceTypeCode,

            'slug' => Str::slug($request->ambulance_type_name),

            'description' => $request->description,

            'image' => $imagePath,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('admin.ambulance-types.index')
            ->with('success', 'Ambulance Type Created Successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ambulanceType = AmbulanceType::findOrFail($id);

        return view(
            'admin.ambulance-types.show',
            compact('ambulanceType')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ambulanceType = AmbulanceType::findOrFail($id);

        return view(
            'admin.ambulance-types.edit',
            compact('ambulanceType')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ambulanceType = AmbulanceType::findOrFail($id);

        $request->validate([

            'ambulance_type_name' => 'required|string|max:255|unique:ambulance_types,ambulance_type_name,' . $ambulanceType->id,

            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status' => 'required|boolean',

        ]);

        $imagePath = $ambulanceType->image;

        if ($request->hasFile('image')) {

            if ($ambulanceType->image && File::exists(public_path($ambulanceType->image))) {
                File::delete(public_path($ambulanceType->image));
            }

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->ambulance_type_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/ambulance-types');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/ambulance-types/' . $imageName;
        }

        $ambulanceType->update([

            'ambulance_type_name' => $request->ambulance_type_name,

            'slug' => Str::slug($request->ambulance_type_name),

            'description' => $request->description,

            'image' => $imagePath,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('admin.ambulance-types.index')
            ->with('success', 'Ambulance Type Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ambulanceType = AmbulanceType::findOrFail($id);

        if ($ambulanceType->image && File::exists(public_path($ambulanceType->image))) {
            File::delete(public_path($ambulanceType->image));
        }

        $ambulanceType->delete();

        return redirect()
            ->route('admin.ambulance-types.index')
            ->with('success', 'Ambulance Type Deleted Successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function status(string $id)
    {
        $ambulanceType = AmbulanceType::findOrFail($id);

        $ambulanceType->status = $ambulanceType->status ? 0 : 1;

        $ambulanceType->save();

        return redirect()
            ->route('admin.ambulance-types.index')
            ->with('success', 'Ambulance Type Status Updated Successfully.');
    }
}