<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class HospitalController extends Controller
{

    public function index()
    {
        $hospitals = Hospital::latest()->paginate(10);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospitals.create');
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
            'pincode' => 'required'
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
        $data['hospital_code'] = 'HSP' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

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

        Hospital::create($data);

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital Created Successfully');
    }

    public function show(Hospital $hospital)
    {
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('admin.hospitals.edit', compact('hospital'));
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
            'pincode' => 'required'
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