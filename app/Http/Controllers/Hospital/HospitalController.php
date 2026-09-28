<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hospital;
use App\Models\HospitalFacility;
use App\Models\HospitalGallery;
use App\Models\HospitalSpecialization;
use App\Models\HospitalTieup;
use App\Models\Specialization;
use App\Models\Tieup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HospitalController extends Controller
{
    public function details()
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Get Hospital
        |--------------------------------------------------------------------------
        |
        | Change hospital_id below if your hospital login table stores
        | the hospital relationship using another column.
        |
        */

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        $hospital = Hospital::with([
            'hospitalSpecializations.specialization',
            'facilities.facility',
        ])->findOrFail($hospitalId);


        /*
        |--------------------------------------------------------------------------
        | Hospital Tieups
        |--------------------------------------------------------------------------
        */

        $hospitalTieups = HospitalTieup::with('tieup')
            ->where('hospital', $hospital->id)
            ->get();


        return view(
            'hospital.hospitalprofile.index',
            compact(
                'hospital',
                'hospitalTieups'
            )
        );
    }


    /**
     * Facility Details
     */
    public function facilityDetails($facility)
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        $facility = HospitalFacility::with([
            'facility',
            'hospital',
        ])
            ->where('hospital_id', $hospitalId)
            ->where('id', $facility)
            ->firstOrFail();

        $galleries = HospitalGallery::where('hospital_id', $hospitalId)
            ->where('facility_id', $facility->facility_id)
            ->latest()
            ->get();

        return view('hospital.hospitalprofile.facility-details', compact(
            'facility',
            'galleries'
        ));
    }


    /**
     * Tieup Details
     */
    public function tieupDetails($tieup)
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        // Get hospital
        $hospital = Hospital::findOrFail($hospitalId);

        // Get assigned tieup for this hospital
        $hospitalTieup = HospitalTieup::with('tieup')
            ->where('hospital', $hospitalId)
            ->where('tieup_id', $tieup)
            ->firstOrFail();

        // Get tieup details
        $tieupData = $hospitalTieup->tieup;

        return view(
            'hospital.hospitalprofile.tieups-details',
            compact(
                'hospital',
                'hospitalTieup',
                'tieupData'
            )
        );
    }


    /**
     * Delete Hospital Banner
     */

    public function deleteBanner(Request $request)
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        $hospital = Hospital::findOrFail($hospitalId);

        $bannerToDelete = trim($request->input('banner'));

        if (!$bannerToDelete) {
            return back()->with('error', 'Banner not found.');
        }

        // Existing banners
        $banners = array_filter(
            array_map('trim', explode(',', $hospital->banner ?? ''))
        );

        // Check banner exists
        if (!in_array($bannerToDelete, $banners)) {
            return back()->with('error', 'Banner not found.');
        }

        // Remove banner
        $banners = array_values(
            array_filter(
                $banners,
                fn($banner) => $banner !== $bannerToDelete
            )
        );

        // Delete physical file
        $filePath = public_path($bannerToDelete);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Update database
        $hospital->banner = implode(',', $banners);
        $hospital->save();

        return back()->with('success', 'Banner deleted successfully.');
    }

    public function edit()
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Hospital
        |--------------------------------------------------------------------------
        */

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        $hospital = Hospital::findOrFail($hospitalId);

        /*
        |--------------------------------------------------------------------------
        | Specializations
        |--------------------------------------------------------------------------
        */

        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        $hospital->load('hospitalSpecializations');

        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tieups
        |--------------------------------------------------------------------------
        */

        $tieups = Tieup::orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Selected Tieups
        |--------------------------------------------------------------------------
        */

        $selectedTieupIds = HospitalTieup::where(
            'hospital',
            $hospital->id
        )
            ->pluck('tieup_id')
            ->toArray();

        return view(
            'hospital.hospitalprofile.edit',
            compact(
                'hospital',
                'specializations',
                'facilities',
                'tieups',
                'selectedTieupIds'
            )
        );
    }


    public function update(Request $request)
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            return redirect()->route('hospital.login');
        }

        /*
        |--------------------------------------------------------------------------
        | Get Logged-in Hospital
        |--------------------------------------------------------------------------
        */

        $hospitalId = $hospitalUser->hospital_id ?? $hospitalUser->id;

        $hospital = Hospital::findOrFail($hospitalId);


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'hospital_name' => 'required',
            'hospital_type' => 'required',

            'mobile' => 'required',

            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required',

            /*
            |--------------------------------------------------------------------------
            | Specializations
            |--------------------------------------------------------------------------
            */

            'specializations' => 'required|array|min:1',
            'specializations.*' => 'exists:specializations,id',

            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',

            /*
            |--------------------------------------------------------------------------
            | Tieups
            |--------------------------------------------------------------------------
            */

            'tieups' => 'required|array|min:1',
            'tieups.*' => 'exists:tieups,id',

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            /*
            |--------------------------------------------------------------------------
            | Banner
            |--------------------------------------------------------------------------
            */

            'banner' => 'nullable|array',
            'banner.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Request Data
        |--------------------------------------------------------------------------
        */

        $data = $request->all();


        /*
        |--------------------------------------------------------------------------
        | Hospital Slug
        |--------------------------------------------------------------------------
        */

        $slug = $hospital->hospital_slug;


        /*
        |--------------------------------------------------------------------------
        | Upload Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            /*
            | Delete old logo
            */

            if (
                $hospital->logo &&
                File::exists(public_path($hospital->logo))
            ) {
                File::delete(
                    public_path($hospital->logo)
                );
            }

            $logo = $request->file('logo');

            $logoExtension =
                $logo->getClientOriginalExtension();

            $logoName =
                $slug . '.' . $logoExtension;

            $logoPath =
                'uploads/hospitals/logos/' . $logoName;

            $logo->move(
                public_path('uploads/hospitals/logos'),
                $logoName
            );

            $data['logo'] = $logoPath;
        }


        /*
        |--------------------------------------------------------------------------
        | Upload Banners
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner')) {

            /*
            | Existing banners
            */

            $existingBanners = !empty($hospital->banner)
                ? array_filter(
                    array_map(
                        'trim',
                        explode(',', $hospital->banner)
                    )
                )
                : [];

            /*
            | Create directory
            */

            $bannerDirectory =
                public_path('uploads/hospitals/banners');

            if (!File::exists($bannerDirectory)) {

                File::makeDirectory(
                    $bannerDirectory,
                    0755,
                    true
                );
            }

            /*
            | New banners
            */

            $newBannerPaths = [];

            foreach ($request->file('banner') as $banner) {

                if (!$banner->isValid()) {
                    continue;
                }

                $extension =
                    $banner->getClientOriginalExtension();

                $bannerName =
                    $slug .
                    '-banner-' .
                    uniqid() .
                    '.' .
                    $extension;

                $banner->move(
                    $bannerDirectory,
                    $bannerName
                );

                $newBannerPaths[] =
                    'uploads/hospitals/banners/' .
                    $bannerName;
            }

            /*
            | Keep old + add new
            */

            $data['banner'] = implode(
                ',',
                array_merge(
                    $existingBanners,
                    $newBannerPaths
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Hospital
        |--------------------------------------------------------------------------
        */

        $hospital->update($data);


        /*
        |--------------------------------------------------------------------------
        | Update Hospital Specializations
        |--------------------------------------------------------------------------
        */

        $existingSpecializationIds =
            HospitalSpecialization::where(
                'hospital_id',
                $hospital->id
            )
                ->pluck('specialization_id')
                ->toArray();

        $newSpecializationIds =
            $request->specializations ?? [];


        /*
        | Add new specializations
        */

        foreach (
            array_diff(
                $newSpecializationIds,
                $existingSpecializationIds
            ) as $specializationId
        ) {

            HospitalSpecialization::create([
                'hospital_id' =>
                    $hospital->id,

                'specialization_id' =>
                    $specializationId,

                'status' => 1,
            ]);
        }


        /*
        | Delete unchecked specializations
        */

        HospitalSpecialization::where(
            'hospital_id',
            $hospital->id
        )
            ->whereIn(
                'specialization_id',
                array_diff(
                    $existingSpecializationIds,
                    $newSpecializationIds
                )
            )
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | Update Hospital Facilities
        |--------------------------------------------------------------------------
        */

        $newFacilityIds =
            $request->facilities ?? [];


        /*
        | Delete unchecked facilities
        */

        HospitalFacility::where(
            'hospital_id',
            $hospital->id
        )
            ->whereNotIn(
                'facility_id',
                $newFacilityIds
            )
            ->delete();


        /*
        | Add / Update selected facilities
        */

        foreach ($newFacilityIds as $facilityId) {

            HospitalFacility::updateOrCreate(
                [
                    'hospital_id' =>
                        $hospital->id,

                    'facility_id' =>
                        $facilityId,
                ],
                [
                    'description' =>
                        $request->facility_description[$facilityId]
                        ?? null,

                    'short_description' =>
                        $request->facility_short_description[$facilityId]
                        ?? null,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Hospital Tieups
        |--------------------------------------------------------------------------
        */

        $newTieupIds =
            $request->tieups ?? [];


        /*
        | Delete unchecked tieups
        */

        HospitalTieup::where(
            'hospital',
            $hospital->id
        )
            ->whereNotIn(
                'tieup_id',
                $newTieupIds
            )
            ->delete();


        /*
        | Add selected tieups
        */

        foreach ($newTieupIds as $tieupId) {

            HospitalTieup::updateOrCreate(
                [
                    'hospital' =>
                        $hospital->id,

                    'tieup_id' =>
                        $tieupId,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hospital.hospitalprofile.index')
            ->with(
                'success',
                'Hospital profile updated successfully.'
            );
    }


}
