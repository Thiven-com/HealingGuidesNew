<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HospitalGalleryController extends Controller
{
    /**
     * Display hospital galleries.
     */
    public function index(Request $request)
    {
        $query = HospitalGallery::query();

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        $galleries = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.hospital-galleries.index',
            compact('galleries')
        );
    }


    /**
     * Store hospital gallery.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|integer',
            'hospital' => 'required|string|max:255',
            'facility_id' => 'required|integer',
            'hospital_facility_list_id' => 'required|integer',
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
        | Validate File Type
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file_path');

        if ($request->file_type === 'image' && !str_starts_with($file->getMimeType(), 'image/')) {
            return back()
                ->withErrors([
                    'file_path' => 'Please upload a valid image file.',
                ])
                ->withInput();
        }

        if ($request->file_type === 'video' && !str_starts_with($file->getMimeType(), 'video/')) {
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
        | Save Gallery
        |--------------------------------------------------------------------------
        */

        HospitalGallery::create([
            'hospital_id' => $request->hospital_id,
            'hospital' => $request->hospital,
            'facility_id' => $request->facility_id,
            'hospital_facility_list_id' => $request->hospital_facility_list_id,
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
        $gallery = HospitalGallery::findOrFail($id);

        return view(
            'admin.hospital-galleries.show',
            compact('gallery')
        );
    }


    /**
     * Delete gallery.
     */
    public function destroy($id)
    {
        $gallery = HospitalGallery::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete File
        |--------------------------------------------------------------------------
        */

        if (
            !empty($gallery->file_path) &&
            Storage::disk('public')->exists($gallery->file_path)
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