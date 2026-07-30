<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorCollection;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    public function doctors(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = Doctor::with([
            'hospital',
            'hospitalSpecialization.specialization'
        ])->where('status', 1);

        if ($request->filled('search')) {
            $query->where('doctor_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('specialization_id')) {
            $query->whereHas('hospitalSpecialization', function ($q) use ($request) {
                $q->where('specialization_id', $request->specialization_id);
            });
        }

        if ($request->filled('experience')) {
            $query->where('experience', '>=', $request->experience);
        }

        $doctors = $query->latest()->paginate(20);
        if ($doctors->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Doctors Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Doctors Fetched Successfully',
            'data' => new DoctorCollection($doctors)
        ]);
    }
}
