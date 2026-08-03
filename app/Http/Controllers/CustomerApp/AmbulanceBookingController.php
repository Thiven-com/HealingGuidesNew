<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceBookingCollection;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use App\Models\FamilyMember;
use App\Models\Hospital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmbulanceBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Request Ambulance
    |--------------------------------------------------------------------------
    */

    public function requestAmbulance(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
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

            'family_member_id' =>
                'required|exists:family_members,id',

            'hospital_id' =>
                'required|exists:hospitals,id',

            'ambulance_type_id' =>
                'required|exists:ambulance_types,id',

            'pickup_address' =>
                'required|string|max:1000',

            'pickup_city' =>
                'nullable|string|max:100',

            'pickup_state' =>
                'nullable|string|max:100',

            'pickup_pincode' =>
                'nullable|string|max:20',

            'pickup_latitude' =>
                'nullable|numeric|between:-90,90',

            'pickup_longitude' =>
                'nullable|numeric|between:-180,180',

            'is_emergency' =>
                'nullable|boolean',

            'emergency_notes' =>
                'nullable|string|max:1000',

            'notes' =>
                'nullable|string|max:1000',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        $familyMember = FamilyMember::where(
            'id',
            $request->family_member_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$familyMember) {
            return response()->json([
                'success' => 0,
                'message' => 'Family member not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'id',
            $request->hospital_id
        )
            ->where('status', 1)
            ->first();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Hospital not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Ambulance Type In Hospital
        |--------------------------------------------------------------------------
        */

        // $hasAmbulance = Ambulance::where(
        //     'hospital_id',
        //     $hospital->id
        // )
        //     ->where(
        //         'ambulance_type_id',
        //         $request->ambulance_type_id
        //     )
        //     ->where('status', 1)
        //     ->exists();

        // if (!$hasAmbulance) {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => 'Selected ambulance type is not available at this hospital.'
        //     ]);
        // }

        /*
        |--------------------------------------------------------------------------
        | Calculate Distance
        |--------------------------------------------------------------------------
        */

        $distanceKm = 0;

        if (
            $request->filled('pickup_latitude') &&
            $request->filled('pickup_longitude') &&
            !empty($hospital->latitude) &&
            !empty($hospital->longitude)
        ) {

            $distanceKm = $this->calculateDistance(
                $request->pickup_latitude,
                $request->pickup_longitude,
                $hospital->latitude,
                $hospital->longitude
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Ambulance Request
        |--------------------------------------------------------------------------
        */

        $booking = new AmbulanceBooking();

        $booking->booking_no =
            'AMB' . now()->format('YmdHis') . rand(100, 999);

        /*
        |--------------------------------------------------------------------------
        | Customer / Patient
        |--------------------------------------------------------------------------
        */

        $booking->customer_id =
            $customer->id;

        $booking->family_member_id =
            $familyMember->id;

        /*
        |--------------------------------------------------------------------------
        | Hospital / Ambulance Type
        |--------------------------------------------------------------------------
        */

        $booking->hospital_id =
            $hospital->id;

        $booking->ambulance_type_id =
            $request->ambulance_type_id;

        /*
        |--------------------------------------------------------------------------
        | Hospital Will Assign Actual Ambulance
        |--------------------------------------------------------------------------
        */

        $booking->ambulance_id = null;

        /*
        |--------------------------------------------------------------------------
        | Pickup
        |--------------------------------------------------------------------------
        */

        $booking->pickup_address =
            $request->pickup_address;

        $booking->pickup_city =
            $request->pickup_city;

        $booking->pickup_state =
            $request->pickup_state;

        $booking->pickup_pincode =
            $request->pickup_pincode;

        $booking->pickup_latitude =
            $request->pickup_latitude;

        $booking->pickup_longitude =
            $request->pickup_longitude;

        /*
        |--------------------------------------------------------------------------
        | Destination = Hospital
        |--------------------------------------------------------------------------
        */

        $booking->destination_address =
            $hospital->address ?? null;

        $booking->destination_city =
            $hospital->city ?? null;

        $booking->destination_state =
            $hospital->state ?? null;

        $booking->destination_pincode =
            $hospital->pincode ?? null;

        $booking->destination_latitude =
            $hospital->latitude ?? null;

        $booking->destination_longitude =
            $hospital->longitude ?? null;

        /*
        |--------------------------------------------------------------------------
        | Emergency
        |--------------------------------------------------------------------------
        */

        $booking->is_emergency =
            $request->boolean('is_emergency');

        $booking->emergency_notes =
            $request->emergency_notes;

        /*
        |--------------------------------------------------------------------------
        | Distance
        |--------------------------------------------------------------------------
        */

        $booking->distance_km = $distanceKm;

        /*
        |--------------------------------------------------------------------------
        | Fare
        |--------------------------------------------------------------------------
        | Actual fare is calculated after hospital assigns ambulance.
        |--------------------------------------------------------------------------
        */

        $booking->base_amount = 0;

        $booking->price_per_km = 0;

        $booking->distance_amount = 0;

        $booking->extra_charge = 0;

        $booking->discount = 0;

        $booking->tax = 0;

        $booking->total_amount = 0;

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $booking->booking_status =
            'pending';

        $booking->payment_status =
            'pending';

        $booking->notes =
            $request->notes;

        $booking->save();

        /*
        |--------------------------------------------------------------------------
        | Relations
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'familyMember',
            'hospital',
            'ambulanceType',
            'ambulance'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance request sent to hospital successfully.',
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | My Ambulance Bookings
    |--------------------------------------------------------------------------
    */

    public function myBookings(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $bookings = AmbulanceBooking::with([
            'familyMember',
            'hospital',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'customer_id',
                $customer->id
            );

        /*
        |--------------------------------------------------------------------------
        | ID Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {

            $bookings->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $bookings->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hospital Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hospital_id')) {

            $bookings->where(
                'hospital_id',
                $request->hospital_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambulance Type Filter
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
        | Booking Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_date')) {

            $bookings->whereDate(
                'booking_date',
                $request->booking_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_type')) {

            $bookings->where(
                'booking_type',
                $request->booking_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Emergency
        |--------------------------------------------------------------------------
        */

        if ($request->filled('is_emergency')) {

            $bookings->where(
                'is_emergency',
                $request->is_emergency
            );
        }

        $bookings = $bookings
            ->latest()
            ->paginate(20);

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No ambulance bookings found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new AmbulanceBookingCollection($bookings),
            'message' => 'Ambulance bookings fetched successfully.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Details
    |--------------------------------------------------------------------------
    */

    public function bookingDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $booking = AmbulanceBooking::with([
            'familyMember',
            'hospital',
            'ambulanceType',
            'ambulance'
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance booking not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new AmbulanceBookingCollection(
                collect([$booking])
            ),
            'message' => 'Ambulance booking fetched successfully.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Ambulance Booking
    |--------------------------------------------------------------------------
    */

    public function cancelBooking(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|exists:ambulance_bookings,id',

            'cancel_reason' =>
                'required|string|max:1000',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $booking = AmbulanceBooking::where(
            'id',
            $request->booking_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance booking not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Already Cancelled
        |--------------------------------------------------------------------------
        */

        if ($booking->booking_status === 'cancelled') {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance booking already cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cannot Cancel
        |--------------------------------------------------------------------------
        */

        if (
            in_array($booking->booking_status, [

                'on_the_way',

                'arrived',

                'patient_picked',

                'completed',

                'rejected'

            ])
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This ambulance booking cannot be cancelled.'
            ]);
        }

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
                ->update([
                    'is_available' => 1
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel
        |--------------------------------------------------------------------------
        */

        $booking->booking_status =
            'cancelled';

        $booking->cancel_reason =
            $request->cancel_reason;

        $booking->cancelled_at =
            now();

        $booking->save();

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance booking cancelled successfully.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Pay Ambulance Booking
    |--------------------------------------------------------------------------
    */

    public function payBooking(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|exists:ambulance_bookings,id',

            'payment_method' =>
                'required|string|max:50',

            'payment_id' =>
                'nullable|string|max:255',

            'transaction_id' =>
                'nullable|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

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
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Ambulance booking not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        if (
            in_array($booking->booking_status, [
                'cancelled',
                'rejected'
            ])
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Payment cannot be made for this booking.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambulance Must Be Assigned
        |--------------------------------------------------------------------------
        */

        if (!$booking->ambulance_id) {

            return response()->json([
                'success' => 0,
                'message' => 'Please wait until the hospital assigns an ambulance.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Fare Must Be Calculated
        |--------------------------------------------------------------------------
        */

        if ((float) $booking->total_amount <= 0) {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance fare has not been calculated yet.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Already Paid
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {

            return response()->json([
                'success' => 0,
                'message' => 'Ambulance booking already paid.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        |
        | Replace this with Razorpay verification when connecting your
        | actual payment gateway.
        |
        */

        $booking->payment_method =
            $request->payment_method;

        $booking->payment_id =
            $request->payment_id;

        $booking->transaction_id =
            $request->transaction_id;

        $booking->payment_status =
            'paid';

        $booking->save();

        return response()->json([
            'success' => 1,
            'message' => 'Ambulance booking payment completed successfully.'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Distance
    |--------------------------------------------------------------------------
    |
    | Haversine distance.
    | This is straight-line distance, not Google Maps road distance.
    |--------------------------------------------------------------------------
    */

    private function calculateDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo
    ) {
        $earthRadius = 6371;

        $latFrom = deg2rad($latitudeFrom);

        $lonFrom = deg2rad($longitudeFrom);

        $latTo = deg2rad($latitudeTo);

        $lonTo = deg2rad($longitudeTo);

        $latDelta =
            $latTo - $latFrom;

        $lonDelta =
            $lonTo - $lonFrom;

        $angle = 2 * asin(
            sqrt(
                pow(
                    sin($latDelta / 2),
                    2
                )
                +
                cos($latFrom)
                *
                cos($latTo)
                *
                pow(
                    sin($lonDelta / 2),
                    2
                )
            )
        );

        return round(
            $earthRadius * $angle,
            2
        );
    }
}