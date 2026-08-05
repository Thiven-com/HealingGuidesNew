<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalCollection;
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
}
