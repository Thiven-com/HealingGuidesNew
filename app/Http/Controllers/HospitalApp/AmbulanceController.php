<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AmbulanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Ambulance Types
    |--------------------------------------------------------------------------
    */

    public function ambulanceTypes(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $ambulanceTypes = AmbulanceType::where('status', 1);

        if ($request->filled('id')) {
            $ambulanceTypes->where('id', $request->id);
        }

        if ($request->filled('search')) {

            $search = $request->search;

            $ambulanceTypes->where(function ($query) use ($search) {
                $query->where(
                    'ambulance_type_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'ambulance_type_code',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        $ambulanceTypes = $ambulanceTypes
            ->latest()
            ->get();

        if ($ambulanceTypes->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Ambulance Types Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Types Fetched Successfully',
            'data' => $ambulanceTypes
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Hospital Ambulances
    |--------------------------------------------------------------------------
    */

    public function ambulances(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $ambulances = Ambulance::with([
            'ambulanceType'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            );

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $ambulances->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambulance Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_type_id')) {
            $ambulances->where(
                'ambulance_type_id',
                $request->ambulance_type_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $ambulances->where(function ($query) use ($search) {

                $query->where(
                    'ambulance_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'ambulance_code',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'vehicle_number',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'registration_number',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'driver_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'driver_mobile',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'model',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Availability
        |--------------------------------------------------------------------------
        */

        if ($request->has('is_available')) {
            $ambulances->where(
                'is_available',
                $request->is_available
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->has('status')) {
            $ambulances->where(
                'status',
                $request->status
            );
        }

        $ambulances = $ambulances
            ->latest()
            ->paginate(20);

        if ($ambulances->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Ambulances Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulances Fetched Successfully',
            'data' => $ambulances
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ambulance Details
    |--------------------------------------------------------------------------
    */

    public function ambulanceDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $ambulance = Ambulance::with([
            'ambulanceType'
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Details Fetched Successfully',
            'data' => $ambulance
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Ambulance
    |--------------------------------------------------------------------------
    */

    public function addAmbulance(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'ambulance_type_id' =>
                'required|integer',

            'ambulance_name' =>
                'required|string|max:255',

            'vehicle_number' =>
                'required|string|max:100',

            'registration_number' =>
                'nullable|string|max:100',

            'driver_name' =>
                'required|string|max:255',

            'driver_mobile' =>
                'required|string|max:20',

            'driver_license_number' =>
                'nullable|string|max:100',

            'model' =>
                'nullable|string|max:255',

            'manufacturing_year' =>
                'nullable|integer|min:1900|max:' . date('Y'),

            'current_location' =>
                'nullable|string|max:500',

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',

            'base_fare' =>
                'nullable|numeric|min:0',

            'price_per_km' =>
                'nullable|numeric|min:0',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'driver_photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'is_available' =>
                'nullable|in:0,1',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Ambulance Type
        |--------------------------------------------------------------------------
        */

        $ambulanceType = AmbulanceType::where(
            'id',
            $request->ambulance_type_id
        )
            ->where(
                'status',
                1
            )
            ->first();

        if (!$ambulanceType) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Ambulance Type'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Vehicle Number
        |--------------------------------------------------------------------------
        */

        $vehicleExists = Ambulance::where(
            'vehicle_number',
            $request->vehicle_number
        )->exists();

        if ($vehicleExists) {
            return response()->json([
                'success' => 0,
                'message' => 'Vehicle Number Already Registered'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Registration Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('registration_number')) {

            $registrationExists = Ambulance::where(
                'registration_number',
                $request->registration_number
            )->exists();

            if ($registrationExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Registration Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Driver Mobile
        |--------------------------------------------------------------------------
        */

        $driverMobileExists = Ambulance::where(
            'driver_mobile',
            $request->driver_mobile
        )->exists();

        if ($driverMobileExists) {
            return response()->json([
                'success' => 0,
                'message' => 'Driver Mobile Number Already Registered'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Driver License
        |--------------------------------------------------------------------------
        */

        if ($request->filled('driver_license_number')) {

            $licenseExists = Ambulance::where(
                'driver_license_number',
                $request->driver_license_number
            )->exists();

            if ($licenseExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Driver License Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Ambulance Code
        |--------------------------------------------------------------------------
        */

        do {

            $ambulanceCode =
                'AMB' . strtoupper(Str::random(6));

        } while (
            Ambulance::where(
                'ambulance_code',
                $ambulanceCode
            )->exists()
        );

        /*
        |--------------------------------------------------------------------------
        | Upload Ambulance Image
        |--------------------------------------------------------------------------
        */

        $image = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/ambulances');

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $image =
                'uploads/ambulances/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Driver Photo
        |--------------------------------------------------------------------------
        */

        $driverPhoto = null;

        if ($request->hasFile('driver_photo')) {

            $file = $request->file('driver_photo');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/ambulance-drivers');

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $driverPhoto =
                'uploads/ambulance-drivers/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Ambulance
        |--------------------------------------------------------------------------
        */

        $ambulance = Ambulance::create([

            'ambulance_type_id' =>
                $request->ambulance_type_id,

            'hospital_id' =>
                $hospital->id,

            'ambulance_name' =>
                $request->ambulance_name,

            'ambulance_code' =>
                $ambulanceCode,

            'image' =>
                $image,

            'vehicle_number' =>
                $request->vehicle_number,

            'registration_number' =>
                $request->registration_number,

            'driver_name' =>
                $request->driver_name,

            'driver_mobile' =>
                $request->driver_mobile,

            'driver_license_number' =>
                $request->driver_license_number,

            'driver_photo' =>
                $driverPhoto,

            'model' =>
                $request->model,

            'manufacturing_year' =>
                $request->manufacturing_year,

            'current_location' =>
                $request->current_location,

            'latitude' =>
                $request->latitude,

            'longitude' =>
                $request->longitude,

            'base_fare' =>
                $request->base_fare ?? 0,

            'price_per_km' =>
                $request->price_per_km ?? 0,

            'is_available' =>
                $request->has('is_available')
                ? $request->is_available
                : 1,

            'status' =>
                $request->has('status')
                ? $request->status
                : 1,
        ]);

        $ambulance->load([
            'ambulanceType'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Added Successfully',
            'data' => $ambulance
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Ambulance
    |--------------------------------------------------------------------------
    */

    public function updateAmbulance(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'ambulance_id' =>
                'required|integer',

            'ambulance_type_id' =>
                'nullable|integer',

            'ambulance_name' =>
                'nullable|string|max:255',

            'vehicle_number' =>
                'nullable|string|max:100',

            'registration_number' =>
                'nullable|string|max:100',

            'driver_name' =>
                'nullable|string|max:255',

            'driver_mobile' =>
                'nullable|string|max:20',

            'driver_license_number' =>
                'nullable|string|max:100',

            'model' =>
                'nullable|string|max:255',

            'manufacturing_year' =>
                'nullable|integer|min:1900|max:' . date('Y'),

            'current_location' =>
                'nullable|string|max:500',

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',

            'base_fare' =>
                'nullable|numeric|min:0',

            'price_per_km' =>
                'nullable|numeric|min:0',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'driver_photo' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'is_available' =>
                'nullable|in:0,1',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Ambulance
        |--------------------------------------------------------------------------
        */

        $ambulance = Ambulance::where(
            'id',
            $request->ambulance_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Not Found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Ambulance Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_type_id')) {

            $ambulanceType = AmbulanceType::where(
                'id',
                $request->ambulance_type_id
            )
                ->where(
                    'status',
                    1
                )
                ->first();

            if (!$ambulanceType) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid Ambulance Type'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Vehicle Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('vehicle_number')) {

            $vehicleExists = Ambulance::where(
                'vehicle_number',
                $request->vehicle_number
            )
                ->where(
                    'id',
                    '!=',
                    $ambulance->id
                )
                ->exists();

            if ($vehicleExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Vehicle Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Registration Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('registration_number')) {

            $registrationExists = Ambulance::where(
                'registration_number',
                $request->registration_number
            )
                ->where(
                    'id',
                    '!=',
                    $ambulance->id
                )
                ->exists();

            if ($registrationExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Registration Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Driver Mobile
        |--------------------------------------------------------------------------
        */

        if ($request->filled('driver_mobile')) {

            $driverMobileExists = Ambulance::where(
                'driver_mobile',
                $request->driver_mobile
            )
                ->where(
                    'id',
                    '!=',
                    $ambulance->id
                )
                ->exists();

            if ($driverMobileExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Driver Mobile Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check Driver License Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('driver_license_number')) {

            $licenseExists = Ambulance::where(
                'driver_license_number',
                $request->driver_license_number
            )
                ->where(
                    'id',
                    '!=',
                    $ambulance->id
                )
                ->exists();

            if ($licenseExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Driver License Number Already Registered'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Fields
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_type_id')) {
            $ambulance->ambulance_type_id =
                $request->ambulance_type_id;
        }

        if ($request->filled('ambulance_name')) {
            $ambulance->ambulance_name =
                $request->ambulance_name;
        }

        if ($request->filled('vehicle_number')) {
            $ambulance->vehicle_number =
                $request->vehicle_number;
        }

        if ($request->has('registration_number')) {
            $ambulance->registration_number =
                $request->registration_number;
        }

        if ($request->has('driver_name')) {
            $ambulance->driver_name =
                $request->driver_name;
        }

        if ($request->has('driver_mobile')) {
            $ambulance->driver_mobile =
                $request->driver_mobile;
        }

        if ($request->has('driver_license_number')) {
            $ambulance->driver_license_number =
                $request->driver_license_number;
        }

        if ($request->has('model')) {
            $ambulance->model =
                $request->model;
        }

        if ($request->has('manufacturing_year')) {
            $ambulance->manufacturing_year =
                $request->manufacturing_year;
        }

        if ($request->has('current_location')) {
            $ambulance->current_location =
                $request->current_location;
        }

        if ($request->has('latitude')) {
            $ambulance->latitude =
                $request->latitude;
        }

        if ($request->has('longitude')) {
            $ambulance->longitude =
                $request->longitude;
        }

        if ($request->has('base_fare')) {
            $ambulance->base_fare =
                $request->base_fare;
        }

        if ($request->has('price_per_km')) {
            $ambulance->price_per_km =
                $request->price_per_km;
        }

        if ($request->has('is_available')) {
            $ambulance->is_available =
                $request->is_available;
        }

        if ($request->has('status')) {
            $ambulance->status =
                $request->status;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Ambulance Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if (
                $ambulance->image &&
                file_exists(
                    public_path($ambulance->image)
                )
            ) {
                @unlink(
                    public_path($ambulance->image)
                );
            }

            $file = $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/ambulances');

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $ambulance->image =
                'uploads/ambulances/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Driver Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('driver_photo')) {

            if (
                $ambulance->driver_photo &&
                file_exists(
                    public_path(
                        $ambulance->driver_photo
                    )
                )
            ) {
                @unlink(
                    public_path(
                        $ambulance->driver_photo
                    )
                );
            }

            $file = $request->file('driver_photo');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path(
                    'uploads/ambulance-drivers'
                );

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $ambulance->driver_photo =
                'uploads/ambulance-drivers/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $ambulance->save();

        $ambulance->load([
            'ambulanceType'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Updated Successfully',
            'data' => $ambulance
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Ambulance Availability
    |--------------------------------------------------------------------------
    */

    public function updateAvailability(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'ambulance_id' =>
                'required|integer',

            'is_available' =>
                'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Ambulance
        |--------------------------------------------------------------------------
        */

        $ambulance = Ambulance::where(
            'id',
            $request->ambulance_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Not Found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Availability
        |--------------------------------------------------------------------------
        */

        $ambulance->is_available =
            $request->is_available;

        $ambulance->save();

        return response()->json([
            'success' => 1,

            'message' =>
                $request->is_available == 1
                ? 'Ambulance Marked Available Successfully'
                : 'Ambulance Marked Unavailable Successfully',

            'data' => [
                'id' =>
                    $ambulance->id,

                'ambulance_name' =>
                    $ambulance->ambulance_name,

                'ambulance_code' =>
                    $ambulance->ambulance_code,

                'vehicle_number' =>
                    $ambulance->vehicle_number,

                'is_available' =>
                    (bool) $ambulance->is_available,
            ]
        ]);
    }
}