<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceType;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AmbulanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ambulances = Ambulance::with([
            'hospital',
            'ambulanceType'
        ])
            ->latest()
            ->get();

        return view(
            'admin.ambulances.index',
            compact('ambulances')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        $ambulanceTypes = AmbulanceType::where('status', 1)
            ->orderBy('ambulance_type_name')
            ->get();

        return view(
            'admin.ambulances.create',
            compact(
                'hospitals',
                'ambulanceTypes'
            )
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'hospital_id' => 'required|exists:hospitals,id',
            'ambulance_type_id' => 'required|exists:ambulance_types,id',
            'ambulance_name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'driver_name' => 'required|string|max:255',
            'driver_mobile' => 'required|digits:10',
            'driver_license_number' => 'nullable|string|max:255',
            'driver_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'model' => 'nullable|string|max:255',
            'manufacturing_year' => 'nullable|digits:4',
            'current_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'base_fare' => 'required|numeric|min:0',
            'price_per_km' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'status' => 'required|boolean',

        ]);

        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();

        }

        $ambulance = new Ambulance();

        // Auto Ambulance Code

        $lastAmbulance = Ambulance::latest()->first();

        if ($lastAmbulance) {

            $number = (int) substr($lastAmbulance->ambulance_code, 3) + 1;

        } else {

            $number = 1;

        }

        $ambulance->ambulance_code = 'AMB' . str_pad($number, 5, '0', STR_PAD_LEFT);

        // Driver Photo Upload

        if ($request->hasFile('driver_photo')) {

            $file = $request->file('driver_photo');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/ambulances');

            if (!File::exists($destinationPath)) {

                File::makeDirectory($destinationPath, 0755, true);

            }

            $file->move($destinationPath, $filename);

            $ambulance->driver_photo = 'uploads/ambulances/' . $filename;

        }
        // Upload Ambulance Image
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = time() . '_ambulance_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/ambulances');

            if (!File::exists($destinationPath)) {

                File::makeDirectory($destinationPath, 0755, true);

            }

            $file->move($destinationPath, $filename);

            $ambulance->image = 'uploads/ambulances/' . $filename;
        }


        $ambulance->hospital_id = $request->hospital_id;

        $ambulance->ambulance_type_id = $request->ambulance_type_id;

        $ambulance->ambulance_name = $request->ambulance_name;

        $ambulance->vehicle_number = $request->vehicle_number;

        $ambulance->registration_number = $request->registration_number;

        $ambulance->driver_name = $request->driver_name;

        $ambulance->driver_mobile = $request->driver_mobile;

        $ambulance->driver_license_number = $request->driver_license_number;

        $ambulance->model = $request->model;

        $ambulance->manufacturing_year = $request->manufacturing_year;
        $ambulance->current_location = $request->current_location;

        $ambulance->latitude = $request->latitude;

        $ambulance->longitude = $request->longitude;

        $ambulance->base_fare = $request->base_fare;

        $ambulance->price_per_km = $request->price_per_km;

        $ambulance->is_available = $request->is_available;

        $ambulance->status = $request->status;

        $ambulance->save();

        return redirect()
            ->route('admin.ambulances.index')
            ->with(
                'success',
                'Ambulance Created Successfully.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ambulance = Ambulance::with([
            'hospital',
            'ambulanceType'
        ])
            ->findOrFail($id);

        return view(
            'admin.ambulances.show',
            compact('ambulance')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ambulance = Ambulance::findOrFail($id);

        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        $ambulanceTypes = AmbulanceType::where('status', 1)
            ->orderBy('ambulance_type_name')
            ->get();

        return view(
            'admin.ambulances.edit',
            compact(
                'ambulance',
                'hospitals',
                'ambulanceTypes'
            )
        );
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ambulance = Ambulance::findOrFail($id);

        $validator = Validator::make($request->all(), [

            'hospital_id' => 'required|exists:hospitals,id',
            'ambulance_type_id' => 'required|exists:ambulance_types,id',
            'ambulance_name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'driver_name' => 'required|string|max:255',
            'driver_mobile' => 'required|digits:10',
            'driver_license_number' => 'nullable|string|max:255',
            'driver_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'model' => 'nullable|string|max:255',
            'manufacturing_year' => 'nullable|digits:4',
            'current_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'base_fare' => 'required|numeric|min:0',
            'price_per_km' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'status' => 'required|boolean',

        ]);

        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();

        }

        // Driver Photo Update

        if ($request->hasFile('driver_photo')) {

            if (
                $ambulance->driver_photo &&
                File::exists(public_path($ambulance->driver_photo))
            ) {

                File::delete(public_path($ambulance->driver_photo));

            }

            $file = $request->file('driver_photo');

            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/ambulances');

            if (!File::exists($destinationPath)) {

                File::makeDirectory($destinationPath, 0755, true);

            }

            $file->move($destinationPath, $filename);

            $ambulance->driver_photo = 'uploads/ambulances/' . $filename;

        }
        if ($request->hasFile('image')) {

            if ($ambulance->image && File::exists(public_path($ambulance->image))) {
                File::delete(public_path($ambulance->image));
            }

            $file = $request->file('image');

            $filename = time() . '_ambulance_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('uploads/ambulances');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $ambulance->image = 'uploads/ambulances/' . $filename;
        }

        $ambulance->hospital_id = $request->hospital_id;

        $ambulance->ambulance_type_id = $request->ambulance_type_id;

        $ambulance->ambulance_name = $request->ambulance_name;

        $ambulance->vehicle_number = $request->vehicle_number;

        $ambulance->registration_number = $request->registration_number;

        $ambulance->driver_name = $request->driver_name;

        $ambulance->driver_mobile = $request->driver_mobile;

        $ambulance->driver_license_number = $request->driver_license_number;

        $ambulance->model = $request->model;

        $ambulance->manufacturing_year = $request->manufacturing_year;

        $ambulance->current_location = $request->current_location;

        $ambulance->latitude = $request->latitude;

        $ambulance->longitude = $request->longitude;
        $ambulance->base_fare = $request->base_fare;

        $ambulance->price_per_km = $request->price_per_km;

        $ambulance->is_available = $request->is_available;

        $ambulance->status = $request->status;

        $ambulance->save();

        return redirect()
            ->route('admin.ambulances.index')
            ->with(
                'success',
                'Ambulance Updated Successfully.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ambulance = Ambulance::findOrFail($id);

        if (
            $ambulance->driver_photo &&
            File::exists(public_path($ambulance->driver_photo))
        ) {

            File::delete(public_path($ambulance->driver_photo));

        }

        $ambulance->delete();

        return redirect()
            ->route('admin.ambulances.index')
            ->with(
                'success',
                'Ambulance Deleted Successfully.'
            );
    }
    /**
     * Toggle ambulance status.
     */
    public function status($id)
    {
        $ambulance = Ambulance::findOrFail($id);

        $ambulance->status = $ambulance->status ? 0 : 1;

        $ambulance->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Ambulance Status Updated Successfully.'
            );
    }

    /**
     * Toggle ambulance availability.
     */
    public function availabilityStatus($id)
    {
        $ambulance = Ambulance::findOrFail($id);

        $ambulance->is_available = $ambulance->is_available ? 0 : 1;

        $ambulance->save();

        return redirect()
            ->back()
            ->with(
                'success',
                'Ambulance Availability Updated Successfully.'
            );
    }
}