<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\HealthInsuranceProvider;
use App\Models\Hospital;
use App\Models\HospitalFacilitiesList;
use App\Models\HospitalFacility;
use App\Models\HospitalGallery;
use App\Models\HospitalSpecialization;
use App\Models\HospitalTieup;
use App\Models\Specialization;
use App\Models\SpecializationCategory;
use App\Models\Tieup;
use App\Models\TieupsList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $facilities = Facility::orderBy('name')
            ->get();
        $tieups = Tieup::orderBy('name')->get();

        return view('admin.hospitals.create', compact('specializations', 'facilities', 'tieups'));
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
            // Facilities
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',
            // Tieups
            'tieups' => 'required|array|min:1',
            'tieups.*' => 'exists:tieups,id',


            // Gallery
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,webm,mov,avi|max:51200',
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

            $bannerPaths = [];

            foreach ($request->file('banner') as $banner) {

                $extension = $banner->getClientOriginalExtension();

                $bannerName = $data['hospital_slug']
                    . '-banner-'
                    . uniqid()
                    . '.'
                    . $extension;

                $bannerPath = $banner->storeAs(
                    'hospital/banner',
                    $bannerName,
                    'public'
                );

                $bannerPaths[] = $bannerPath;
            }

            $data['banner'] = implode(',', $bannerPaths);
        }

        // =========================================================
        // Hospital Gallery - Multiple Photos & Videos
        // =========================================================

        if ($request->hasFile('gallery')) {

            $galleryFiles = [];

            foreach ($request->file('gallery') as $galleryFile) {

                $galleryName = $data['hospital_slug']
                    . '-gallery-'
                    . uniqid()
                    . '.'
                    . $galleryFile->getClientOriginalExtension();

                $galleryFiles[] = $galleryFile->storeAs(
                    'hospital/gallery',
                    $galleryName,
                    'public'
                );
            }

            $data['gallery'] = implode(',', $galleryFiles);
        }

        $hospital = Hospital::create($data);

        // Save Hospital Specializations
        foreach ($request->specializations as $specializationId) {
            $specialization = Specialization::where('id', $specializationId)->first();

            HospitalSpecialization::create([
                'hospital_id' => $hospital->id,
                'specialization_id' => $specializationId,
                'specialization_category_id' => $specialization->specialization_category ?? null,
                'status' => 1,
            ]);

        }
        foreach ($request->facilities as $facilityId) {

            HospitalFacility::create([
                'hospital_id' => $hospital->id,
                'facility_id' => $facilityId,
                'description' => $request->facility_description[$facilityId] ?? null,
                'short_description' => $request->facility_short_description[$facilityId] ?? null,
            ]);
        }
        // =========================================================
        // Save Hospital Tieups
        // =========================================================

        foreach ($request->tieups as $tieupId) {

            HospitalTieup::create([
                'hospital' => $hospital->id,
                'tieup_id' => $tieupId,
            ]);

        }

        return redirect()
            ->route('admin.hospitals.index')
            ->with('success', 'Hospital Created Successfully');
    }

    public function show(Hospital $hospital)
    {
        $hospital->load('hospitalSpecializations.specialization');
        $hospitalTieups = HospitalTieup::with('tieup')
            ->where('hospital', $hospital->id)
            ->get();

        return view('admin.hospitals.show', compact('hospital', 'hospitalTieups'));
    }


    public function edit(Hospital $hospital)
    {
        $specializations = Specialization::where('status', 1)
            ->orderBy('specialization_name')
            ->get();

        $hospital->load('hospitalSpecializations');
        $facilities = Facility::orderBy('name')
            ->get();
        // Get all tieups
        $tieups = Tieup::orderBy('name')
            ->get();

        // Get selected tieup IDs for this hospital
        $selectedTieupIds = HospitalTieup::where(
            'hospital',
            $hospital->id
        )
            ->pluck('tieup_id')
            ->toArray();

        return view(
            'admin.hospitals.edit',
            compact('hospital', 'specializations', 'facilities', 'tieups', 'selectedTieupIds')
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
            // Facilities
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:facilities,id',
            // Tieups
            'tieups' => 'required|array|min:1',
            'tieups.*' => 'exists:tieups,id',

            // Gallery
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,webm,mov,avi|max:51200',
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

        if ($request->hasFile('banner')) {

            // Existing banners
            $existingBanners = !empty($hospital->banner)
                ? array_filter(explode(',', $hospital->banner))
                : [];

            // New banners
            $newBannerPaths = [];

            foreach ($request->file('banner') as $banner) {

                $extension = $banner->getClientOriginalExtension();

                $bannerName = $hospital->hospital_slug
                    . '-banner-'
                    . uniqid()
                    . '.'
                    . $extension;

                $bannerPath = $banner->storeAs(
                    'hospital/banner',
                    $bannerName,
                    'public'
                );

                $newBannerPaths[] = $bannerPath;
            }

            // Keep old + add new
            $data['banner'] = implode(
                ',',
                array_merge($existingBanners, $newBannerPaths)
            );
        }

        $hospital->update($data);

        $existingIds = HospitalSpecialization::where('hospital_id', $hospital->id)
            ->pluck('specialization_id')
            ->toArray();

        $newIds = $request->specializations ?? [];

        // Insert only new specializations
        foreach (array_diff($newIds, $existingIds) as $specializationId) {
            $specialization = Specialization::where('id', $specializationId)->first();
            HospitalSpecialization::create([
                'hospital_id' => $hospital->id,
                'specialization_id' => $specializationId,
                'specialization_category_id' => $specialization->specialization_category ?? null,
                'status' => 1,
            ]);
        }

        // Delete only unchecked specializations
        HospitalSpecialization::where('hospital_id', $hospital->id)
            ->whereIn('specialization_id', array_diff($existingIds, $newIds))
            ->delete();
        /*
           |--------------------------------------------------------------------------
           | Update Hospital Facilities
           |--------------------------------------------------------------------------
           */

        $newFacilityIds = $request->facilities ?? [];

        // Delete unchecked facilities
        HospitalFacility::where(
            'hospital_id',
            $hospital->id
        )
            ->whereNotIn(
                'facility_id',
                $newFacilityIds
            )
            ->delete();

        // Add / update selected facilities
        foreach ($newFacilityIds as $facilityId) {

            HospitalFacility::updateOrCreate(
                [
                    'hospital_id' => $hospital->id,
                    'facility_id' => $facilityId,
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

        // =========================================================
        // Update Hospital Tieups
        // =========================================================

        $newTieupIds = $request->tieups ?? [];

        // Delete unchecked tieups
        HospitalTieup::where(
            'hospital',
            $hospital->id
        )
            ->whereNotIn(
                'tieup_id',
                $newTieupIds
            )
            ->delete();

        // Add selected tieups
        foreach ($newTieupIds as $tieupId) {

            HospitalTieup::updateOrCreate(
                [
                    'hospital' => $hospital->id,
                    'tieup_id' => $tieupId,
                ]
            );
        }

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

    public function facilityDetails($id)
    {
        $facility = HospitalFacility::with([
            'hospital',
            'facility'
        ])->findOrFail($id);

        $facilityId = $facility->facility_id ?? $facility->id;
        $hospitalId = $facility->hospital_id;

        $hospitalFacilities = HospitalFacilitiesList::where('hospital_id', $hospitalId)->where('hospital_facilities_id', $id)->get();

        $galleries = HospitalGallery::where('hospital_facility_list_id', $id)
            ->where('hospital_id', $hospitalId)
            ->latest()
            ->get();

        return view('admin.hospitals.facility-details', compact(
            'facility',
            'hospitalFacilities',
            'galleries'
        ));
    }

    // public function tieupsDetails($id)
    // {
    //     $hospitalTieup = HospitalTieup::with([
    //         'hospitalDetail',
    //         'tieup'
    //     ])->findOrFail($id);

    //     $tieup = $hospitalTieup->tieup;

    //     $hospitalId = $hospitalTieup->hospital;

    //     // Get ALL health insurance providers
    //     $allTieups = HealthInsuranceProvider::orderBy('name', 'asc')->get();

    //     // Get selected health insurance provider IDs for this hospital
    //     $selectedTieupIds = TieupsList::where('hospital_id', $hospitalId)
    //         ->pluck('health_insurance_providers_id')
    //         ->toArray();

    //     return view(
    //         'admin.hospitals.tieups-details',
    //         compact(
    //             'tieup',
    //             'hospitalTieup',
    //             'allTieups',
    //             'selectedTieupIds',
    //             'hospitalId'
    //         )
    //     );
    // }


    public function tieupsDetails($id)
    {
        $hospitalTieup = HospitalTieup::with([
            'hospitalDetail',
            'tieup'
        ])->findOrFail($id);

        $tieup = $hospitalTieup->tieup;

        $hospitalId = $hospitalTieup->hospital;

        // All active health insurance providers
        $allTieups = HealthInsuranceProvider::where('status', 1)
            ->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        /*
         * Get only the providers belonging to this
         * particular hospital_tieups record.
         *
         * hospital_tieups_id = current hospital tieup ID
         */
        $selectedTieupIds = TieupsList::where('hospital_id', $hospitalId)
            ->where('hospital_tieups_id', $hospitalTieup->id)
            ->pluck('health_insurance_providers_id')
            ->toArray();

        return view(
            'admin.hospitals.tieups-details',
            compact(
                'tieup',
                'hospitalTieup',
                'allTieups',
                'selectedTieupIds',
                'hospitalId'
            )
        );
    }

    public function updateTieups(Request $request, $hospitalTieupId)
    {
        $request->validate([
            'health_insurance_providers' => 'nullable|array',
            'health_insurance_providers.*' => 'exists:health_insurance_providers,id',
        ]);

        // Get current hospital tieup
        $hospitalTieup = HospitalTieup::findOrFail($hospitalTieupId);

        $hospitalId = $hospitalTieup->hospital;

        // Get selected provider IDs
        $providerIds = $request->input('health_insurance_providers', []);

        // Make sure IDs are integers
        $providerIds = array_map('intval', $providerIds);

        /*
         * Get existing tieup records for this particular
         * hospital + hospital_tieups record.
         */
        $existingTieups = TieupsList::where('hospital_id', $hospitalId)
            ->where('hospital_tieups_id', $hospitalTieup->id)
            ->get();

        /*
         * Delete providers that were previously selected
         * but are now unchecked.
         */
        foreach ($existingTieups as $existingTieup) {

            if (
                !in_array(
                    (int) $existingTieup->health_insurance_providers_id,
                    $providerIds
                )
            ) {
                $existingTieup->delete();
            }
        }

        /*
         * If nothing is selected, all previous selections
         * have now been removed.
         */
        if (empty($providerIds)) {
            return redirect()
                ->back()
                ->with('success', 'Hospital tie-ups updated successfully.');
        }

        // Fetch selected active providers
        $providers = HealthInsuranceProvider::whereIn('id', $providerIds)
            ->where('status', 1)
            ->orderBy('display_order', 'asc')
            ->get();

        /*
         * Update existing providers or create new providers.
         */
        foreach ($providers as $provider) {

            $tieupList = TieupsList::where('hospital_id', $hospitalId)
                ->where('hospital_tieups_id', $hospitalTieup->id)
                ->where('health_insurance_providers_id', $provider->id)
                ->first();

            $data = [
                'hospital_id' => $hospitalId,
                'hospital_tieups_id' => $hospitalTieup->id,
                'health_insurance_providers_id' => $provider->id,
                'title' => $provider->name,
                'image' => $provider->logo,
                'description' => $provider->description,
            ];

            if ($tieupList) {

                // Already exists → update
                $tieupList->update($data);

            } else {

                // New selection → create
                TieupsList::create($data);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Hospital tie-ups updated successfully.');
    }

    public function deleteBanner(Request $request, Hospital $hospital)
    {
        $request->validate([
            'banner' => 'required|string',
        ]);

        $bannerToDelete = trim($request->banner);

        // Existing banners
        $banners = !empty($hospital->banner)
            ? array_filter(explode(',', $hospital->banner))
            : [];

        // Remove selected banner
        $remainingBanners = array_filter($banners, function ($banner) use ($bannerToDelete) {
            return trim($banner) !== $bannerToDelete;
        });

        // Delete physical file
        if (
            !empty($bannerToDelete) &&
            Storage::disk('public')->exists($bannerToDelete)
        ) {
            Storage::disk('public')->delete($bannerToDelete);
        }

        // Update database
        $hospital->banner = !empty($remainingBanners)
            ? implode(',', $remainingBanners)
            : null;

        $hospital->save();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully.'
        ]);
    }

}