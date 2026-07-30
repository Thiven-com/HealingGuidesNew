<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Postalcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    //
    public function states()
    {
        $states = Postalcode::select('state_name')
            ->whereNotNull('state_name')
            ->distinct()
            ->orderBy('state_name')
            ->pluck('state_name');

        return response()->json([
            'success' => 1,
            'data' => $states,
            'message' => "States Fetched Successfully"
        ]);
    }

    public function postalDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'postal_code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $data = Postalcode::where('pincode', $request->postal_code)->first();

        if (!$data) {
            return response()->json([
                'success' => 0,
                'message' => 'Pincode not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Data fetched successfully',
            'data' => $data,
        ]);
    }

    public function districts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $districts = Postalcode::where('state_name', 'like', '%' . $request->state_name . '%')
            ->whereNotNull('district_name')
            ->select('district_name')
            ->distinct()
            ->orderBy('district_name')
            ->pluck('district_name');

        return response()->json([
            'success' => 1,
            'message' => 'Districts fetched successfully',
            'data' => $districts,
        ]);
    }
}
