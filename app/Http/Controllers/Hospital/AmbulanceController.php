<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AmbulanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Ambulance List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $query = Ambulance::with([
            'ambulanceType'
        ])
            ->where('hospital_id', $hospital->id);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'ambulance_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'ambulance_code',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'vehicle_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'driver_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'driver_mobile',
                        'like',
                        "%{$search}%"
                    );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Ambulance Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_type_id')) {

            $query->where(
                'ambulance_type_id',
                $request->ambulance_type_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Availability
        |--------------------------------------------------------------------------
        */

        if ($request->filled('availability')) {

            $query->where(
                'is_available',
                $request->availability
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        $ambulances = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        $ambulanceTypes = AmbulanceType::where(
            'status',
            1
        )
            ->orderBy('id')
            ->get();


        return view(
            'hospital.ambulances.index',
            compact(
                'ambulances',
                'ambulanceTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $ambulanceTypes = AmbulanceType::where(
            'status',
            1
        )
            ->orderBy('id')
            ->get();

        return view(
            'hospital.ambulances.create',
            compact('ambulanceTypes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();


        $validator = Validator::make(
            $request->all(),
            [
                'ambulance_type_id' => [
                    'required',
                    'exists:ambulance_types,id'
                ],

                'ambulance_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'vehicle_number' => [
                    'required',
                    'string',
                    'max:100',
                    'unique:ambulances,vehicle_number'
                ],

                'registration_number' => [
                    'nullable',
                    'string',
                    'max:100',
                    'unique:ambulances,registration_number'
                ],

                'driver_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'driver_mobile' => [
                    'required',
                    'digits:10',
                    'unique:ambulances,driver_mobile'
                ],

                'driver_license_number' => [
                    'nullable',
                    'string',
                    'max:100'
                ],

                'model' => [
                    'nullable',
                    'string',
                    'max:255'
                ],

                'manufacturing_year' => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:' . date('Y')
                ],

                'base_fare' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'price_per_km' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],

                'driver_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],
            ]
        );


        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Ambulance Image
        |--------------------------------------------------------------------------
        */

        $image = null;

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename =
                time()
                . '_ambulance_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/ambulances'),
                $filename
            );

            $image =
                'uploads/ambulances/'
                . $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Photo
        |--------------------------------------------------------------------------
        */

        $driverPhoto = null;

        if ($request->hasFile('driver_photo')) {

            $file = $request->file(
                'driver_photo'
            );

            $filename =
                time()
                . '_driver_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/ambulance-drivers'),
                $filename
            );

            $driverPhoto =
                'uploads/ambulance-drivers/'
                . $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Ambulance Code
        |--------------------------------------------------------------------------
        */

        $lastAmbulance = Ambulance::latest('id')
            ->first();

        $nextNumber =
            $lastAmbulance
            ? $lastAmbulance->id + 1
            : 1;

        $ambulanceCode =
            'AMB'
            . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Ambulance::create([

            'hospital_id' =>
                $hospital->id,

            'ambulance_type_id' =>
                $request->ambulance_type_id,

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
                $request->base_fare,

            'price_per_km' =>
                $request->price_per_km,

            'is_available' => 1,

            'status' => 1,

        ]);


        return redirect()
            ->route('hospital.ambulances.index')
            ->with(
                'success',
                'Ambulance Added Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $ambulance = Ambulance::with([
            'ambulanceType'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        return view(
            'hospital.ambulances.show',
            compact('ambulance')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $ambulance = Ambulance::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $ambulanceTypes = AmbulanceType::where(
            'status',
            1
        )
            ->orderBy('id')
            ->get();


        return view(
            'hospital.ambulances.edit',
            compact(
                'ambulance',
                'ambulanceTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $hospital = Auth::guard('hospital')->user();

        $ambulance = Ambulance::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $validator = Validator::make(
            $request->all(),
            [
                'ambulance_type_id' => [
                    'required',
                    'exists:ambulance_types,id'
                ],

                'ambulance_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'vehicle_number' => [
                    'required',

                    Rule::unique(
                        'ambulances',
                        'vehicle_number'
                    )->ignore($ambulance->id)
                ],

                'registration_number' => [
                    'nullable',

                    Rule::unique(
                        'ambulances',
                        'registration_number'
                    )->ignore($ambulance->id)
                ],

                'driver_name' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'driver_mobile' => [
                    'required',
                    'digits:10',

                    Rule::unique(
                        'ambulances',
                        'driver_mobile'
                    )->ignore($ambulance->id)
                ],

                'base_fare' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'price_per_km' => [
                    'required',
                    'numeric',
                    'min:0'
                ],

                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],

                'driver_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120'
                ],
            ]
        );


        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $ambulance->ambulance_type_id =
            $request->ambulance_type_id;

        $ambulance->ambulance_name =
            $request->ambulance_name;

        $ambulance->vehicle_number =
            $request->vehicle_number;

        $ambulance->registration_number =
            $request->registration_number;

        $ambulance->driver_name =
            $request->driver_name;

        $ambulance->driver_mobile =
            $request->driver_mobile;

        $ambulance->driver_license_number =
            $request->driver_license_number;

        $ambulance->model =
            $request->model;

        $ambulance->manufacturing_year =
            $request->manufacturing_year;

        $ambulance->base_fare =
            $request->base_fare;

        $ambulance->price_per_km =
            $request->price_per_km;


        /*
        |--------------------------------------------------------------------------
        | Ambulance Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $file =
                $request->file('image');

            $filename =
                time()
                . '_ambulance_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/ambulances'),
                $filename
            );

            $ambulance->image =
                'uploads/ambulances/'
                . $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('driver_photo')) {

            $file =
                $request->file(
                    'driver_photo'
                );

            $filename =
                time()
                . '_driver_'
                . uniqid()
                . '.'
                . $file->getClientOriginalExtension();

            $file->move(
                public_path(
                    'uploads/ambulance-drivers'
                ),
                $filename
            );

            $ambulance->driver_photo =
                'uploads/ambulance-drivers/'
                . $filename;
        }


        $ambulance->save();


        return redirect()
            ->route(
                'hospital.ambulances.index'
            )
            ->with(
                'success',
                'Ambulance Updated Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Availability
    |--------------------------------------------------------------------------
    */

    public function updateAvailability(
        Request $request
    ) {
        $hospital =
            Auth::guard('hospital')->user();


        $validator = Validator::make(
            $request->all(),
            [
                'id' => [
                    'required',
                    'integer'
                ],

                'is_available' => [
                    'required',
                    'in:0,1'
                ],
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' =>
                    $validator->errors()->first()
            ]);
        }


        $ambulance = Ambulance::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->id
            )
            ->first();


        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Ambulance Not Found'
            ], 404);
        }


        $ambulance->is_available =
            $request->is_available;

        $ambulance->save();


        return response()->json([
            'success' => 1,
            'message' =>
                'Ambulance Availability Updated Successfully'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $hospital =
            Auth::guard('hospital')->user();

        $ambulance = Ambulance::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();

        $ambulance->status = 0;

        $ambulance->is_available = 0;

        $ambulance->save();


        return redirect()
            ->route(
                'hospital.ambulances.index'
            )
            ->with(
                'success',
                'Ambulance Deactivated Successfully'
            );
    }

    public function updateStatus(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        $ambulance = Ambulance::where('hospital_id', $hospital->id)
            ->where('id', $request->id)
            ->firstOrFail();

        $ambulance->status = $request->status;

        // If deactivated, it should also become unavailable
        if ($request->status == 0) {
            $ambulance->is_available = 0;
        }

        $ambulance->save();

        return redirect()
            ->back()
            ->with('success', 'Ambulance status updated successfully.');
    }
}