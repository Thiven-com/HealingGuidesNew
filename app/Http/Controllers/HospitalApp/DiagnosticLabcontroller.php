<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticBooking;
use Illuminate\Http\Request;

class DiagnosticLabcontroller extends Controller
{
    public function index(Request $request)
    {
        $hospital = auth('sanctum')->user();

        $bookings = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'diagnostic',
            'items.labTest',
        ])
            ->whereHas('diagnostic', function ($query) use ($hospital) {
                $query->where('hospital_id', $hospital->id);
            })
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Diagnostic lab bookings fetched successfully.',
            'data' => $bookings,
        ], 200);
    }

    public function show($id)
    {
        $hospital = auth('sanctum')->user();

        $booking = DiagnosticBooking::with([
            'customer',
            'familyMember',
            'diagnostic',
            'items.labTest',
        ])
            ->whereHas('diagnostic', function ($query) use ($hospital) {
                $query->where('hospital_id', $hospital->id);
            })
            ->find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Diagnostic lab booking not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Diagnostic lab booking details fetched successfully.',
            'data' => $booking,
        ], 200);
    }
    
}
