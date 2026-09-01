<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AmbulanceBooking;
use App\Models\Ambulance;

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


        $booking->payment_status =
            $request->payment_status;

        $booking->save();


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