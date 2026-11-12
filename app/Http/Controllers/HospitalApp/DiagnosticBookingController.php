<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticBooking;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiagnosticBookingController extends Controller
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


    /**
     * Update diagnostic booking status.
     */
    public function updateStatus(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'status' => false,
                'message' => 'Please Login',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',
            'booking_status' => 'required|in:confirmed,rejected,sample_collected,processing,report_ready,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $booking = DiagnosticBooking::where('id', $request->booking_id)
            ->whereHas('diagnostic', function ($query) use ($hospital) {
                $query->where('hospital_id', $hospital->id);
            })
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Diagnostic lab booking not found.',
            ], 404);
        }

        $statusMessages = [
            'confirmed' => [
                'title' => 'Lab Booking Confirmed',
                'message' => 'Your lab booking ' . $booking->booking_no . ' has been confirmed by the diagnostic centre.',
                'event' => 'diagnostic_booking_confirmed',
            ],
            'rejected' => [
                'title' => 'Lab Booking Rejected',
                'message' => 'Your lab booking ' . $booking->booking_no . ' has been rejected by the diagnostic centre.',
                'event' => 'diagnostic_booking_rejected',
            ],
            'sample_collected' => [
                'title' => 'Sample Collected',
                'message' => 'Your sample has been collected for lab booking ' . $booking->booking_no . '.',
                'event' => 'diagnostic_sample_collected',
            ],
            'processing' => [
                'title' => 'Lab Test Processing',
                'message' => 'Your lab test for booking ' . $booking->booking_no . ' is now being processed.',
                'event' => 'diagnostic_processing',
            ],
            'report_ready' => [
                'title' => 'Lab Report Ready',
                'message' => 'Your lab report for booking ' . $booking->booking_no . ' is ready.',
                'event' => 'diagnostic_report_ready',
            ],
            'completed' => [
                'title' => 'Lab Booking Completed',
                'message' => 'Your lab booking ' . $booking->booking_no . ' has been completed successfully.',
                'event' => 'diagnostic_booking_completed',
            ],
        ];

        $oldStatus = $booking->booking_status;
        $newStatus = $request->booking_status;

        if ($oldStatus === $newStatus) {
            return response()->json([
                'status' => false,
                'message' => 'Booking is already in this status.',
            ], 422);
        }

        if ($oldStatus === 'cancelled' || $oldStatus === 'rejected' || $oldStatus === 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'This booking can no longer be updated.',
            ], 422);
        }

        $allowedTransitions = [
            'pending' => ['confirmed', 'rejected'],
            'confirmed' => ['sample_collected', 'rejected'],
            'sample_collected' => ['processing'],
            'processing' => ['report_ready'],
            'report_ready' => ['completed'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid booking status transition.',
            ], 422);
        }

        $booking->booking_status = $newStatus;
        $booking->save();

        $notification = $statusMessages[$newStatus];

        NotificationService::send(
            'customer',
            $booking->customer_id,
            'diagnostic',
            $notification['title'],
            $notification['message'],
            $notification['event'],
            $booking->id,
            'lab_booking_details',
            [
                'booking_id' => $booking->id,
                'booking_no' => $booking->booking_no,
                'diagnostic_id' => $booking->diagnostic_id,
                'booking_status' => $booking->booking_status,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'payment_status' => $booking->payment_status,
            ]
        );
        

        return response()->json([
            'status' => true,
            'message' => 'Diagnostic booking status updated successfully.',
            'data' => [
                'booking_id' => $booking->id,
                'booking_no' => $booking->booking_no,
                'old_status' => $oldStatus,
                'booking_status' => $booking->booking_status,
            ],
        ], 200);
    }
}
