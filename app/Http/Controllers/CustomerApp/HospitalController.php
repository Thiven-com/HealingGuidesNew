<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\HospitalCollection;
use App\Http\Resources\SpecializationCollection;
use App\Models\Hospital;
use App\Models\Specialization;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    //
    public function hospitals(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = Hospital::with('hospitalSpecializations.specialization')
            ->where('status', 1);

        if ($request->filled('search')) {
            $query->where('hospital_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('specialization_id')) {
            $query->whereHas('hospitalSpecializations', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        $hospitals = $query->latest()->paginate(20);

        if ($hospitals->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Hospitals Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Hospitals Fetched Successfully',
            'data' => new HospitalCollection($hospitals)
        ]);
    }

    public function specializations(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $specializations = Specialization::where('status', 1);
        if ($request->filled('id')) {
            $specializations->where('id', $request->id);
        }
        $specializations = $specializations->latest()->paginate(20);

        if ($specializations->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Specializations Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new SpecializationCollection($specializations),
            'message' => 'Specializations Fetched Successfully'
        ]);
    }
}
