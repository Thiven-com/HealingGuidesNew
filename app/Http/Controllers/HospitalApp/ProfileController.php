<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalCollection;
use App\Models\Hospital;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function profile()
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new HospitalCollection(collect([$hospital])),
            'message' => 'Hospital fetched successfully'
        ]);
    }

    public function firebase_token(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        // Validate Firebase tokens
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'firebase_token' => 'nullable|string',
                'ios_firebase_token' => 'nullable|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Get current hospital's old Android token
        $oldFirebaseToken = $user->firebase_token;

        // Remove duplicate Android token from other hospitals
        if (!empty($oldFirebaseToken)) {
            Hospital::where('firebase_token', $oldFirebaseToken)
                ->where('id', '!=', $user->id)
                ->update([
                    'firebase_token' => null,
                ]);
        }

        // Get current hospital's old iOS token
        $oldIosFirebaseToken = $user->ios_firebase_token;

        // Remove duplicate iOS token from other hospitals
        if (!empty($oldIosFirebaseToken)) {
            Hospital::where('ios_firebase_token', $oldIosFirebaseToken)
                ->where('id', '!=', $user->id)
                ->update([
                    'ios_firebase_token' => null,
                ]);
        }

        // Remove incoming Android token from other hospitals
        if ($request->filled('firebase_token')) {
            Hospital::where('firebase_token', $request->firebase_token)
                ->where('id', '!=', $user->id)
                ->update([
                    'firebase_token' => null,
                ]);
        }

        // Remove incoming iOS token from other hospitals
        if ($request->filled('ios_firebase_token')) {
            Hospital::where('ios_firebase_token', $request->ios_firebase_token)
                ->where('id', '!=', $user->id)
                ->update([
                    'ios_firebase_token' => null,
                ]);
        }

        // Update current hospital's Firebase tokens
        $user->firebase_token = $request->input('firebase_token');
        $user->ios_firebase_token = $request->input('ios_firebase_token');
        $user->save();

        return response()->json([
            'success' => 1,
            'message' => 'Firebase tokens updated successfully.',
        ]);
    }
}
