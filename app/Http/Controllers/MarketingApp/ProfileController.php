<?php

namespace App\Http\Controllers\MarketingApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketingApp\MarketingStaffCollection;
use App\Models\MarketingStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function profile(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Profile Fetched Successfully',
            'data' => new MarketingStaffCollection(collect([$staff]))
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        $staff = auth('sanctum')->user();
        if (!$staff) {

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

        $validator = Validator::make(
            $request->all(),
            [
                'name' =>
                    'nullable|string|max:255',

                'email' =>
                    'nullable|email|max:255',

                'dob' =>
                    'nullable|date',

                'gender' =>
                    'nullable|in:male,female,other',

                'address' =>
                    'nullable|string',

                'country' =>
                    'nullable|string|max:100',

                'state' =>
                    'nullable|string|max:100',

                'city' =>
                    'nullable|string|max:100',

                'pincode' =>
                    'nullable|string|max:10',

                'photo' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Email Duplicate Check
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('email') &&
            strtolower(trim($request->email)) !==
            strtolower(trim($staff->email ?? ''))
        ) {

            $emailExists = MarketingStaff::where(
                'email',
                trim($request->email)
            )
                ->where(
                    'id',
                    '!=',
                    $staff->id
                )
                ->exists();


            if ($emailExists) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Email Already Exists'
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Basic Information
        |--------------------------------------------------------------------------
        */

        if ($request->has('name')) {

            $staff->name =
                trim($request->name);
        }


        if ($request->has('email')) {

            $staff->email =
                $request->email
                ? trim($request->email)
                : null;
        }


        if ($request->has('dob')) {

            $staff->dob =
                $request->dob;
        }


        if ($request->has('gender')) {

            $staff->gender =
                $request->gender;
        }


        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        if ($request->has('address')) {

            $staff->address =
                $request->address;
        }


        if ($request->has('country')) {

            $staff->country =
                $request->country;
        }


        if ($request->has('state')) {

            $staff->state =
                $request->state;
        }


        if ($request->has('city')) {

            $staff->city =
                $request->city;
        }


        if ($request->has('pincode')) {

            $staff->pincode =
                $request->pincode;
        }


        /*
        |--------------------------------------------------------------------------
        | Profile Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Old Photo
            |--------------------------------------------------------------------------
            */

            if (
                $staff->photo &&
                file_exists(
                    public_path($staff->photo)
                )
            ) {

                @unlink(
                    public_path($staff->photo)
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Photo
            |--------------------------------------------------------------------------
            */

            $file =
                $request->file('photo');


            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();


            $directory =
                public_path(
                    'uploads/marketing_staff'
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


            $staff->photo =
                'uploads/marketing_staff/' .
                $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $staff->save();


        return response()->json([
            'success' => 1,
            'message' => 'Profile Updated Successfully',
            'data' => new MarketingStaffCollection(collect([$staff]))
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Current Location
    |--------------------------------------------------------------------------
    */

    public function updateLocation(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {

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

        $validator = Validator::make(
            $request->all(),
            [
                'latitude' =>
                    'required|numeric|between:-90,90',

                'longitude' =>
                    'required|numeric|between:-180,180',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Location
        |--------------------------------------------------------------------------
        */

        $staff->latitude =
            $request->latitude;

        $staff->longitude =
            $request->longitude;

        $staff->save();


        return response()->json([
            'success' => 1,
            'message' => 'Location Updated Successfully',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Availability
    |--------------------------------------------------------------------------
    */

    public function updateAvailability(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'is_available' =>
                    'required|in:0,1',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }


        $staff->is_available =
            $request->is_available;

        $staff->save();


        return response()->json([
            'success' => 1,

            'message' =>
                $staff->is_available
                ? 'You Are Now Available'
                : 'You Are Now Unavailable',

            'data' => [
                'is_available' =>
                    (bool) $staff->is_available
            ]
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Profile Response
    |--------------------------------------------------------------------------
    */

    private function profileResponse($staff)
    {
        return [

            'id' =>
                $staff->id,

            'name' =>
                $staff->name,

            'employee_code' =>
                $staff->employee_code,

            'mobile' =>
                $staff->mobile,

            'email' =>
                $staff->email,

            'photo' =>
                $staff->photo
                ? asset($staff->photo)
                : null,

            'dob' =>
                $staff->dob
                ? $staff->dob->format('Y-m-d')
                : null,

            'gender' =>
                $staff->gender,

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' =>
                $staff->address,

            'country' =>
                $staff->country,

            'state' =>
                $staff->state,

            'city' =>
                $staff->city,

            'pincode' =>
                $staff->pincode,

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            'designation' =>
                $staff->designation,

            'joining_date' =>
                $staff->joining_date
                ? $staff->joining_date->format('Y-m-d')
                : null,

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'latitude' =>
                $staff->latitude,

            'longitude' =>
                $staff->longitude,

            /*
            |--------------------------------------------------------------------------
            | Availability
            |--------------------------------------------------------------------------
            */

            'is_available' =>
                (bool) $staff->is_available,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                (bool) $staff->status,

            'last_login_at' =>
                $staff->last_login_at,
        ];
    }
}