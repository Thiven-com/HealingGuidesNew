<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile()
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new AmbulanceCollection(collect([$ambulance])),
            'message' => 'Ambulance profile fetched successfully'
        ]);
    }

    public function firebase_token(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'firebase_token' => 'nullable|string',
            'ios_firebase_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Remove duplicate Android token from other ambulances
        if ($request->filled('firebase_token')) {
            Ambulance::where('firebase_token', $request->firebase_token)
                ->where('id', '!=', $ambulance->id)
                ->update([
                    'firebase_token' => null
                ]);
        }

        // Remove duplicate iOS token from other ambulances
        if ($request->filled('ios_firebase_token')) {
            Ambulance::where('ios_firebase_token', $request->ios_firebase_token)
                ->where('id', '!=', $ambulance->id)
                ->update([
                    'ios_firebase_token' => null
                ]);
        }

        // Update current ambulance tokens
        $ambulance->firebase_token = $request->input('firebase_token');
        $ambulance->ios_firebase_token = $request->input('ios_firebase_token');
        $ambulance->save();

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Firebase tokens updated successfully.'
        ]);
    }
}