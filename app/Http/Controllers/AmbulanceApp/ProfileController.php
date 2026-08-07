<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use Illuminate\Http\Request;

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
}