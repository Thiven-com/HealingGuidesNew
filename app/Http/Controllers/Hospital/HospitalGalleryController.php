<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\HospitalGallery;
use App\Models\HospitalFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HospitalGalleryController extends Controller
{
    /**
     * Get logged-in hospital ID.
     */
    private function getHospitalId()
    {
        $hospitalUser = Auth::guard('hospital')->user();

        if (!$hospitalUser) {
            abort(403, 'Unauthenticated.');
        }

        return $hospitalUser->hospital_id ?? $hospitalUser->id;
    }


    /**
     * Display hospital galleries.
     */
    public function index(Request $request)
    {
        $hospitalId = $this->getHospitalId();

        $query = HospitalGallery::where(
            'hospital_id',
            $hospitalId
        );

        if ($request->filled('facility_id')) {
            $query->where(
                'facility_id',
                $request->facility_id
            );
        }

        if ($request->filled('file_type')) {
            $query->where(
                'file_type',
                $request->file_type
            );
        }

        $galleries = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'hospital.hospital-galleries.index',
            compact('galleries')
        );
    }


    /**
     * Store hospital gallery.
     */
    public function store(Request $request)
    {
        $hospitalId = $this->getHospitalId();

        $request->validate([
            'facility_id' => 'required|integer',
            'file_type' => 'required|in:image,video',
            'file_path' => [
                'required',
                'file',
                'max:51200',
                'mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check Facility Belongs To Logged-In Hospital
        |--------------------------------------------------------------------------
        */

        $hospitalFacility = HospitalFacility::where(
            'hospital_id',
            $hospitalId
        )
        ->where(
            'id',
            $request->facility_id
        )
        ->first();

        /*
        |--------------------------------------------------------------------------
        | If your form sends facility_id from hospital_facilities.facility_id
        |--------------------------------------------------------------------------
        */

        if (!$hospitalFacility) {
            $hospitalFacility = HospitalFacility::where(
                'hospital_id',
                $hospitalId
            )
            ->where(
                'facility_id',
                $request->facility_id
            )
            ->first();
        }

        if (!$hospitalFacility) {
            return back()
                ->withErrors([
                    'facility_id' => 'Invalid facility selected.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Validate File Type
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file_path');

        if (
            $request->file_type === 'image' &&
            !str_starts_with($file->getMimeType(), 'image/')
        ) {
            return back()
                ->withErrors([
                    'file_path' => 'Please upload a valid image file.',
                ])
                ->withInput();
        }

        if (
            $request->file_type === 'video' &&
            !str_starts_with($file->getMimeType(), 'video/')
        ) {
            return back()
                ->withErrors([
                    'file_path' => 'Please upload a valid video file.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        $path = $file->store(
            'hospital-galleries',
            'public'
        );


        /*
        |--------------------------------------------------------------------------
        | Get Hospital Name
        |--------------------------------------------------------------------------
        */

        $hospital = $hospitalFacility->hospital;

        $hospitalName = $hospital->hospital_name
            ?? $hospital->name
            ?? 'Hospital';


        /*
        |--------------------------------------------------------------------------
        | Save Gallery
        |--------------------------------------------------------------------------
        */

        HospitalGallery::create([
            'hospital_id' => $hospitalId,

            'hospital' => $hospitalName,

            'facility_id' => $hospitalFacility->facility_id,

            'file_type' => $request->file_type,

            'file_path' => $path,
        ]);


        return back()->with(
            'success',
            'Hospital gallery added successfully.'
        );
    }


    /**
     * Display a single gallery.
     */
    public function show($id)
    {
        $hospitalId = $this->getHospitalId();

        $gallery = HospitalGallery::where(
            'hospital_id',
            $hospitalId
        )
        ->where(
            'id',
            $id
        )
        ->firstOrFail();

        return view(
            'hospital.hospital-galleries.show',
            compact('gallery')
        );
    }


    /**
     * Delete gallery.
     */
    public function destroy($id)
    {
        $hospitalId = $this->getHospitalId();

        /*
        |--------------------------------------------------------------------------
        | Only Allow Logged-In Hospital's Gallery
        |--------------------------------------------------------------------------
        */

        $gallery = HospitalGallery::where(
            'hospital_id',
            $hospitalId
        )
        ->where(
            'id',
            $id
        )
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Delete File
        |--------------------------------------------------------------------------
        */

        if (
            !empty($gallery->file_path) &&
            Storage::disk('public')->exists(
                $gallery->file_path
            )
        ) {
            Storage::disk('public')->delete(
                $gallery->file_path
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $gallery->delete();


        return back()->with(
            'success',
            'Hospital gallery deleted successfully.'
        );
    }
}