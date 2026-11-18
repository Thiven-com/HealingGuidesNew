<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AmbulanceBookingCollection;
use App\Models\Ambulance;
use App\Models\AmbulanceBooking;
use App\Models\FamilyMember;
use App\Models\Hospital;
use App\Services\NotificationService;
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

        NotificationService::send(
            'customer',
            $customer->id,
            'ambulance',
            $booking->is_emergency
            ? 'Emergency Ambulance Request Submitted'
            : 'Ambulance Request Submitted',
            'Your ' .
            ($booking->is_emergency ? 'emergency ' : '') .
            'ambulance request has been successfully submitted to ' .
            $hospital->hospital_name .
            '. Your booking number is ' . $booking->booking_no .
            '. Please wait while the hospital reviews your request.',
            'ambulance_booked',
            $booking->id,
            'ambulance_booking_details',
            [
                'booking_id' => $booking->id,
                'booking_no' => $booking->booking_no,
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->hospital_name,
                'ambulance_type_id' => $booking->ambulance_type_id,
                'is_emergency' => (bool) $booking->is_emergency,
                'booking_status' => $booking->booking_status,
            ]
        );

        /*
|--------------------------------------------------------------------------
| Send Notification To Hospital
|--------------------------------------------------------------------------
*/

        if ($booking->hospital_id) {

            NotificationService::send(
                'hospital',
                $booking->hospital_id,
                'ambulance',
                'New Ambulance Request',
                'A new ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance request has been received from ' .
                $customer->name .
                '. Pickup Location: ' . $booking->pickup_address .
                '. Booking No: ' . $booking->booking_no .
                '. Please review the booking and take the necessary action.',
                'ambulance_booked',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'customer_id' => $customer->id,
                    'hospital_id' => $hospital->id,
                    'ambulance_type_id' => $booking->ambulance_type_id,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'booking_status' => $booking->booking_status,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Send Notification To Ambulance If Assigned
        |--------------------------------------------------------------------------
        */

        if ($booking->ambulance_id) {

            NotificationService::send(
                'ambulance',
                $booking->ambulance_id,
                'ambulance',
                'New Ambulance Booking',
                'A new ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance booking has been assigned to you. Pickup Location: ' .
                $booking->pickup_address .
                '. Destination: ' . $booking->destination_address .
                '. Booking No: ' . $booking->booking_no .
                '. Please review the booking details and proceed accordingly.',
                'ambulance_booked',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'ambulance_id' => $booking->ambulance_id,
                    'hospital_id' => $booking->hospital_id,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'booking_status' => $booking->booking_status,
                ]
            );
        }

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

        NotificationService::send(
            'customer',
            $customer->id,
            'ambulance',
            'Ambulance Booking Cancelled',
            'Your ' .
            ($booking->is_emergency ? 'emergency ' : '') .
            'ambulance booking has been cancelled successfully. Booking No: ' .
            $booking->booking_no .
            '. Hospital: ' .
            ($booking->hospital->hospital_name ?? 'N/A') .
            '. Pickup Location: ' . $booking->pickup_address .
            '. Destination: ' . $booking->destination_address .
            '. Cancellation Reason: ' .
            ($booking->cancel_reason ?: 'Not specified') .
            '. If you still require ambulance service, please submit a new booking.',
            'ambulance_booking_cancelled',
            $booking->id,
            'ambulance_booking_details',
            [
                'booking_id' => $booking->id,
                'booking_no' => $booking->booking_no,

                'hospital_id' => $booking->hospital_id,
                'hospital_name' => $booking->hospital->hospital_name ?? null,

                'ambulance_type_id' => $booking->ambulance_type_id,
                'ambulance_id' => $booking->ambulance_id,

                'booking_status' => $booking->booking_status,

                'cancel_reason' => $booking->cancel_reason,
                'cancelled_at' => $booking->cancelled_at,
            ]
        );

        if ($booking->hospital_id) {

            NotificationService::send(
                'hospital',
                $booking->hospital_id,
                'ambulance',
                'Ambulance Booking Cancelled',
                'The ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance booking has been cancelled by ' .
                $customer->name .
                '. Booking No: ' . $booking->booking_no .
                '. Pickup Location: ' . $booking->pickup_address .
                '. Destination: ' . $booking->destination_address .
                '. Cancellation Reason: ' .
                ($booking->cancel_reason ?: 'Not specified') .
                '. Please update your records accordingly.',
                'ambulance_booking_cancelled',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'hospital_id' => $booking->hospital_id,
                    'hospital_name' => $booking->hospital->hospital_name ?? null,
                    'ambulance_type_id' => $booking->ambulance_type_id,
                    'ambulance_id' => $booking->ambulance_id,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'booking_status' => $booking->booking_status,
                    'cancel_reason' => $booking->cancel_reason,
                    'cancelled_at' => $booking->cancelled_at,
                ]
            );
        }

        if ($booking->ambulance_id) {

            NotificationService::send(
                'ambulance',
                $booking->ambulance_id,
                'ambulance',
                'Ambulance Booking Cancelled',
                'The ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance booking assigned to you has been cancelled by the customer ' .
                $customer->name .
                '. Booking No: ' . $booking->booking_no .
                '. Pickup Location: ' . $booking->pickup_address .
                '. Destination: ' . $booking->destination_address .
                '. Cancellation Reason: ' .
                ($booking->cancel_reason ?: 'Not specified') .
                '. You are no longer required to attend this booking.',
                'ambulance_booking_cancelled',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'ambulance_id' => $booking->ambulance_id,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'hospital_id' => $booking->hospital_id,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'booking_status' => $booking->booking_status,
                    'cancel_reason' => $booking->cancel_reason,
                    'cancelled_at' => $booking->cancelled_at,
                ]
            );
        }

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

        NotificationService::send(
            'customer',
            $customer->id,
            'ambulance',
            'Ambulance Payment Successful',
            'Your payment for the ' .
            ($booking->is_emergency ? 'emergency ' : '') .
            'ambulance booking has been completed successfully. Booking No: ' .
            $booking->booking_no .
            '. Hospital: ' .
            ($booking->hospital->hospital_name ?? 'N/A') .
            '. Amount Paid: ₹' . number_format((float) $booking->total_amount, 2) .
            '. Payment Method: ' . $booking->payment_method .
            '. Transaction ID: ' . ($booking->transaction_id ?: 'N/A') .
            '. Your payment is confirmed. Thank you for choosing our ambulance service.',
            'ambulance_booking_payment_successful',
            $booking->id,
            'ambulance_booking_details',
            [
                'booking_id' => $booking->id,
                'booking_no' => $booking->booking_no,

                'hospital_id' => $booking->hospital_id,
                'hospital_name' => $booking->hospital->hospital_name ?? null,

                'ambulance_id' => $booking->ambulance_id,

                'total_amount' => $booking->total_amount,
                'payment_method' => $booking->payment_method,
                'payment_id' => $booking->payment_id,
                'transaction_id' => $booking->transaction_id,

                'payment_status' => $booking->payment_status,
                'booking_status' => $booking->booking_status,
            ]
        );

        if ($booking->hospital_id) {

            NotificationService::send(
                'hospital',
                $booking->hospital_id,
                'ambulance',
                'Ambulance Booking Payment Received',
                'The payment for the ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance booking has been completed successfully by ' .
                $customer->name .
                '. Booking No: ' . $booking->booking_no .
                '. Amount Received: ₹' . number_format((float) $booking->total_amount, 2) .
                '. Payment Method: ' . $booking->payment_method .
                '. Transaction ID: ' . ($booking->transaction_id ?: 'N/A') .
                '. Please review the booking details and proceed with the ambulance service.',
                'ambulance_booking_payment_successful',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'hospital_id' => $booking->hospital_id,
                    'hospital_name' => $booking->hospital->hospital_name ?? null,
                    'ambulance_id' => $booking->ambulance_id,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'total_amount' => (float) $booking->total_amount,
                    'payment_method' => $booking->payment_method,
                    'payment_id' => $booking->payment_id,
                    'transaction_id' => $booking->transaction_id,
                    'payment_status' => $booking->payment_status,
                    'booking_status' => $booking->booking_status,
                ]
            );
        }

        if ($booking->ambulance_id) {

            NotificationService::send(
                'ambulance',
                $booking->ambulance_id,
                'ambulance',
                'Ambulance Booking Payment Confirmed',
                'The payment for the ' .
                ($booking->is_emergency ? 'emergency ' : '') .
                'ambulance booking assigned to you has been completed successfully. Booking No: ' .
                $booking->booking_no .
                '. Pickup Location: ' . $booking->pickup_address .
                '. Destination: ' . $booking->destination_address .
                '. Payment Status: ' . ucfirst($booking->payment_status) .
                '. Please proceed with the assigned ambulance service.',
                'ambulance_booking_payment_successful',
                $booking->id,
                'ambulance_booking_details',
                [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'ambulance_id' => $booking->ambulance_id,
                    'hospital_id' => $booking->hospital_id,
                    'pickup_address' => $booking->pickup_address,
                    'destination_address' => $booking->destination_address,
                    'is_emergency' => (bool) $booking->is_emergency,
                    'total_amount' => (float) $booking->total_amount,
                    'payment_method' => $booking->payment_method,
                    'payment_id' => $booking->payment_id,
                    'transaction_id' => $booking->transaction_id,
                    'payment_status' => $booking->payment_status,
                    'booking_status' => $booking->booking_status,
                ]
            );
        }

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