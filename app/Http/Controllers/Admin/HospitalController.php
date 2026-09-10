<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\HospitalSpecialization;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class HospitalController extends Controller
{

    public function index(Request $request)
    {
        $query = Hospital::query();

        // Hospital Name
        if ($request->filled('hospital_name')) {
            $query->where('hospital_name', 'like', '%' . trim($request->hospital_name) . '%');
        }

        // Hospital Code
        if ($request->filled('hospital_code')) {
            $query->where('hospital_code', 'like', '%' . trim($request->hospital_code) . '%');
        }

        // Hospital Type
        if ($request->filled('hospital_type')) {
            $query->where('hospital_type', $request->hospital_type);
        }

        // Mobile
        if ($request->filled('mobile')) {
            $query->where('mobile', 'like', '%' . trim($request->mobile) . '%');
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hospitals = $query->latest('id')->get();

        // Get hospital types for dropdown
        $hospitalTypes = Hospital::whereNotNull('hospital_type')
            ->where('hospital_type', '!=', '')
            ->distinct()
            ->orderBy('hospital_type')
            ->pluck('hospital_type');

        return view('admin.hospitals.index', compact(
            'hospitals',
            'hospitalTypes'
        ));
    }

    public function create()
    {
        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        return view('admin.hospitals.create', compact('specializations'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'hospital_name' => 'required',
            'hospital_type' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specializations,id',
        ]);

        $data = $request->all();

        // Generate slug
        $slug = Str::slug($request->hospital_name);

        // Make slug unique
        $count = Hospital::where('hospital_slug', 'LIKE', "{$slug}%")->count();
        $data['hospital_slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;

        // Generate Hospital Code
        $lastHospital = Hospital::latest('id')->first();
        $nextId = $lastHospital ? $lastHospital->id + 1 : 1;
        $firstWord = explode(' ', trim($request->hospital_name))[0];
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $firstWord), 0, 3));
        $data['hospital_code'] = $prefix . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        if ($request->hasFile('logo')) {

            $logo = $request->file('logo');
            $logoName = $data['hospital_slug'] . '.' . $logo->getClientOriginalExtension();

            $data['logo'] = $logo->storeAs(
                'hospital/logo',
                $logoName,
                'public'
            );
        }

        if ($request->hasFile('banner')) {

            $banner = $request->file('banner');
            $bannerName = $data['hospital_slug'] . '.' . $banner->getClientOriginalExtension();

            $data['banner'] = $banner->storeAs(
                'hospital/banner',
                $bannerName,
                'public'
            );
        }

        $hospital = Hospital::create($data);

        // Save Hospital Specializations
        foreach ($request->specializations as $specializationId) {

            HospitalSpecialization::create([
                'hospital_id' => $hospital->id,
                'specialization_id' => $specializationId,
                'status' => 1,
            ]);

        }

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital Created Successfully');
    }

    public function show(Hospital $hospital)
    {
        $hospital->load('hospitalSpecializations.specialization');

        return view('admin.hospitals.show', compact('hospital'));
    }


    public function edit(Hospital $hospital)
    {
        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        $hospital->load('hospitalSpecializations');

        return view(
            'admin.hospitals.edit',
            compact('hospital', 'specializations')
        );
    }

    public function update(Request $request, Hospital $hospital)
    {

        $request->validate([
            'hospital_name' => 'required',
            'hospital_type' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specializations,id',
        ]);

        $data = $request->all();

        $slug = $hospital->hospital_slug;

        // Upload logo
        if ($request->hasFile('logo')) {

            // Delete old logo
            if ($hospital->logo && Storage::disk('public')->exists($hospital->logo)) {
                Storage::disk('public')->delete($hospital->logo);
            }

            $extension = $request->file('logo')->getClientOriginalExtension();

            $data['logo'] = $request->file('logo')->storeAs(
                'hospital/logo',
                $slug . '.' . $extension,
                'public'
            );
        }
        // Upload banner
        if ($request->hasFile('banner')) {

            // Delete old banner
            if ($hospital->banner && Storage::disk('public')->exists($hospital->banner)) {
                Storage::disk('public')->delete($hospital->banner);
            }

            $extension = $request->file('banner')->getClientOriginalExtension();

            $data['banner'] = $request->file('banner')->storeAs(
                'hospital/banner',
                $slug . '.' . $extension,
                'public'
            );
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')
                ->store('hospital/banner', 'public');
        }

        $hospital->update($data);

        $existingIds = HospitalSpecialization::where('hospital_id', $hospital->id)
            ->pluck('specialization_id')
            ->toArray();

        $newIds = $request->specializations ?? [];

        // Insert only new specializations
        foreach (array_diff($newIds, $existingIds) as $specializationId) {

            HospitalSpecialization::create([
                'hospital_id' => $hospital->id,
                'specialization_id' => $specializationId,
                'status' => 1,
            ]);
        }

        // Delete only unchecked specializations
        HospitalSpecialization::where('hospital_id', $hospital->id)
            ->whereIn('specialization_id', array_diff($existingIds, $newIds))
            ->delete();

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital Updated Successfully');

    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();

        return back()->with('success', 'Hospital Deleted Successfully');
    }

    public function status(Hospital $hospital)
    {
        $hospital->status = !$hospital->status;

        $hospital->save();

        return back()->with('success', 'Status Updated');
    }

}