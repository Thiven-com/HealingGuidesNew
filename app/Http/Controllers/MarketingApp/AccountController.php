<?php

namespace App\Http\Controllers\MarketingApp;

use App\Http\Controllers\Controller;
use App\Models\MarketingStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|digits:10',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $staff = MarketingStaff::where(
            'mobile',
            $request->mobile
        )->first();


        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Not Registered',
            ], 404);
        }

        if (!$staff->status) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.',
            ], 403);
        }

        $otp = '1234';

        $staff->otp = $otp;

        $staff->save();
        return response()->json([
            'success' => 1,
            'message' => 'OTP Sent Successfully',

            'data' => [
                'mobile' => $staff->mobile,
            ],
        ]);
    }

    public function verifyMobile(Request $request)
    {
        
        $validator = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|digits:10',
                'otp' => 'required|digits:4',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }


        $staff = MarketingStaff::where(
            'mobile',
            $request->mobile
        )->first();


        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Marketing Staff Not Found',
            ], 404);
        }


        if (!$staff->status) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.',
            ]);
        }


        if (
            !$staff->otp ||
            (string) $staff->otp !== (string) $request->otp
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Invalid OTP',
            ], 422);
        }

        $staff->tokens()->delete();

        $token = $staff
            ->createToken('marketing-app')
            ->plainTextToken;

        // $staff->otp = null;

        $staff->last_login_at = Carbon::now();

        $staff->is_available = 1;

        $staff->save();


        return response()->json([
            'success' => 1,
            'message' => 'Login Successfully',

            'data' => [

                'token' => $token,

                'staff' => [

                    'id' => $staff->id,

                    'name' => $staff->name,

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

                    'designation' =>
                        $staff->designation,

                    'gender' =>
                        $staff->gender,

                    'dob' =>
                        $staff->dob,

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

                    'joining_date' =>
                        $staff->joining_date,

                    'latitude' =>
                        $staff->latitude,

                    'longitude' =>
                        $staff->longitude,

                    'is_available' =>
                        (bool) $staff->is_available,

                    'status' =>
                        (bool) $staff->status,
                ],
            ],
        ]);
    }

    public function resendOtp(Request $request)
    {
       

        $validator = Validator::make(
            $request->all(),
            [
                'mobile' => 'required|digits:10',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }


        $staff = MarketingStaff::where(
            'mobile',
            $request->mobile
        )->first();


        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Not Registered',
            ], 404);
        }


        if (!$staff->status) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.',
            ], 403);
        }


        // Development
        $otp = '1234';

        // Production:
        // $otp = rand(1000, 9999);
        $staff->otp = $otp;

        $staff->save();

        return response()->json([
            'success' => 1,
            'message' => 'OTP Resent Successfully',
            'data' => [
                'mobile' =>
                    $staff->mobile,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $staff = auth('sanctum')->user();
        if (!$staff) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }
        if ($staff->currentAccessToken()) {

            $staff
                ->currentAccessToken()
                ->delete();
        }
        return response()->json([
            'success' => 1,
            'message' => 'Logout Successfully',
        ]);
    }
}