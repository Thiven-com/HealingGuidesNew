<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosticCollection;
use App\Http\Resources\DiagnosticLabTestCollection;
use App\Http\Resources\DiagnosticLabTestsCollection;
use App\Http\Resources\LabTestCollection;
use App\Models\Diagnostic;
use App\Models\DiagnosticLabTest;
use App\Models\LabTest;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    //
    public function diagnostics(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $diagnostics = Diagnostic::where('status', 1);

        if ($request->filled('id')) {
            $diagnostics->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $diagnostics->where('diagnostic_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('city')) {
            $diagnostics->where('city', $request->city);
        }

        if ($request->filled('home_collection')) {
            $diagnostics->where('home_collection', $request->home_collection);
        }

        $diagnostics = $diagnostics->latest()->paginate(20);

        if ($diagnostics->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Diagnostics Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DiagnosticCollection($diagnostics),
            'message' => 'Diagnostics Fetched Successfully'
        ]);
    }

    public function labTests(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $labTests = LabTest::where('status', 1);

        if ($request->filled('id')) {
            $labTests->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $labTests->where('test_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('home_collection')) {
            $labTests->where('home_collection', $request->home_collection);
        }

        if ($request->filled('fasting_required')) {
            $labTests->where('fasting_required', $request->fasting_required);
        }

        $labTests = $labTests->latest()->paginate(20);

        if ($labTests->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Lab Tests Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new LabTestCollection($labTests),
            'message' => 'Lab Tests Fetched Successfully'
        ]);
    }

    public function labTestDiagnostics(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $diagnostics = DiagnosticLabTest::with([
            'diagnostic',
            'labTest'
        ])
            ->where('lab_test_id', $id)
            ->where('status', 1);

        if ($request->filled('home_collection')) {
            $diagnostics->where('home_collection', $request->home_collection);
        }

        $diagnostics = $diagnostics->paginate(20);

        if ($diagnostics->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Diagnostics Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DiagnosticLabTestCollection($diagnostics),
            'message' => 'Diagnostics Fetched Successfully'
        ]);
    }

    public function diagnosticLabTests(Request $request, $id)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $labTests = DiagnosticLabTest::with([
            'labTest',
            'diagnostic'
        ])
            ->where('diagnostic_id', $id)
            ->where('status', 1);

        if ($request->filled('search')) {

            $labTests->whereHas('labTest', function ($query) use ($request) {

                $query->where('test_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('test_code', 'LIKE', '%' . $request->search . '%');

            });
        }

        if ($request->filled('home_collection')) {
            $labTests->where('home_collection', $request->home_collection);
        }

        $labTests = $labTests->latest()->paginate(20);

        if ($labTests->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Lab Tests Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new DiagnosticLabTestsCollection($labTests),
            'message' => 'Lab Tests Fetched Successfully'
        ]);
    }
}
