<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specializations = Specialization::latest()->get();

        return view('admin.specializations.index', compact('specializations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'specialization_name' => 'required|string|max:255|unique:specializations,specialization_name',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $specialization = new Specialization();

        $specialization->specialization_name = $request->specialization_name;
        $specialization->slug = Str::slug($request->specialization_name);
        $specialization->description = $request->description;
        $specialization->status = $request->status;

        if ($request->hasFile('icon')) {

            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();

            $icon->move(public_path('uploads/specializations/icons'), $iconName);

            $specialization->icon = 'uploads/specializations/icons/' . $iconName;
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/specializations/images'), $imageName);

            $specialization->image = 'uploads/specializations/images/' . $imageName;
        }

        $specialization->save();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialization = Specialization::findOrFail($id);

        return view('admin.specializations.show', compact('specialization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specialization = Specialization::findOrFail($id);

        return view('admin.specializations.edit', compact('specialization'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $specialization = Specialization::findOrFail($id);

        $request->validate([
            'specialization_name' => 'required|string|max:255|unique:specializations,specialization_name,' . $specialization->id,
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $specialization->specialization_name = $request->specialization_name;
        $specialization->slug = Str::slug($request->specialization_name);
        $specialization->description = $request->description;
        $specialization->status = $request->status;

        if ($request->hasFile('icon')) {

            if ($specialization->icon && File::exists(public_path($specialization->icon))) {
                File::delete(public_path($specialization->icon));
            }

            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();

            $icon->move(public_path('uploads/specializations/icons'), $iconName);

            $specialization->icon = 'uploads/specializations/icons/' . $iconName;
        }

        if ($request->hasFile('image')) {

            if ($specialization->image && File::exists(public_path($specialization->image))) {
                File::delete(public_path($specialization->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/specializations/images'), $imageName);

            $specialization->image = 'uploads/specializations/images/' . $imageName;
        }

        $specialization->save();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization updated successfully.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $specialization = Specialization::findOrFail($id);

        if ($specialization->icon && File::exists(public_path($specialization->icon))) {
            File::delete(public_path($specialization->icon));
        }

        if ($specialization->image && File::exists(public_path($specialization->image))) {
            File::delete(public_path($specialization->image));
        }

        $specialization->delete();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization deleted successfully.');
    }

    /**
     * Change Status
     */
    public function status($id)
    {
        $specialization = Specialization::findOrFail($id);

        $specialization->status = !$specialization->status;

        $specialization->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}