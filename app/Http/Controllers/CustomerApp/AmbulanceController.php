<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceCollection;
use App\Http\Resources\AmbulanceResource;
use App\Http\Resources\AmbulanceTypeCollection;
use App\Http\Resources\AmbulanceTypeResource;
use App\Models\Ambulance;
use App\Models\AmbulanceType;
use App\Models\Diagnostic;
use App\Models\DiagnosticLabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'hospital',
            'prices'
        ])
            ->where('status', 1)
            ->where('is_available', 1);

        if ($request->filled('id')) {

            $query->where(
                'id',
                $request->id
            );
        }

        if ($request->filled('ambulance_type_id')) {

            $query->where(
                'ambulance_type_id',
                $request->ambulance_type_id
            );
        }

        if ($request->filled('hospital_id')) {

            $query->where(
                'hospital_id',
                $request->hospital_id
            );
        }

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'ambulance_name',
                    'like',
                    '%' . $request->search . '%'
                )

                    ->orWhere(
                        'ambulance_code',
                        'like',
                        '%' . $request->search . '%'
                    )

                    ->orWhere(
                        'vehicle_number',
                        'like',
                        '%' . $request->search . '%'
                    )

                    ->orWhere(
                        'driver_name',
                        'like',
                        '%' . $request->search . '%'
                    );

            });
        }

        if ($request->filled('accept_free_booking')) {

            $query->where(
                'accept_free_booking',
                $request->accept_free_booking
            );
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

    public function freeAmbulance(Request $request)
    {
        try {
            $data = DB::table('diagnostic_lab_tests as dlt')
                ->join('lab_tests as lt', 'lt.id', '=', 'dlt.lab_test_id')
                ->where('dlt.free_ambulances', 1)
                ->select(
                    'lt.*'
                )
                ->distinct()
                ->get()->map(function ($test) {
                    $test->image = asset($test->image);
                    return $test;
                });

            return response()->json([
                'success' => 1,
                'message' => 'Free ambulance lab tests fetched successfully.',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function freeAmbulanceDiagnostics(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        if (!$request->filled('lab_test_id')) {
            return response()->json([
                'success' => 0,
                'message' => 'Lab Test ID is required'
            ], 422);
        }

        $labTestId = $request->lab_test_id;

        $diagnosticIds = DiagnosticLabTest::where('lab_test_id', $labTestId)
            ->where('free_ambulances', 1)
            ->where('status', 1)
            ->pluck('diagnostic_id')
            ->unique();

        if ($diagnosticIds->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No diagnostics found for free ambulance'
            ], 200);
        }

        $diagnostics = Diagnostic::whereIn('id', $diagnosticIds)
            ->where('status', 1)
            ->get();

        if ($diagnostics->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No diagnostics found for free ambulance'
            ], 200);
        }

        // Convert image paths to full asset URLs
        $diagnostics->transform(function ($diagnostic) {
            if ($diagnostic->logo) {
                $diagnostic->logo = asset($diagnostic->logo);
            }

            if ($diagnostic->banner) {
                $diagnostic->banner = asset($diagnostic->banner);
            }

            return $diagnostic;
        });

        return response()->json([
            'success' => 1,
            'message' => 'Free Ambulance Diagnostics Fetched Successfully',
            'data' => $diagnostics
        ], 200);
    }


    // public function freeAmbulanceDiagnostics(Request $request)
    // {
    //     $user = auth('sanctum')->user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'Please Login'
    //         ], 401);
    //     }

    //     if (!$request->filled('lab_test_id')) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'Lab Test ID is required'
    //         ], 422);
    //     }

    //     $labTestId = $request->lab_test_id;

    //     $diagnosticIds = DiagnosticLabTest::where(
    //         'lab_test_id',
    //         $labTestId
    //     )
    //         ->where('free_ambulances', 1)
    //         ->where('status', 1)
    //         ->pluck('diagnostic_id')
    //         ->unique();

    //     if ($diagnosticIds->isEmpty()) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'No diagnostics found for free ambulance'
    //         ]);
    //     }

    //     $diagnostics = Diagnostic::whereIn('id', $diagnosticIds)
    //         ->where('status', 1)
    //         ->get();

    //     if ($diagnostics->isEmpty()) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'No diagnostics found for free ambulance'
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => 1,
    //         'message' => 'Free Ambulance Diagnostics Fetched Successfully',
    //         'data' => $diagnostics
    //     ]);
    // }
}