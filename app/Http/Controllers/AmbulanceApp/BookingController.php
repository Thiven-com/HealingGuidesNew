<?php

namespace App\Http\Controllers\AmbulanceApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceBookingCollection;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function bookings(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = AmbulanceBooking::with([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'ambulance_id',
                $ambulance->id
            );


        /*
        |--------------------------------------------------------------------------
        | Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->type == 'active') {

            $query->whereIn('booking_status', [
                'ambulance_assigned',
                'on_the_way'
            ]);

        } elseif ($request->type == 'history') {

            $query->whereIn('booking_status', [
                'completed',
                'cancelled'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Specific Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $query->where(
                'booking_status',
                $request->booking_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_no',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'pickup_address',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'destination_address',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Results
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest()
            ->paginate(20);


        return response()->json([
            'success' => 1,
            'message' => 'Bookings Fetched Successfully',

            'data' =>
                new AmbulanceBookingCollection(
                    $bookings
                )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Details
    |--------------------------------------------------------------------------
    */

    public function bookingDetails($id)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $booking = AmbulanceBooking::with([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'ambulance_id',
                $ambulance->id
            )
            ->first();

        if (!$booking) {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Booking Not Found'
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Booking Details Fetched Successfully',

            'data' =>
                new AmbulanceBookingCollection(
                    collect([$booking])
                )
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Start Trip
    |--------------------------------------------------------------------------
    |
    | ambulance_assigned -> on_the_way
    |
    */

    public function startTrip(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'booking_id' =>
                    'required|integer'
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' =>
                    $validator->errors()->first(),

                'errors' =>
                    $validator->errors()
            ]);
        }

        $booking = AmbulanceBooking::where(
            'id',
            $request->booking_id
        )
            ->where(
                'ambulance_id',
                $ambulance->id
            )
            ->first();

        if (!$booking) {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Booking Not Found'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if (
            $booking->booking_status !=
            'ambulance_assigned'
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Trip Cannot Be Started'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Start
        |--------------------------------------------------------------------------
        */

        $booking->booking_status =
            'on_the_way';
        $booking->pickup_code = rand(1000, 9999);
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
        | Relations
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ]);

        return response()->json([
            'success' => 1,
            'message' =>
                'Ambulance Trip Started Successfully',

            'data' =>
                new AmbulanceBookingCollection(
                    collect([$booking])
                )
        ]);
    }

    public function updateTripLocation(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'current_location' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $booking = AmbulanceBooking::where(
            'id',
            $request->booking_id
        )
            ->where(
                'ambulance_id',
                $ambulance->id
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Booking Not Found'
            ], 404);
        }

        if ($booking->booking_status != 'on_the_way') {
            return response()->json([
                'success' => 0,
                'message' => 'Trip Is Not In Progress'
            ]);
        }
        $ambulance->latitude =
            $request->latitude;

        $ambulance->longitude =
            $request->longitude;

        if ($request->filled('current_location')) {
            $ambulance->current_location =
                $request->current_location;
        }

        $ambulance->save();

        return response()->json([
            'success' => 1,
            'message' => 'Location Updated Successfully'
        ]);
    }

    public function completeTrip(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'booking_id' =>
                    'required|integer',

                'extra_charge' =>
                    'nullable|numeric|min:0',

                'notes' =>
                    'nullable|string|max:1000',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,

                'message' =>
                    $validator->errors()->first(),

                'errors' =>
                    $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Find Booking
            |--------------------------------------------------------------------------
            */

            $booking = AmbulanceBooking::where(
                'id',
                $request->booking_id
            )
                ->where(
                    'ambulance_id',
                    $ambulance->id
                )
                ->lockForUpdate()
                ->first();

            if (!$booking) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Ambulance Booking Not Found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            if (!in_array($booking->booking_status, ['on_the_way', 'patient_picked'])) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Only Ongoing Trips Can Be Completed'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Extra Charge
            |--------------------------------------------------------------------------
            */

            if ($request->has('extra_charge')) {

                $booking->extra_charge =
                    (float) $request->extra_charge;
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Total
            |--------------------------------------------------------------------------
            */

            $booking->total_amount =
                (float) $booking->base_amount
                +
                (float) $booking->distance_amount
                +
                (float) $booking->extra_charge
                +
                (float) $booking->tax
                -
                (float) $booking->discount;

            if ($booking->total_amount < 0) {

                $booking->total_amount = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            if ($request->has('notes')) {

                $booking->notes =
                    $request->notes;
            }

            /*
            |--------------------------------------------------------------------------
            | Complete
            |--------------------------------------------------------------------------
            */

            $booking->booking_status =
                'completed';

            $booking->completed_at =
                Carbon::now();

            $booking->save();

            /*
            |--------------------------------------------------------------------------
            | Ambulance Available
            |--------------------------------------------------------------------------
            */

            $ambulance->is_available = 1;

            $ambulance->save();

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            $booking->load([
                'customer',
                'familyMember',
                'ambulanceType',
                'ambulance'
            ]);

            return response()->json([
                'success' => 1,

                'message' =>
                    'Ambulance Trip Completed Successfully',

                'data' =>
                    new AmbulanceBookingCollection(
                        collect([$booking])
                    )
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' => 'Something Went Wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function activeTrip()
    {
        $ambulance = auth('sanctum')->user();
        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }
        $booking = AmbulanceBooking::with([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'ambulance_id',
                $ambulance->id
            )
            ->whereIn(
                'booking_status',
                [
                    'ambulance_assigned',
                    'on_the_way'
                ]
            )
            ->latest()
            ->first();
        if (!$booking) {

            return response()->json([
                'success' => 0,
                'message' => 'No Active Trip Found'
            ]);
        }
        return response()->json([
            'success' => 1,
            'message' => 'Active Trip Fetched Successfully',
            'data' => new AmbulanceBookingCollection(collect([$booking]))
        ]);
    }

    public function verifyPickupCode(Request $request)
    {
        $ambulance = auth('sanctum')->user();

        if (!$ambulance) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|integer',
            'pickup_code' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $booking = AmbulanceBooking::where('id', $request->booking_id)
            ->where('ambulance_id', $ambulance->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Booking Not Found'
            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | Verify Pickup Code
        |--------------------------------------------------------------------------
        */

        if ($booking->pickup_code !== $request->pickup_code) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Pickup Code'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Patient Picked
        |--------------------------------------------------------------------------
        */

        $booking->booking_status = 'patient_picked';
        $booking->save();

        /*
        |--------------------------------------------------------------------------
        | Relations
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Pickup Code Verified Successfully. Patient Picked.',
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            )
        ]);
    }
}