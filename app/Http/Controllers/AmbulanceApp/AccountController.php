<?php
namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $otp = 1234;

        $ambulance = Ambulance::where('driver_mobile', $request->mobile)->first();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance not registered.'
            ]);
        }

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
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $ambulance = Ambulance::where('driver_mobile', $request->mobile)->first();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance not found.'
            ]);
        }

        if ($ambulance->otp != $request->otp) {
            return response()->json([
                'success' => 0,
                'message' => 'OTP Mismatch'
            ]);
        }

        // $ambulance->otp = null;
        $ambulance->save();

        $token = $ambulance->createToken('AmbulanceApp')->plainTextToken;

        return response()->json([
            'success' => 1,
            'data' => [
                'token' => $token,
                'user' => $ambulance
            ],
            'message' => 'Logged in successfully'
        ]);
    }
}