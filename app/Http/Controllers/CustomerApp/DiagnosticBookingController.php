<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosticBookingCollection;
use App\Models\Diagnostic;
use App\Models\DiagnosticBooking;
use App\Models\DiagnosticBookingItem;
use App\Models\DiagnosticLabTest;
use App\Models\FamilyMember;
use App\Models\LabTest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiagnosticBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Book Lab Test
    |--------------------------------------------------------------------------
    */

    public function bookLabTest(Request $request)
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

            'diagnostic_id' => 'required|exists:diagnostics,id',

            'family_member_id' => 'nullable|exists:family_members,id',

            'collection_type' => 'required|in:home_collection,lab_visit',

            'booking_date' => 'required|date|after_or_equal:today',

            'booking_time' => 'required',

            'lab_tests' => 'required|array|min:1',

            'lab_tests.*.lab_test_id' =>
                'required|exists:lab_tests,id|distinct',

            /*
            |--------------------------------------------------------------------------
            | Home Collection Address
            |--------------------------------------------------------------------------
            */

            'address' =>
                'required_if:collection_type,home_collection|nullable|string|max:500',

            'city' =>
                'required_if:collection_type,home_collection|nullable|string|max:100',

            'state' =>
                'required_if:collection_type,home_collection|nullable|string|max:100',

            'pincode' =>
                'required_if:collection_type,home_collection|nullable|string|max:10',

            'latitude' =>
                'nullable|numeric',

            'longitude' =>
                'nullable|numeric',

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
        | Check Booking Date & Time
        |--------------------------------------------------------------------------
        */

        try {

            $bookingDateTime = Carbon::parse(
                $request->booking_date . ' ' .
                $request->booking_time
            );

        } catch (\Exception $e) {

            return response()->json([
                'success' => 0,
                'message' => 'Invalid booking date or time.'
            ]);
        }

        if ($bookingDateTime->lte(now())) {

            return response()->json([
                'success' => 0,
                'message' => 'Please select a future date and time.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Family Member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

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
        }

        /*
        |--------------------------------------------------------------------------
        | Diagnostic Center
        |--------------------------------------------------------------------------
        */

        $diagnostic = Diagnostic::where(
            'id',
            $request->diagnostic_id
        )
            ->where('status', 1)
            ->first();

        if (!$diagnostic) {

            return response()->json([
                'success' => 0,
                'message' => 'Diagnostic center not available.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Diagnostic Home Collection
        |--------------------------------------------------------------------------
        */

        if (
            $request->collection_type == 'home_collection' &&
            !$diagnostic->home_collection
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Home collection is not available for this diagnostic center.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Lab Tests & Calculate Amount
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;

        $items = [];

        foreach ($request->lab_tests as $test) {

            /*
            |--------------------------------------------------------------------------
            | Lab Test
            |--------------------------------------------------------------------------
            */

            $labTest = LabTest::where(
                'id',
                $test['lab_test_id']
            )
                ->where('status', 1)
                ->first();

            if (!$labTest) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Selected lab test is not available.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Test Available At Selected Diagnostic
            |--------------------------------------------------------------------------
            */

            $diagnosticTest = DiagnosticLabTest::where(
                'diagnostic_id',
                $diagnostic->id
            )
                ->where(
                    'lab_test_id',
                    $labTest->id
                )
                ->where('status', 1)
                ->first();

            if (!$diagnosticTest) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        $labTest->test_name .
                        ' is not available at this diagnostic center.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Home Collection
            |--------------------------------------------------------------------------
            |
            | Check availability from diagnostic_lab_tests because the same
            | test may support home collection at one diagnostic but not another.
            |
            */

            if (
                $request->collection_type == 'home_collection' &&
                !$diagnosticTest->home_collection
            ) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Home collection is not available for ' .
                        $labTest->test_name . '.'
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Price
            |--------------------------------------------------------------------------
            */

            $price = (
                !is_null($diagnosticTest->offer_price) &&
                $diagnosticTest->offer_price > 0
            )
                ? $diagnosticTest->offer_price
                : $diagnosticTest->price;

            if (is_null($price)) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Price is not available for ' .
                        $labTest->test_name . '.'
                ]);
            }

            $subtotal += $price;

            $items[] = [

                'lab_test_id' =>
                    $labTest->id,

                'price' =>
                    $price,

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Home Collection Charge
        |--------------------------------------------------------------------------
        */

        $homeCollectionCharge = 0;

        if ($request->collection_type == 'home_collection') {

            $homeCollectionCharge =
                $diagnostic->home_collection_charge ?? 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $discount = 0;

        $tax = 0;

        $totalAmount =
            $subtotal
            + $homeCollectionCharge
            + $tax
            - $discount;

        /*
        |--------------------------------------------------------------------------
        | Create Booking
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            $booking = new DiagnosticBooking();

            /*
            |--------------------------------------------------------------------------
            | Booking Number
            |--------------------------------------------------------------------------
            */

            $lastBooking = DiagnosticBooking::lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastBooking ? ($lastBooking->id + 1) : 1;

            $booking->booking_no = 'LAB' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $booking->customer_id =
                $customer->id;

            $booking->family_member_id =
                $request->family_member_id;

            /*
            |--------------------------------------------------------------------------
            | Diagnostic
            |--------------------------------------------------------------------------
            */

            $booking->diagnostic_id =
                $diagnostic->id;

            /*
            |--------------------------------------------------------------------------
            | Collection
            |--------------------------------------------------------------------------
            */

            $booking->collection_type =
                $request->collection_type;

            $booking->booking_date =
                $request->booking_date;

            $booking->booking_time =
                $request->booking_time;

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            $booking->subtotal =
                $subtotal;

            $booking->home_collection_charge =
                $homeCollectionCharge;

            $booking->discount =
                $discount;

            $booking->tax =
                $tax;

            $booking->total_amount =
                $totalAmount;

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $booking->payment_status =
                'pending';

            $booking->booking_status =
                'pending';

            /*
            |--------------------------------------------------------------------------
            | Home Collection Address
            |--------------------------------------------------------------------------
            */

            // if ($request->collection_type == 'home_collection') {

            $booking->address =
                $request->address;

            $booking->city =
                $request->city;

            $booking->state =
                $request->state;

            $booking->pincode =
                $request->pincode;

            $booking->latitude =
                $request->latitude;

            $booking->longitude =
                $request->longitude;
            // }

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $booking->notes =
                $request->notes;

            $booking->save();

            /*
            |--------------------------------------------------------------------------
            | Create Booking Items
            |--------------------------------------------------------------------------
            */

            foreach ($items as $item) {

                $bookingItem =
                    new DiagnosticBookingItem();

                $bookingItem->diagnostic_booking_id =
                    $booking->id;

                $bookingItem->lab_test_id =
                    $item['lab_test_id'];

                $bookingItem->price =
                    $item['price'];

                $bookingItem->save();
            }
            NotificationService::send(
                'customer',
                $customer->id,
                'lab_test_booked',
                'Lab Test Booked',
                'Your lab test booking ' .
                $booking->booking_no .
                ' has been booked successfully at ' .
                $diagnostic->name .
                '.',
                'diagnostic_booking',
                $booking->id,
                'lab_booking_details',
                [
                    'booking_id' => $booking->id,

                    'booking_no' => $booking->booking_no,

                    'customer_id' => $customer->id,

                    'family_member_id' => $booking->family_member_id,

                    'diagnostic_id' => $diagnostic->id,

                    'diagnostic_name' => $diagnostic->diagnostic_name,

                    'collection_type' => $booking->collection_type,

                    'booking_date' => $booking->booking_date,

                    'booking_time' => $booking->booking_time,

                    'subtotal' => $booking->subtotal,

                    'home_collection_charge' =>
                        $booking->home_collection_charge,

                    'discount' => $booking->discount,

                    'tax' => $booking->tax,

                    'total_amount' => $booking->total_amount,

                    'payment_status' => $booking->payment_status,

                    'booking_status' => $booking->booking_status,
                ]
            );

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Booking Details
            |--------------------------------------------------------------------------
            */

            $booking->load([
                'diagnostic',
                'familyMember',
                'items.labTest'
            ]);

            return response()->json([

                'success' => 1,

                'message' =>
                    'Lab test booked successfully.',

                'data' =>
                    $booking

            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => 0,

                'message' =>
                    'Unable to book lab test.',

                'error' =>
                    $e->getMessage()

            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | My Lab Bookings
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

        /*
        |--------------------------------------------------------------------------
        | Bookings
        |--------------------------------------------------------------------------
        */

        $bookings = DiagnosticBooking::with([
            'diagnostic',
            'familyMember',
            'items.labTest'
        ])
            ->where('customer_id', $customer->id);

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
        | Booking Status Filter
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
        | Payment Status Filter
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
        | Booking Date Filter
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
        | Collection Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('collection_type')) {

            $bookings->where(
                'collection_type',
                $request->collection_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Bookings
        |--------------------------------------------------------------------------
        */

        $bookings = $bookings
            ->latest()
            ->paginate(20);

        if ($bookings->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Lab Bookings Found'
            ]);
        }

        return response()->json([

            'success' => 1,

            'data' => new DiagnosticBookingCollection(
                $bookings
            ),

            'message' => 'Lab bookings fetched successfully.'

        ]);
    }


    public function bookingDetails($id)
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
        | Booking Details
        |--------------------------------------------------------------------------
        */

        $booking = DiagnosticBooking::with([
            'diagnostic',
            'familyMember',
            'items.labTest'
        ])
            ->where('id', $id)
            ->where(
                'customer_id',
                $customer->id
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Not Found
        |--------------------------------------------------------------------------
        */

        if ($booking->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'Lab booking not found.'
            ]);
        }

        return response()->json([

            'success' => 1,

            'data' => new DiagnosticBookingCollection(
                $booking
            ),

            'message' =>
                'Lab booking details fetched successfully.'

        ]);
    }


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
                'required|exists:diagnostic_bookings,id',

            'payment_method' =>
                'required|in:cash,razorpay,stripe',

            'transaction_id' =>
                'nullable|string|max:255',

            'payment_id' =>
                'nullable|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $booking = DiagnosticBooking::where(
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
                'message' => 'Lab booking not found.'
            ]);
        }

        if ($booking->payment_status == 'paid') {
            return response()->json([
                'success' => 0,
                'message' => 'Payment already completed.'
            ]);
        }

        if ($booking->booking_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Cancelled booking cannot be paid.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Gateway Verification
        |--------------------------------------------------------------------------
        |
        | Verify Razorpay / Stripe here.
        |
        */

        $paymentVerified = true;

        if (!$paymentVerified) {
            return response()->json([
                'success' => 0,
                'message' => 'Payment verification failed.'
            ]);
        }

        $booking->payment_method =
            $request->payment_method;

        $booking->transaction_id =
            $request->transaction_id;

        $booking->payment_id =
            $request->payment_id;

        $booking->payment_status = 'paid';

        $booking->booking_status = 'confirmed';

        $booking->save();

        return response()->json([
            'success' => 1,
            'message' => 'Payment completed successfully.',
            'data' => $booking
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Cancel Booking
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

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'booking_id' =>
                'required|exists:diagnostic_bookings,id',

            'cancel_reason' =>
                'nullable|string|max:500',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Booking
        |--------------------------------------------------------------------------
        */

        $booking = DiagnosticBooking::where(
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
                'message' => 'Lab booking not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Already Cancelled
        |--------------------------------------------------------------------------
        */

        if ($booking->booking_status == 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Lab booking already cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Completed / Processing Status Check
        |--------------------------------------------------------------------------
        */

        if (
            in_array($booking->booking_status, [

                'sample_collected',

                'processing',

                'report_ready',

                'completed',

            ])
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This lab booking cannot be cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Date Time
        |--------------------------------------------------------------------------
        |
        | booking_date is already Carbon because model has:
        |
        | 'booking_date' => 'date'
        |
        |--------------------------------------------------------------------------
        */

        $bookingDate = $booking->booking_date->format('Y-m-d');

        $bookingTime = Carbon::parse(
            $booking->booking_time
        )->format('H:i:s');

        $bookingDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $bookingDate . ' ' . $bookingTime
        );

        /*
        |--------------------------------------------------------------------------
        | Past Booking Check
        |--------------------------------------------------------------------------
        */

        if ($bookingDateTime->lte(now())) {

            return response()->json([
                'success' => 0,
                'message' => 'Past lab bookings cannot be cancelled.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cancel Booking
        |--------------------------------------------------------------------------
        */

        $booking->booking_status = 'cancelled';

        $booking->cancel_reason =
            $request->cancel_reason;

        $booking->cancelled_at = now();

        $booking->save();

        return response()->json([

            'success' => 1,

            'message' =>
                'Lab booking cancelled successfully.',

            'data' => [

                'booking_id' =>
                    $booking->id,

                'booking_no' =>
                    $booking->booking_no,

                'booking_status' =>
                    $booking->booking_status,

                'cancel_reason' =>
                    $booking->cancel_reason,

                'cancelled_at' =>
                    $booking->cancelled_at,

            ]

        ]);
    }
}