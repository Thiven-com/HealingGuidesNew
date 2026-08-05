<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosticCollection;
use App\Http\Resources\LabTestCollection;
use App\Http\Resources\DiagnosticLabTestCollection;
use App\Models\Diagnostic;
use App\Models\DiagnosticLabTest;
use App\Models\LabTest;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Lab Tests
    |--------------------------------------------------------------------------
    */

    public function labTests(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $labTests = LabTest::where('status', 1);

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {

            $labTests->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $labTests->where(function ($query) use ($search) {

                $query->where(
                    'test_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'test_code',
                    'LIKE',
                    '%' . $search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Sample Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('sample_type')) {

            $labTests->where(
                'sample_type',
                $request->sample_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Fasting Required
        |--------------------------------------------------------------------------
        */

        if ($request->has('fasting_required')) {

            $labTests->where(
                'fasting_required',
                $request->fasting_required
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Home Collection
        |--------------------------------------------------------------------------
        */

        if ($request->has('home_collection')) {

            $labTests->where(
                'home_collection',
                $request->home_collection
            );
        }

        $labTests = $labTests
            ->latest()
            ->paginate(20);

        if ($labTests->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Lab Tests Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Lab Tests Fetched Successfully',
            'data' => new LabTestCollection($labTests)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Diagnostics
    |--------------------------------------------------------------------------
    */

    public function diagnostics(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $diagnostics = Diagnostic::where(
            'status',
            1
        );

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {

            $diagnostics->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $diagnostics->where(function ($query) use ($search) {

                $query->where(
                    'diagnostic_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'diagnostic_code',
                    'LIKE',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'mobile',
                    'LIKE',
                    '%' . $search . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | City
        |--------------------------------------------------------------------------
        */

        if ($request->filled('city')) {

            $diagnostics->where(
                'city',
                $request->city
            );
        }

        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

        if ($request->filled('state')) {

            $diagnostics->where(
                'state',
                $request->state
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pincode
        |--------------------------------------------------------------------------
        */

        if ($request->filled('pincode')) {

            $diagnostics->where(
                'pincode',
                $request->pincode
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Home Collection
        |--------------------------------------------------------------------------
        */

        if ($request->has('home_collection')) {

            $diagnostics->where(
                'home_collection',
                $request->home_collection
            );
        }

        $diagnostics = $diagnostics
            ->latest()
            ->paginate(20);

        if ($diagnostics->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Diagnostics Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Diagnostics Fetched Successfully',
            'data' => new DiagnosticCollection($diagnostics)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Diagnostic Wise Lab Tests
    |--------------------------------------------------------------------------
    */

    public function diagnosticLabTests(Request $request, $id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Diagnostic
        |--------------------------------------------------------------------------
        */

        $diagnostic = Diagnostic::where(
            'id',
            $id
        )
        ->where(
            'status',
            1
        )
        ->first();

        if (!$diagnostic) {

            return response()->json([
                'success' => 0,
                'message' => 'Diagnostic Not Found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Diagnostic Lab Tests
        |--------------------------------------------------------------------------
        */

        $labTests = DiagnosticLabTest::with([
            'diagnostic',
            'labTest'
        ])
        ->where(
            'diagnostic_id',
            $diagnostic->id
        )
        ->where(
            'status',
            1
        );

        /*
        |--------------------------------------------------------------------------
        | Lab Test ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lab_test_id')) {

            $labTests->where(
                'lab_test_id',
                $request->lab_test_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search Lab Test
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $labTests->whereHas(
                'labTest',
                function ($query) use ($search) {

                    $query->where(
                        'test_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'test_code',
                        'LIKE',
                        '%' . $search . '%'
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Home Collection
        |--------------------------------------------------------------------------
        */

        if ($request->has('home_collection')) {

            $labTests->where(
                'home_collection',
                $request->home_collection
            );
        }

        $labTests = $labTests
            ->latest()
            ->paginate(20);

        if ($labTests->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Lab Tests Found For This Diagnostic'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Diagnostic Lab Tests Fetched Successfully',
            'diagnostic' => [
                'id' =>
                    $diagnostic->id,

                'diagnostic_name' =>
                    $diagnostic->diagnostic_name,

                'diagnostic_code' =>
                    $diagnostic->diagnostic_code,

                'home_collection' =>
                    (bool) $diagnostic->home_collection,
            ],
            'data' => new DiagnosticLabTestCollection(
                $labTests
            )
        ]);
    }
}