<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => [
                'required',
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/'
            ],

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $ambulance = Ambulance::where(
            'driver_mobile',
            $request->mobile
        )->first();
        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Not Registered'
            ]);
        }
        if ($ambulance->status != 1) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.'
            ]);
        }
        $otp = 1234;
        // $otp = rand(1000, 9999);
        $ambulance->otp = $otp;
        $ambulance->save();
        return response()->json([
            'success' => 1,
            'message' => 'OTP Sent Successfully'
        ]);
    }
    public function verifyMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => [
                'required',
                'digits:10'
            ],

            'otp' => [
                'required',
                'digits:4'
            ],

        ]);
        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $ambulance = Ambulance::where(
            'driver_mobile',
            $request->mobile
        )->first();
        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Not Registered'
            ]);
        }
        if ($ambulance->status != 1) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.'
            ]);
        }
        if (
            !$ambulance->otp ||
            $ambulance->otp != $request->otp
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid OTP'
            ]);
        }
        $ambulance->save();
        $ambulance->tokens()->delete();
        $token = $ambulance
            ->createToken('AmbulanceApp')
            ->plainTextToken;

        return response()->json([
            'success' => 1,
            'message' => 'Logged In Successfully',
            'data' => [

                'token' => $token,

                'user' => new AmbulanceCollection(collect([$ambulance]))
            ]
        ]);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'mobile' => [
                'required',
                'digits:10'
            ],

        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }


        $ambulance = Ambulance::where(
            'driver_mobile',
            $request->mobile
        )->first();


        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Mobile Number Not Registered'
            ]);
        }


        if ($ambulance->status != 1) {

            return response()->json([
                'success' => 0,
                'message' => 'Your Account Is Inactive. Please Contact Admin.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Generate New OTP
        |--------------------------------------------------------------------------
        */

        $otp = 1234;

        // Production:
        // $otp = rand(1000, 9999);


        $ambulance->otp = $otp;

        $ambulance->save();


        // Send SMS here


        return response()->json([
            'success' => 1,
            'message' => 'OTP Resent Successfully'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $ambulance = auth('sanctum')->user();


        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Current Token
        |--------------------------------------------------------------------------
        */

        $ambulance
            ->currentAccessToken()
            ->delete();


        return response()->json([
            'success' => 1,
            'message' => 'Logged Out Successfully'
        ]);
    }
}