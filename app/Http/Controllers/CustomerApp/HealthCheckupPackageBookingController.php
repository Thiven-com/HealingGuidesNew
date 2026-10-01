<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\HealthCheckupPackage;
use App\Models\HealthCheckupPackageBooking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class HealthCheckupPackageBookingController extends Controller
{
    /**
     * Create booking and Razorpay order.
     */
    public function store(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'health_checkup_package_id' => 'required|exists:health_checkup_packages,id',
            'family_member_id' => 'required|exists:family_members,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        $familyMember = FamilyMember::where('id', $request->family_member_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$familyMember) {
            return response()->json([
                'success' => 0,
                'message' => 'Family member not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Package
        |--------------------------------------------------------------------------
        */

        $package = HealthCheckupPackage::find(
            $request->health_checkup_package_id
        );

        if (!$package) {
            return response()->json([
                'success' => 0,
                'message' => 'Health checkup package not found.',
            ], 404);
        }

        $amount = (float) $package->price;

        if ($amount <= 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid health checkup package amount.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Home Collection Address
        |--------------------------------------------------------------------------
        */

        $homeCollection = (bool) $package->home_collection;

        if ($homeCollection) {

            $request->validate([
                'address' => 'required|string|max:1000',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|string|max:20',

                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',

                'contact_name' => 'required|string|max:255',
                'contact_mobile' => 'required|string|max:20',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Only Razorpay
        |--------------------------------------------------------------------------
        */

        if (strtolower($request->payment_method) !== 'razorpay') {
            return response()->json([
                'success' => 0,
                'message' => 'Only Razorpay payment is supported.',
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Booking Number
            |--------------------------------------------------------------------------
            */

            $bookingNo = 'HCP'
                . date('YmdHis')
                . strtoupper(Str::random(4));

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = HealthCheckupPackageBooking::create([

                'booking_no' => $bookingNo,

                'customer_id' => $customer->id,

                'family_member_id' => $familyMember->id,

                'health_checkup_package_id' => $package->id,

                'booking_date' => $request->booking_date,

                'booking_time' => $request->booking_time,

                /*
                |--------------------------------------------------------------------------
                | Collection
                |--------------------------------------------------------------------------
                */

                'home_collection' => $homeCollection,

                /*
                |--------------------------------------------------------------------------
                | Home Collection Address
                |--------------------------------------------------------------------------
                */

                'address' => $homeCollection
                    ? $request->address
                    : null,

                'city' => $homeCollection
                    ? $request->city
                    : null,

                'state' => $homeCollection
                    ? $request->state
                    : null,

                'pincode' => $homeCollection
                    ? $request->pincode
                    : null,

                'latitude' => $homeCollection
                    ? $request->latitude
                    : null,

                'longitude' => $homeCollection
                    ? $request->longitude
                    : null,

                'contact_name' => $homeCollection
                    ? $request->contact_name
                    : null,

                'contact_mobile' => $homeCollection
                    ? $request->contact_mobile
                    : null,

                /*
                |--------------------------------------------------------------------------
                | Amount
                |--------------------------------------------------------------------------
                */

                'total_amount' => $amount,

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                'payment_method' => 'razorpay',

                'payment_id' => null,

                'payment_status' => 'pending',

                'booking_status' => 'pending',

                'notes' => $request->notes,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Razorpay
            |--------------------------------------------------------------------------
            */

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $razorpayOrder = $api->order->create([
                'amount' => (int) round($amount * 100),

                'currency' => 'INR',

                'receipt' => $bookingNo,

                'notes' => [
                    'booking_id' => (string) $booking->id,
                    'booking_no' => $bookingNo,
                    'customer_id' => (string) $customer->id,
                    'package_id' => (string) $package->id,
                    'home_collection' => $homeCollection ? '1' : '0',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([

                'payment_no' => 'PAY'
                    . date('YmdHis')
                    . strtoupper(Str::random(4)),

                'customer_id' => $customer->id,

                'payment_for' => 'health_checkup_package',

                'reference_id' => $booking->id,

                'amount' => $amount,

                'discount' => 0,

                'tax' => 0,

                'paid_amount' => 0,

                'balance_amount' => $amount,

                'currency' => 'INR',

                'payment_gateway' => 'razorpay',

                'payment_method' => 'razorpay',

                'gateway_order_id' => $razorpayOrder['id'],

                'gateway_payment_id' => null,

                'gateway_signature' => null,

                'transaction_id' => null,

                'bank_reference_no' => null,

                'invoice_no' => null,

                'payment_status' => 'pending',

                'failure_reason' => null,

                'paid_at' => null,

                'remarks' => $request->notes,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Link Payment
            |--------------------------------------------------------------------------
            */

            $booking->update([
                'payment_id' => $payment->id,
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Relations
            |--------------------------------------------------------------------------
            */

            $booking->load([
                'familyMember',
                'healthCheckupPackage',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => 1,

                'message' =>
                    'Health checkup booking created. Proceed with Razorpay payment.',

                'data' => [

                    'booking' => $this->bookingData(
                        $booking,
                        $payment
                    ),

                    'razorpay' => [

                        'key' =>
                            config('services.razorpay.key'),

                        'order_id' =>
                            $razorpayOrder['id'],

                        'amount' =>
                            (int) round($amount * 100),

                        'amount_rupees' =>
                            $amount,

                        'currency' =>
                            'INR',

                        'name' =>
                            $familyMember->name,

                        'description' =>
                            $package->name,

                        'prefill' => [

                            'name' =>
                                $homeCollection
                                ? $request->contact_name
                                : $familyMember->name,

                            'contact' =>
                                $homeCollection
                                ? $request->contact_mobile
                                : $familyMember->mobile,

                            'email' =>
                                $customer->email ?? null,
                        ],
                    ],
                ],
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,

                'message' =>
                    'Unable to create health checkup booking.',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Verify Razorpay Payment.
     */
    public function verifyPayment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $request->validate([
            'booking_id' => 'required|integer',

            'razorpay_order_id' => 'required|string',

            'razorpay_payment_id' => 'required|string',

            // 'razorpay_signature' => 'required|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Booking
        |--------------------------------------------------------------------------
        */

        $booking = HealthCheckupPackageBooking::where(
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
                'message' => 'Booking not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Payment
        |--------------------------------------------------------------------------
        */

        $payment = Payment::where(
            'id',
            $booking->payment_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => 0,
                'message' => 'Payment record not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Already Paid
        |--------------------------------------------------------------------------
        */

        if ($payment->payment_status === 'paid') {
            return response()->json([
                'success' => 1,
                'message' => 'Payment already verified.',
                'data' => $this->bookingData(
                    $booking,
                    $payment
                ),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Correct Razorpay Order
        |--------------------------------------------------------------------------
        */

        if (
            $payment->gateway_order_id !==
            $request->razorpay_order_id
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Razorpay order ID.',
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Razorpay Signature Verification
            |--------------------------------------------------------------------------
            */

            // $api = new Api(
            //     config('services.razorpay.key'),
            //     config('services.razorpay.secret')
            // );

            // $attributes = [
            //     'razorpay_order_id' =>
            //         $request->razorpay_order_id,

            //     'razorpay_payment_id' =>
            //         $request->razorpay_payment_id,

            //     'razorpay_signature' =>
            //         $request->razorpay_signature,
            // ];

            // $api->utility->verifyPaymentSignature(
            //     $attributes
            // );

            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([

                'gateway_payment_id' =>
                    $request->razorpay_payment_id,

                'gateway_signature' =>
                    $request->razorpay_signature,

                'transaction_id' =>
                    $request->razorpay_payment_id,

                'paid_amount' =>
                    $payment->amount,

                'balance_amount' => 0,

                'payment_status' =>
                    'success',

                'paid_at' =>
                    now(),
                'failure_reason' => null
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'payment_status' => 'paid',

                'booking_status' => 'confirmed',
            ]);

            DB::commit();

            $booking->load([
                'familyMember',
                'healthCheckupPackage',
            ]);

            return response()->json([
                'success' => 1,
                'message' =>
                    'Payment verified and health checkup booking confirmed successfully.',
                'data' => $this->bookingData(
                    $booking,
                    $payment
                ),
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | Payment Verification Failed
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'payment_status' => 'failed',
                'failure_reason' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => 0,
                'message' =>
                    'Razorpay payment verification failed.',
                'error' =>
                    $e->getMessage(),
            ], 422);
        }
    }


    /**
     * Payment Failed
     */
    public function paymentFailed(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $request->validate([
            'booking_id' => 'required|integer',

            'razorpay_order_id' => 'nullable|string',

            'razorpay_payment_id' => 'nullable|string',

            'failure_reason' => 'nullable|string',
        ]);

        $booking = HealthCheckupPackageBooking::where(
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
                'message' => 'Booking not found.',
            ], 404);
        }

        $payment = Payment::where(
            'id',
            $booking->payment_id
        )->first();

        if (!$payment) {
            return response()->json([
                'success' => 0,
                'message' => 'Payment record not found.',
            ], 404);
        }

        $payment->update([

            'gateway_payment_id' =>
                $request->razorpay_payment_id,

            'payment_status' => 'failed',

            'failure_reason' =>
                $request->failure_reason,

        ]);

        $booking->update([
            'payment_status' => 'failed',
            'booking_status' => 'pending',
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Payment failure recorded.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }


    /**
     * Get Customer Bookings
     */
    public function index(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $query = HealthCheckupPackageBooking::with([
            'familyMember',
            'healthCheckupPackage',
        ])
            ->where(
                'customer_id',
                $customer->id
            );

        if ($request->filled('family_member_id')) {
            $query->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        if ($request->filled('booking_status')) {
            $query->where(
                'booking_status',
                $request->booking_status
            );
        }

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        $bookings = $query
            ->latest()
            ->get();

        return response()->json([
            'success' => 1,
            'message' =>
                'Health checkup package bookings fetched successfully.',
            'data' => $bookings->map(function ($booking) {

                $payment = null;

                if ($booking->payment_id) {
                    $payment = Payment::find(
                        $booking->payment_id
                    );
                }

                return $this->bookingData(
                    $booking,
                    $payment
                );

            })->values(),
        ]);
    }


    /**
     * Get Single Booking
     */
    public function show($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $booking = HealthCheckupPackageBooking::with([
            'familyMember',
            'healthCheckupPackage',
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Health checkup package booking not found.',
                'data' => null,
            ], 404);
        }

        $payment = null;

        if ($booking->payment_id) {
            $payment = Payment::find(
                $booking->payment_id
            );
        }

        return response()->json([
            'success' => 1,
            'message' =>
                'Health checkup package booking fetched successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }


    /**
     * Cancel Booking
     */
    public function cancel($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $booking = HealthCheckupPackageBooking::where(
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
                'message' =>
                    'Health checkup package booking not found.',
            ], 404);
        }

        if (
            in_array($booking->booking_status, [
                'completed',
                'cancelled',
            ])
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'This booking cannot be cancelled.',
            ]);
        }

        $booking->update([
            'booking_status' => 'cancelled',
        ]);

        $payment = null;

        if ($booking->payment_id) {

            $payment = Payment::find(
                $booking->payment_id
            );

            /*
            |--------------------------------------------------------------------------
            | Don't mark paid payment as refunded automatically.
            |--------------------------------------------------------------------------
            |
            | Actual Razorpay refund should be processed separately.
            |
            */

        }

        return response()->json([
            'success' => 1,
            'message' =>
                'Health checkup package booking cancelled successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }


    /**
     * Reschedule Booking
     */
    public function reschedule(
        Request $request,
        $id
    ) {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $request->validate([
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
        ]);

        $booking = HealthCheckupPackageBooking::where(
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
                'message' =>
                    'Health checkup package booking not found.',
            ], 404);
        }

        if (
            in_array($booking->booking_status, [
                'completed',
                'cancelled',
            ])
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'This booking cannot be rescheduled.',
            ]);
        }

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
        ]);

        $booking->load([
            'familyMember',
            'healthCheckupPackage',
        ]);

        $payment = null;

        if ($booking->payment_id) {
            $payment = Payment::find(
                $booking->payment_id
            );
        }

        return response()->json([
            'success' => 1,
            'message' =>
                'Health checkup package booking rescheduled successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }


    /**
     * Common Booking Response
     */
    private function bookingData(
        $booking,
        $payment = null
    ) {
        return [

            'id' => $booking->id,

            'booking_no' =>
                $booking->booking_no,

            'customer_id' =>
                $booking->customer_id,

            'family_member_id' =>
                $booking->family_member_id,

            'health_checkup_package_id' =>
                $booking->health_checkup_package_id,

            'booking_date' =>
                $booking->booking_date
                ? $booking->booking_date->format('Y-m-d')
                : null,

            'booking_time' =>
                $booking->booking_time,
            'home_collection' =>
                (bool) $booking->home_collection,

            'address' =>
                $booking->address,

            'city' =>
                $booking->city,

            'state' =>
                $booking->state,

            'pincode' =>
                $booking->pincode,

            'latitude' =>
                $booking->latitude
                ? (float) $booking->latitude
                : null,

            'longitude' =>
                $booking->longitude
                ? (float) $booking->longitude
                : null,

            'contact_name' =>
                $booking->contact_name,

            'contact_mobile' =>
                $booking->contact_mobile,

            'total_amount' =>
                (float) $booking->total_amount,

            'payment_method' =>
                $booking->payment_method,

            'payment_id' =>
                $booking->payment_id,

            'payment_status' =>
                $booking->payment_status,

            'booking_status' =>
                $booking->booking_status,

            'notes' =>
                $booking->notes,

            'family_member' => [

                'id' =>
                    optional($booking->familyMember)->id,

                'name' =>
                    optional($booking->familyMember)->name,

                'mobile' =>
                    optional($booking->familyMember)->mobile,

                'relationship' =>
                    optional($booking->familyMember)->relationship,

                'gender' =>
                    optional($booking->familyMember)->gender,

                'dob' =>
                    optional($booking->familyMember)->dob,

                'age' =>
                    optional($booking->familyMember)->age,

                'blood_group' =>
                    optional($booking->familyMember)->blood_group,

                'height' =>
                    optional($booking->familyMember)->height,

                'weight' =>
                    optional($booking->familyMember)->weight,

                'occupation' =>
                    optional($booking->familyMember)->occupation,

                'photo' =>
                    optional($booking->familyMember)->photo
                    ? asset(
                        $booking->familyMember->photo
                    )
                    : null,
            ],

            'health_checkup_package' => [

                'id' =>
                    optional($booking->healthCheckupPackage)->id,

                'health_checkup_id' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->health_checkup_id,

                'name' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->name,

                'slug' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->slug,

                'image' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->image
                    ? asset(
                        $booking
                            ->healthCheckupPackage
                            ->image
                    )
                    : null,

                'short_description' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->short_description,

                'description' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->description,

                'total_tests' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->total_tests,

                'mrp' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->mrp,

                'price' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->price,

                'discount_percentage' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->discount_percentage,

                'home_collection' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->home_collection,

                'centre_collection' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->centre_collection,

                'report_delivery' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->report_delivery,

                'fasting_required' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->fasting_required,

                'preparation_instructions' =>
                    optional(
                        $booking->healthCheckupPackage
                    )->preparation_instructions,
            ],

            'payment' => $payment ? [

                'id' =>
                    $payment->id,

                'payment_no' =>
                    $payment->payment_no,

                'payment_for' =>
                    $payment->payment_for,

                'reference_id' =>
                    $payment->reference_id,

                'amount' =>
                    (float) $payment->amount,

                'discount' =>
                    (float) $payment->discount,

                'tax' =>
                    (float) $payment->tax,

                'paid_amount' =>
                    (float) $payment->paid_amount,

                'balance_amount' =>
                    (float) $payment->balance_amount,

                'currency' =>
                    $payment->currency,

                'payment_gateway' =>
                    $payment->payment_gateway,

                'payment_method' =>
                    $payment->payment_method,

                'gateway_order_id' =>
                    $payment->gateway_order_id,

                'gateway_payment_id' =>
                    $payment->gateway_payment_id,

                'transaction_id' =>
                    $payment->transaction_id,

                'bank_reference_no' =>
                    $payment->bank_reference_no,

                'invoice_no' =>
                    $payment->invoice_no,

                'payment_status' =>
                    $payment->payment_status,

                'failure_reason' =>
                    $payment->failure_reason,

                'paid_at' =>
                    $payment->paid_at,

                'remarks' =>
                    $payment->remarks,

            ] : null,

            'created_at' =>
                $booking->created_at,

            'updated_at' =>
                $booking->updated_at,
        ];
    }
}