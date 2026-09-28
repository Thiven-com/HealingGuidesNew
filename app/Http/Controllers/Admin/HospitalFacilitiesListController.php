<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HospitalFacilitiesList;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HospitalFacilitiesListController extends Controller
{/**
 * Display all master hospital facilities.
 */
    public function index()
    {
        $hospitalFacilities = HospitalFacilitiesList::latest()->paginate(10);

        return view(
            'admin.hospitals.facility-details',
            compact('hospitalFacilities')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.hospital-facilities-list.create');
    }

    /**
     * Store hospital facility.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
            // Current HospitalFacility record.
            'hospital_facility_id' => 'nullable|exists:hospital_facilities,id',
            'hospital_id' => 'nullable',
        ]);

        $hospitalFacilityList = new HospitalFacilitiesList();

        $hospitalFacilityList->title = $validated['title'];
        $hospitalFacilityList->description = $validated['description'] ?? null;
        $hospitalFacilityList->hospital_id = $validated['hospital_id'] ?? null;
        $hospitalFacilityList->hospital_facilities_id = $validated['hospital_facility_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {
            $hospitalFacilityList->image = $request->file('image')->store(
                'hospital_facilities',
                'public'
            );
        }

        $hospitalFacilityList->save();

        /*
        |--------------------------------------------------------------------------
        | Return to current Hospital Facility Details page
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['hospital_facility_id'])) {
            return redirect()->route(
                'admin.hospitals.facility.details',
                [
                    'facility' => $validated['hospital_facility_id']
                ]
            )->with('success', 'Hospital facility added successfully.');
        }

        /*
        |--------------------------------------------------------------------------
        | If no hospital facility was supplied, go to master list
        |--------------------------------------------------------------------------
        */
        return redirect()->route(
            'admin.hospitals.facility.details',
            [
                'facility' => $validated['hospital_facility_id']
            ]
        )->with('success', 'Hospital facility added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $hospitalFacility = HospitalFacilitiesList::findOrFail($id);

        return view(
            'admin.hospital-facilities-list.edit',
            compact('hospitalFacility')
        );
    }

    /**
     * Update hospital facility.
     */
    public function update(Request $request, $id)
    {
        $hospitalFacility = HospitalFacilitiesList::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'description' => 'nullable|string',
        ]);

        $hospitalFacility->title = $validated['title'];
        $hospitalFacility->description = $validated['description'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Replace Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('image')) {

            if (
                !empty($hospitalFacility->image) &&
                Storage::disk('public')->exists($hospitalFacility->image)
            ) {
                Storage::disk('public')->delete($hospitalFacility->image);
            }

            $hospitalFacility->image = $request->file('image')->store(
                'hospital_facilities',
                'public'
            );
        }

        $hospitalFacility->save();

        return redirect()
            ->back()
            ->with('success', 'Hospital facility updated successfully.');
    }

    /**
     * Delete hospital facility.
     */
    public function destroy($id)
    {
        $hospitalFacility = HospitalFacilitiesList::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */
        if (
            !empty($hospitalFacility->image) &&
            Storage::disk('public')->exists($hospitalFacility->image)
        ) {
            Storage::disk('public')->delete($hospitalFacility->image);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */
        $hospitalFacility->delete();

        return redirect()
            ->back()
            ->with('success', 'Hospital facility deleted successfully.');
    }
}
