<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AmbulanceBooking;
use App\Models\Ambulance;
use App\Services\NotificationService;

class AmbulanceBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Booking List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = AmbulanceBooking::with([
            'ambulance',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('booking_no', 'like', "%{$search}%")
                    ->orWhere('pickup_address', 'like', "%{$search}%")
                    ->orWhere(
                        'destination_address',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'booking_status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            $query->whereDate(
                'created_at',
                $request->date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Bookings
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.ambulance-bookings.index',
            compact('bookings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Booking Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {


        $booking = AmbulanceBooking::with([
            'ambulance',
        ])

            ->where('id', $id)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Available Ambulances
        |--------------------------------------------------------------------------
        */

        $ambulances = Ambulance::where(
            'status',
            'active'
        )
            ->where(
                'is_available',
                1
            )
            ->with('ambulanceType')
            ->orderBy('ambulance_name')
            ->get();


        return view(
            'admin.ambulance-bookings.show',
            compact(
                'booking',
                'ambulances'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Assign Ambulance
    |--------------------------------------------------------------------------
    */

    public function assignAmbulance(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $request->validate([
            'booking_id' => 'required|integer',
            'ambulance_id' => 'required|integer',
        ]);


        $booking = AmbulanceBooking::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->booking_id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Check Booking
        |--------------------------------------------------------------------------
        */

        if (
            in_array($booking->booking_status, [
                'completed',
                'cancelled',
                'rejected',
            ])
        ) {

            return back()->with(
                'error',
                'This booking cannot be assigned.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Check Ambulance
        |--------------------------------------------------------------------------
        */

        $ambulance = Ambulance::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->ambulance_id
            )
            ->where(
                'status',
                'active'
            )
            ->where(
                'is_available',
                1
            )
            ->first();


        if (!$ambulance) {

            return back()->with(
                'error',
                'Selected ambulance is not available.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Assign
        |--------------------------------------------------------------------------
        */

        $booking->ambulance_id =
            $ambulance->id;

        $booking->booking_status =
            'assigned';

        $booking->assigned_at =
            now();

        $booking->save();


        /*
        |--------------------------------------------------------------------------
        | Ambulance Busy
        |--------------------------------------------------------------------------
        */

        $ambulance->is_available = 0;

        $ambulance->save();

        /*
|--------------------------------------------------------------------------
| Notify Customer
|--------------------------------------------------------------------------
*/

        try {
            if ($booking->customer_id) {
                NotificationService::send(
                    'customer',
                    $booking->customer_id,
                    'ambulance',
                    'Ambulance Assigned',
                    'Good news! An ambulance has been assigned to your booking ' .
                    $booking->booking_no .
                    '. Please be ready at your pickup location and check the app for booking updates.',
                    'ambulance_booked',
                    $booking->id,
                    'booking_details',
                    [
                        'booking_id' => $booking->id,
                        'booking_no' => $booking->booking_no,
                        'ambulance_id' => $ambulance->id,
                        'booking_status' => $booking->booking_status,
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Ambulance assignment notification failed: ' . $e->getMessage());
        }
        /*
|--------------------------------------------------------------------------
| Notify Ambulance App
|--------------------------------------------------------------------------
*/

        try {

            NotificationService::send(
                'ambulance',
                $ambulance->id,
                'ambulance',
                'New Ambulance Booking Assigned',
                'A new booking ' . $booking->booking_no .
                ' has been assigned to you. Pickup: ' .
                $booking->pickup_address .
                '. Destination: ' .
                $booking->destination_address .
                '. Please review the booking details and proceed accordingly.',
                'ambulance_booked',
                $booking->id,
                'booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'ambulance_id' => $ambulance->id,
                    'booking_status' => $booking->booking_status,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'assigned_at' => now()->toDateTimeString(),
                ]
            );

        } catch (\Throwable $e) {

            Log::error('Ambulance app notification failed: ' . $e->getMessage());
        }


        return back()->with(
            'success',
            'Ambulance assigned successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Booking
    |--------------------------------------------------------------------------
    */

    public function reject(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $request->validate([
            'booking_id' => 'required|integer',
            'reject_reason' => 'nullable|string|max:500',
        ]);


        $booking = AmbulanceBooking::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->booking_id
            )
            ->firstOrFail();


        if (
            in_array($booking->booking_status, [
                'completed',
                'cancelled',
            ])
        ) {

            return back()->with(
                'error',
                'This booking cannot be rejected.'
            );
        }
        // Store assigned ambulance before updating booking
        $ambulanceId = $booking->ambulance_id;


        $booking->booking_status =
            'rejected';

        $booking->cancel_reason =
            $request->reject_reason;

        $booking->cancelled_at =
            now();

        $booking->save();


        /*
        |--------------------------------------------------------------------------
        | Release Ambulance
        |--------------------------------------------------------------------------
        */

        if ($booking->ambulance_id) {

            Ambulance::where(
                'id',
                $booking->ambulance_id
            )
                ->where(
                    'hospital_id',
                    $hospital->id
                )
                ->update([
                    'is_available' => 1,
                ]);
        }

        /*
|--------------------------------------------------------------------------
| Notify Customer
|--------------------------------------------------------------------------
*/

        try {
            if ($booking->customer_id) {
                NotificationService::send(
                    'customer',
                    $booking->customer_id,
                    'ambulance',
                    'Ambulance Booking Rejected',
                    'Your ambulance booking ' . $booking->booking_no .
                    ' has been rejected. Reason: ' .
                    ($booking->cancel_reason ?: 'No reason provided') .
                    '. You may try booking another ambulance or contact support for assistance.',
                    'ambulance_booking_rejected',
                    $booking->id,
                    'booking_details',
                    [
                        'booking_id' => $booking->id,
                        'booking_no' => $booking->booking_no,
                        'booking_status' => $booking->booking_status,
                        'cancel_reason' => $booking->cancel_reason,
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Ambulance rejection notification failed: ' . $e->getMessage());
        }

        /*
    |--------------------------------------------------------------------------
    | Notify Ambulance App
    |--------------------------------------------------------------------------
    */

        if ($ambulanceId) {
            try {
                NotificationService::send(
                    'ambulance',
                    $ambulanceId,
                    'ambulance',
                    'Booking Rejected',
                    'The ambulance booking ' . $booking->booking_no .
                    ' assigned to you has been rejected. Reason: ' .
                    ($booking->cancel_reason ?: 'No reason provided') .
                    '. Please check the booking details.',
                    'ambulance_booking_rejected',
                    $booking->id,
                    'booking_details',
                    [
                        'booking_id' => $booking->id,
                        'booking_no' => $booking->booking_no,
                        'ambulance_id' => $ambulanceId,
                        'booking_status' => 'rejected',
                        'cancel_reason' => $booking->cancel_reason,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('Ambulance app rejection notification failed: ' . $e->getMessage());
            }
            /*
            |--------------------------------------------------------------------------
            | Release Ambulance
            |--------------------------------------------------------------------------
            */

            Ambulance::where('id', $ambulanceId)
                ->where('hospital_id', $hospital->id)
                ->update([
                    'is_available' => 1,
                ]);
        }


        return back()->with(
            'success',
            'Ambulance booking rejected successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Live Tracking Page
    |--------------------------------------------------------------------------
    */

    public function track($id)
    {

        $booking = AmbulanceBooking::with([
            'ambulance',
        ])
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        return view(
            'admin.ambulance-bookings.track',
            compact('booking')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $request->validate([
            'booking_id' => 'required|integer',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $booking = AmbulanceBooking::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $request->booking_id
            )
            ->firstOrFail();
        $oldPaymentStatus = $booking->payment_status;


        $booking->payment_status =
            $request->payment_status;

        $booking->save();

        /*
|--------------------------------------------------------------------------
| Notify Customer When Payment Status Changes
|--------------------------------------------------------------------------
*/

        if ($oldPaymentStatus !== $booking->payment_status) {

            try {
                if ($booking->customer_id) {

                    NotificationService::send(
                        'customer',
                        $booking->customer_id,
                        'ambulance',
                        'Ambulance Payment Status Updated',
                        'The payment status for your ambulance booking ' .
                        $booking->booking_no . ' has been updated from ' .
                        ucfirst($oldPaymentStatus) . ' to ' .
                        ucfirst($booking->payment_status) .
                        '. Booking Amount: ₹' .
                        number_format((float) $booking->total_amount, 2) .
                        '. Please check your booking details for the latest update.',
                        'ambulance_payment_status_updated',
                        $booking->id,
                        'booking_details',
                        [
                            'booking_id' => $booking->id,
                            'booking_no' => $booking->booking_no,
                            'payment_status' => $booking->payment_status,
                            'total_amount' => $booking->total_amount,
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error('Ambulance payment notification failed: ' . $e->getMessage());
            }
        }
        /*
       |--------------------------------------------------------------------------
       | Notify Ambulance App
       |--------------------------------------------------------------------------
       */

        if ($booking->ambulance_id) {

            try {

                NotificationService::send(
                    'ambulance',
                    $booking->ambulance_id,
                    'ambulance',
                    'Ambulance Payment Status Updated',
                    'The payment status for booking ' .
                    $booking->booking_no . ' has changed from ' .
                    ucfirst($oldPaymentStatus) . ' to ' .
                    ucfirst($booking->payment_status) .
                    '. Booking Amount: ₹' .
                    number_format((float) $booking->total_amount, 2) .
                    '. Please check the booking details.',
                    'ambulance_payment_status_updated',
                    $booking->id,
                    'booking_details',
                    [
                        'booking_id' => $booking->id,
                        'booking_no' => $booking->booking_no,
                        'ambulance_id' => $booking->ambulance_id,
                        'old_payment_status' => $oldPaymentStatus,
                        'payment_status' => $booking->payment_status,
                        'total_amount' => $booking->total_amount,
                    ]
                );

            } catch (\Throwable $e) {
                Log::error('Ambulance app payment notification failed: ' . $e->getMessage());
            }
        }



        return back()->with(
            'success',
            'Payment status updated successfully.'
        );
    }

    public function location($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $booking = AmbulanceBooking::where('hospital_id', $hospital->id)
            ->where('id', $id)
            ->with('ambulance')
            ->firstOrFail();

        if (!$booking->ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance not assigned',
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => [
                'latitude' => $booking->ambulance->latitude,
                'longitude' => $booking->ambulance->longitude,
                'booking_status' => $booking->booking_status,
                'updated_at' => $booking->ambulance->location_updated_at
                    ? $booking->ambulance->location_updated_at->format('d M Y, h:i:s A')
                    : null,
            ],
        ]);
    }
}