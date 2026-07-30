<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use App\Http\Resources\AmbulanceResource;
use App\Http\Resources\AmbulanceTypeCollection;
use App\Http\Resources\AmbulanceTypeResource;
use App\Models\Ambulance;
use App\Models\AmbulanceType;
use Illuminate\Http\Request;

class AmbulanceController extends Controller
{
    /**
     * Ambulance Types
     */
    public function ambulanceTypes(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = AmbulanceType::where('status', 1);

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $query->where('ambulance_type_name', 'like', '%' . $request->search . '%');
        }

        $ambulanceTypes = $query
            ->orderBy('ambulance_type_name')
            ->get();

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Types Fetched Successfully',
            'data' => new AmbulanceTypeCollection($ambulanceTypes)
        ]);
    }

    /**
     * Ambulance Type Details
     */
    public function ambulanceTypeDetails($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $ambulanceType = AmbulanceType::where('status', 1)
            ->find($id);

        if (!$ambulanceType) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Type Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Type Details',
            'data' => new AmbulanceTypeResource($ambulanceType)
        ]);
    }

    /**
     * Ambulances List
     */
    public function ambulances(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = Ambulance::with([
            'ambulanceType',
            'hospital'
        ])->where('status', 1)->where('is_available', 1);

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('ambulance_type_id')) {
            $query->where('ambulance_type_id', $request->ambulance_type_id);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ambulance_name', 'like', '%' . $request->search . '%')
                    ->orWhere('ambulance_code', 'like', '%' . $request->search . '%')
                    ->orWhere('vehicle_number', 'like', '%' . $request->search . '%')
                    ->orWhere('driver_name', 'like', '%' . $request->search . '%');
            });
        }

        $ambulances = $query
            ->orderBy('ambulance_name')
            ->paginate(10);

        return response()->json([
            'success' => 1,
            'message' => 'Ambulances Fetched Successfully',
            'data' => new AmbulanceCollection($ambulances)
        ]);
    }

    /**
     * Ambulance Details
     */
    public function ambulanceDetails($id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $ambulance = Ambulance::with([
            'ambulanceType',
            'hospital'
        ])
            ->where('status', 1)
            ->find($id);

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Details Fetched Successfully',
            'data' => new AmbulanceResource($ambulance)
        ]);
    }
}