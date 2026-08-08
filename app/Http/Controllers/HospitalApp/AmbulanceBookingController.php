<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceBookingCollection;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AmbulanceBookingController extends Controller
{
    public function bookings(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $bookings = AmbulanceBooking::with([
            'customer',
            'familyMember',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            );


        if ($request->filled('id')) {

            $bookings->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_no')) {

            $bookings->where(
                'booking_no',
                $request->booking_no
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_status')) {

            $bookings->where(
                'booking_status',
                $request->booking_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambulance Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_type_id')) {

            $bookings->where(
                'ambulance_type_id',
                $request->ambulance_type_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambulance
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ambulance_id')) {

            $bookings->where(
                'ambulance_id',
                $request->ambulance_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Emergency
        |--------------------------------------------------------------------------
        */

        if ($request->has('is_emergency')) {

            $bookings->where(
                'is_emergency',
                $request->is_emergency
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $bookings->where(
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

            $bookings->whereDate(
                'created_at',
                $request->date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $bookings->where(function ($query) use ($search) {

                $query->where(
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
                        'pickup_city',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'destination_address',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'destination_city',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'familyMember',
                        function ($q) use ($search) {

                            $q->where(
                                'name',
                                'LIKE',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }

        $bookings = $bookings
            ->latest()
            ->paginate(20);

        if ($bookings->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Ambulance Bookings Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Bookings Fetched Successfully',
            'data' => new AmbulanceBookingCollection($bookings)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ambulance Booking Details
    |--------------------------------------------------------------------------
    */

    public function bookingDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

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
                'hospital_id',
                $hospital->id
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
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Assign Ambulance
    |--------------------------------------------------------------------------
    */

    public function assignAmbulance(Request $request)
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
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|integer',

            'ambulance_id' =>
                'required|integer',

            'distance_km' =>
                'nullable|numeric|min:0',

            'extra_charge' =>
                'nullable|numeric|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'notes' =>
                'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            */

            $booking = AmbulanceBooking::where(
                'id',
                $request->booking_id
            )
                ->where(
                    'hospital_id',
                    $hospital->id
                )
                ->lockForUpdate()
                ->first();

            if (!$booking) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Ambulance Booking Not Found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Booking Status
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $booking->booking_status,
                    ['pending', 'requested']
                )
            ) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'This ambulance request cannot be assigned.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Find Hospital Ambulance
            |--------------------------------------------------------------------------
            */

            $ambulance = Ambulance::where(
                'id',
                $request->ambulance_id
            )
                ->where(
                    'hospital_id',
                    $hospital->id
                )
                ->where(
                    'status',
                    1
                )
                ->lockForUpdate()
                ->first();

            if (!$ambulance) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Ambulance Not Found'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Ambulance Type Check
            |--------------------------------------------------------------------------
            */

            if (
                $ambulance->ambulance_type_id !=
                $booking->ambulance_type_id
            ) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Selected ambulance does not match the requested ambulance type.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Availability Check
            |--------------------------------------------------------------------------
            */

            if (!$ambulance->is_available) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Selected Ambulance Is Not Available'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Fare
            |--------------------------------------------------------------------------
            */

            $distanceKm =
                $request->filled('distance_km')
                ? (float) $request->distance_km
                : (float) ($booking->distance_km ?? 0);

            $baseAmount =
                (float) ($ambulance->base_fare ?? 0);

            $pricePerKm =
                (float) ($ambulance->price_per_km ?? 0);

            $distanceAmount =
                $distanceKm * $pricePerKm;

            $extraCharge =
                (float) ($request->extra_charge ?? 0);

            $discount =
                (float) ($request->discount ?? 0);

            $tax =
                (float) ($request->tax ?? 0);

            $totalAmount =
                $baseAmount +
                $distanceAmount +
                $extraCharge +
                $tax -
                $discount;

            if ($totalAmount < 0) {
                $totalAmount = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->ambulance_id =
                $ambulance->id;

            $booking->distance_km =
                $distanceKm;

            $booking->base_amount =
                $baseAmount;

            $booking->price_per_km =
                $pricePerKm;

            $booking->distance_amount =
                $distanceAmount;

            $booking->extra_charge =
                $extraCharge;

            $booking->discount =
                $discount;

            $booking->tax =
                $tax;

            $booking->total_amount =
                $totalAmount;

            $booking->booking_status =
                'ambulance_assigned';

            $booking->accepted_at =
                Carbon::now();

            $booking->assigned_at =
                Carbon::now();

            if ($request->has('notes')) {

                $booking->notes =
                    $request->notes;
            }

            $booking->save();

            /*
            |--------------------------------------------------------------------------
            | Make Ambulance Unavailable
            |--------------------------------------------------------------------------
            */

            $ambulance->is_available = 0;

            $ambulance->save();

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Relations
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
                'message' => 'Ambulance Assigned Successfully',
                'data' => new AmbulanceBookingCollection(
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

    /*
    |--------------------------------------------------------------------------
    | Reject Ambulance Request
    |--------------------------------------------------------------------------
    */

    public function rejectRequest(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|integer',

            'cancel_reason' =>
                'required|string|max:1000',

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
                'hospital_id',
                $hospital->id
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
        | Only Pending Request Can Be Rejected
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $booking->booking_status,
                ['pending', 'requested']
            )
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This ambulance request cannot be rejected.'
            ]);
        }

        $booking->booking_status =
            'rejected';

        $booking->cancel_reason =
            $request->cancel_reason;

        $booking->cancelled_at =
            Carbon::now();

        $booking->save();

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Request Rejected Successfully',
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Start Ambulance Trip
    |--------------------------------------------------------------------------
    */

    public function startTrip(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|integer',

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
                'hospital_id',
                $hospital->id
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
        | Ambulance Must Be Assigned
        |--------------------------------------------------------------------------
        */

        if (!$booking->ambulance_id) {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance Has Not Been Assigned'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */

        if ($booking->booking_status != 'ambulance_assigned') {

            return response()->json([
                'success' => 0,
                'message' => 'Trip Cannot Be Started'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Start Trip
        |--------------------------------------------------------------------------
        */

        $booking->booking_status =
            'on_the_way';

        $booking->save();

        $booking->load([
            'familyMember',
            'ambulanceType',
            'ambulance'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance Trip Started Successfully',
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Complete Ambulance Trip
    |--------------------------------------------------------------------------
    */

    public function completeTrip(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|integer',

            'extra_charge' =>
                'nullable|numeric|min:0',

            'notes' =>
                'nullable|string|max:1000',

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------------------
            */

            $booking = AmbulanceBooking::where(
                'id',
                $request->booking_id
            )
                ->where(
                    'hospital_id',
                    $hospital->id
                )
                ->lockForUpdate()
                ->first();

            if (!$booking) {

                DB::rollBack();

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

            if ($booking->booking_status != 'on_the_way') {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Only Ongoing Trips Can Be Completed'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Update Extra Charge
            |--------------------------------------------------------------------------
            */

            if ($request->has('extra_charge')) {

                $booking->extra_charge =
                    $request->extra_charge;
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate Total
            |--------------------------------------------------------------------------
            */

            $booking->total_amount =
                (float) $booking->base_amount +
                (float) $booking->distance_amount +
                (float) $booking->extra_charge +
                (float) $booking->tax -
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
            | Make Ambulance Available Again
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
                        'is_available' => 1
                    ]);
            }

            DB::commit();

            $booking->load([
                'customer',
                'familyMember',
                'ambulanceType',
                'ambulance'
            ]);

            return response()->json([
                'success' => 1,
                'message' => 'Ambulance Trip Completed Successfully',
                'data' => new AmbulanceBookingCollection(
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
}