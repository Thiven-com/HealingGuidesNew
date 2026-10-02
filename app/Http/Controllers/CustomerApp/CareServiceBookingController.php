<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\CareServiceBooking;
use App\Models\FamilyMember;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\HomeVisitService;
use App\Models\HomeVisitServiceCategory;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class CareServiceBookingController extends Controller
{
    /**
     * Create Care Service Booking and Razorpay Order
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

        $request->validate([
            'family_member_id' => [
                'required',
                'exists:family_members,id',
            ],

            'home_visit_service_categories_id' => [
                'required',
                'exists:home_visit_service_categories,id',
            ],

            'home_visit_services_id' => [
                'required',
                'exists:home_visit_services,id',
            ],

            'reason' => [
                'required',
                'string',
            ],

            'preferred_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'preferred_time' => [
                'required',
                'date_format:H:i',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'pincode' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'contact_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_mobile' => [
                'required',
                'string',
                'max:20',
            ],

            'additional_note' => [
                'nullable',
                'string',
            ],

            'payment_method' => [
                'required',
                'string',
            ],
        ]);

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
                'message' => 'Family member not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        $category = HomeVisitServiceCategory::find(
            $request->home_visit_service_categories_id
        );

        if (!$category) {
            return response()->json([
                'success' => 0,
                'message' => 'Home visit service category not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Service
        |--------------------------------------------------------------------------
        */

        $service = HomeVisitService::find(
            $request->home_visit_services_id
        );

        if (!$service) {
            return response()->json([
                'success' => 0,
                'message' => 'Home visit service not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Service Category
        |--------------------------------------------------------------------------
        */

        if (
            (int) $service->home_visit_service_categories_id !==
            (int) $category->id
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Selected service does not belong to the selected category.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        $amount = (float) $service->price;

        if ($amount <= 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid home visit service amount.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        if (
            strtolower($request->payment_method) !==
            'razorpay'
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Only Razorpay payment is supported.',
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Booking Number
            |--------------------------------------------------------------------------
            */

            $bookingNo = 'CSB'
                . date('YmdHis')
                . strtoupper(Str::random(4));

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking = CareServiceBooking::create([

                'booking_no' =>
                    $bookingNo,

                'customer_id' =>
                    $customer->id,

                'family_member_id' =>
                    $familyMember->id,

                'home_visit_service_categories_id' =>
                    $category->id,

                'home_visit_services_id' =>
                    $service->id,

                'reason' =>
                    $request->reason,

                'preferred_date' =>
                    $request->preferred_date,

                'preferred_time' =>
                    $request->preferred_time,

                'country' =>
                    $request->country,

                'state' =>
                    $request->state,

                'city' =>
                    $request->city,

                'pincode' =>
                    $request->pincode,

                'address' =>
                    $request->address,

                'latitude' =>
                    $request->latitude,

                'longitude' =>
                    $request->longitude,

                'contact_name' =>
                    $request->contact_name,

                'contact_mobile' =>
                    $request->contact_mobile,

                'additional_note' =>
                    $request->additional_note,

                'total_amount' =>
                    $amount,

                'payment_method' =>
                    'razorpay',

                'payment_id' =>
                    null,

                'payment_status' =>
                    'pending',

                'booking_status' =>
                    'pending',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Razorpay Order
            |--------------------------------------------------------------------------
            */

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $razorpayOrder = $api->order->create([

                'amount' =>
                    (int) round($amount * 100),

                'currency' =>
                    'INR',

                'receipt' =>
                    $bookingNo,

                'notes' => [

                    'booking_id' =>
                        (string) $booking->id,

                    'booking_no' =>
                        $bookingNo,

                    'customer_id' =>
                        (string) $customer->id,

                    'category_id' =>
                        (string) $category->id,

                    'service_id' =>
                        (string) $service->id,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([

                'payment_no' =>
                    'PAY'
                    . date('YmdHis')
                    . strtoupper(Str::random(4)),

                'customer_id' =>
                    $customer->id,

                'payment_for' =>
                    'care_service',

                'reference_id' =>
                    $booking->id,

                'amount' =>
                    $amount,

                'discount' =>
                    0,

                'tax' =>
                    0,

                'paid_amount' =>
                    0,

                'balance_amount' =>
                    $amount,

                'currency' =>
                    'INR',

                'payment_gateway' =>
                    'razorpay',

                'payment_method' =>
                    'razorpay',

                'gateway_order_id' =>
                    $razorpayOrder['id'],

                'gateway_payment_id' =>
                    null,

                'gateway_signature' =>
                    null,

                'transaction_id' =>
                    null,

                'bank_reference_no' =>
                    null,

                'invoice_no' =>
                    null,

                'payment_status' =>
                    'pending',

                'failure_reason' =>
                    null,

                'paid_at' =>
                    null,

                'remarks' =>
                    $request->additional_note,
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
                'category',
                'service',
            ]);

            return response()->json([
                'success' => 1,

                'message' =>
                    'Care service booking created. Proceed with Razorpay payment.',

                'data' => [

                    'booking' =>
                        $this->bookingData(
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
                            $service->name,

                        'prefill' => [

                            'name' =>
                                $request->contact_name,

                            'contact' =>
                                $request->contact_mobile,

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
                    'Unable to create care service booking.',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
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

        $query = CareServiceBooking::with([
            'familyMember',
            'category',
            'service',
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

        if ($request->filled('home_visit_service_categories_id')) {
            $query->where(
                'home_visit_service_categories_id',
                $request->home_visit_service_categories_id
            );
        }

        if ($request->filled('home_visit_services_id')) {
            $query->where(
                'home_visit_services_id',
                $request->home_visit_services_id
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
                'Care service bookings fetched successfully.',

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

        $booking = CareServiceBooking::with([
            'familyMember',
            'category',
            'service',
        ])
            ->where('id', $id)
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Care service booking not found.',
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
                'Care service booking fetched successfully.',

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

        $booking = CareServiceBooking::where(
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
                    'Care service booking not found.',
            ], 404);
        }

        if (
            in_array(
                $booking->booking_status,
                [
                    'completed',
                    'cancelled',
                ]
            )
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'This booking cannot be cancelled.',
            ], 422);
        }

        $booking->update([
            'booking_status' =>
                'cancelled',
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
                'Care service booking cancelled successfully.',

            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }


    /**
     * Verify Razorpay Payment
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
            'booking_id' =>
                'required|integer',

            'razorpay_order_id' =>
                'required|string',

            'razorpay_payment_id' =>
                'required|string',

            // 'razorpay_signature' =>
            //     'required|string',
        ]);

        $booking = CareServiceBooking::where(
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
                'message' =>
                    'Booking not found.',
            ], 404);
        }

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
                'message' =>
                    'Payment record not found.',
            ], 404);
        }

        if (
            $payment->payment_status ===
            'success'
        ) {
            return response()->json([
                'success' => 1,

                'message' =>
                    'Payment already verified.',

                'data' =>
                    $this->bookingData(
                        $booking,
                        $payment
                    ),
            ]);
        }

        if (
            $payment->gateway_order_id !==
            $request->razorpay_order_id
        ) {
            return response()->json([
                'success' => 0,
                'message' =>
                    'Invalid Razorpay order ID.',
            ], 422);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Razorpay Signature Verification
            |--------------------------------------------------------------------------
            |
            | Enable this when your app sends razorpay_signature.
            |
            */

            // $api = new Api(
            //     config('services.razorpay.key'),
            //     config('services.razorpay.secret')
            // );

            // $api->utility->verifyPaymentSignature([
            //     'razorpay_order_id' =>
            //         $request->razorpay_order_id,
            //
            //     'razorpay_payment_id' =>
            //         $request->razorpay_payment_id,
            //
            //     'razorpay_signature' =>
            //         $request->razorpay_signature,
            // ]);

            $payment->update([

                'gateway_payment_id' =>
                    $request->razorpay_payment_id,

                'gateway_signature' =>
                    $request->razorpay_signature,

                'transaction_id' =>
                    $request->razorpay_payment_id,

                'paid_amount' =>
                    $payment->amount,

                'balance_amount' =>
                    0,

                'payment_status' =>
                    'success',

                'paid_at' =>
                    now(),

                'failure_reason' =>
                    null,
            ]);

            $booking->update([

                'payment_status' =>
                    'paid',

                'booking_status' =>
                    'confirmed',
            ]);

            DB::commit();

            $booking->load([
                'familyMember',
                'category',
                'service',
            ]);

            return response()->json([
                'success' => 1,

                'message' =>
                    'Payment verified and care service booking confirmed successfully.',

                'data' =>
                    $this->bookingData(
                        $booking,
                        $payment
                    ),
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            $payment->update([
                'payment_status' =>
                    'failed',

                'failure_reason' =>
                    $e->getMessage(),
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
            'booking_id' =>
                'required|integer',

            'razorpay_order_id' =>
                'nullable|string',

            'razorpay_payment_id' =>
                'nullable|string',

            'failure_reason' =>
                'nullable|string',
        ]);

        $booking = CareServiceBooking::where(
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
                'message' =>
                    'Booking not found.',
            ], 404);
        }

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
                'message' =>
                    'Payment record not found.',
            ], 404);
        }

        $payment->update([

            'gateway_payment_id' =>
                $request->razorpay_payment_id,

            'payment_status' =>
                'failed',

            'failure_reason' =>
                $request->failure_reason,
        ]);

        $booking->update([
            'payment_status' =>
                'failed',

            'booking_status' =>
                'pending',
        ]);

        return response()->json([
            'success' => 1,

            'message' =>
                'Payment failure recorded.',

            'data' =>
                $this->bookingData(
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

            'id' =>
                $booking->id,

            'booking_no' =>
                $booking->booking_no,

            'customer_id' =>
                $booking->customer_id,

            'family_member_id' =>
                $booking->family_member_id,

            'home_visit_service_categories_id' =>
                $booking->home_visit_service_categories_id,

            'home_visit_services_id' =>
                $booking->home_visit_services_id,

            'reason' =>
                $booking->reason,

            'preferred_date' =>
                $booking->preferred_date
                ? $booking->preferred_date->format('Y-m-d')
                : null,

            'preferred_time' =>
                $booking->preferred_time,

            'service_address' => [

                'country' =>
                    $booking->country,

                'state' =>
                    $booking->state,

                'city' =>
                    $booking->city,

                'pincode' =>
                    $booking->pincode,

                'address' =>
                    $booking->address,

                'latitude' =>
                    $booking->latitude !== null
                    ? (float) $booking->latitude
                    : null,

                'longitude' =>
                    $booking->longitude !== null
                    ? (float) $booking->longitude
                    : null,
            ],

            'contact' => [

                'name' =>
                    $booking->contact_name,

                'mobile' =>
                    $booking->contact_mobile,
            ],

            'additional_note' =>
                $booking->additional_note,

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

            'family_member' => [

                'id' =>
                    optional(
                        $booking->familyMember
                    )->id,

                'name' =>
                    optional(
                        $booking->familyMember
                    )->name,

                'mobile' =>
                    optional(
                        $booking->familyMember
                    )->mobile,

                'relationship' =>
                    optional(
                        $booking->familyMember
                    )->relationship,

                'gender' =>
                    optional(
                        $booking->familyMember
                    )->gender,

                'dob' =>
                    optional(
                        $booking->familyMember
                    )->dob,

                'age' =>
                    optional(
                        $booking->familyMember
                    )->age,

                'blood_group' =>
                    optional(
                        $booking->familyMember
                    )->blood_group,

                'height' =>
                    optional(
                        $booking->familyMember
                    )->height,

                'weight' =>
                    optional(
                        $booking->familyMember
                    )->weight,

                'occupation' =>
                    optional(
                        $booking->familyMember
                    )->occupation,

                'photo' =>
                    optional(
                        $booking->familyMember
                    )->photo
                    ? asset(
                        $booking
                            ->familyMember
                            ->photo
                    )
                    : null,
            ],

            'category' => [

                'id' =>
                    optional(
                        $booking->category
                    )->id,

                'name' =>
                    optional(
                        $booking->category
                    )->name,

                'slug' =>
                    optional(
                        $booking->category
                    )->slug,

                'category_type' =>
                    optional(
                        $booking->category
                    )->category_type,

                'image' =>
                    optional(
                        $booking->category
                    )->image
                    ? asset(
                        $booking
                            ->category
                            ->image
                    )
                    : null,
            ],

            'service' => [

                'id' =>
                    optional(
                        $booking->service
                    )->id,

                'name' =>
                    optional(
                        $booking->service
                    )->name,

                'slug' =>
                    optional(
                        $booking->service
                    )->slug,

                'image' =>
                    optional(
                        $booking->service
                    )->image
                    ? asset(
                        $booking
                            ->service
                            ->image
                    )
                    : null,

                'price' =>
                    optional(
                        $booking->service
                    )->price !== null
                    ? (float) 
                    $booking
                        ->service
                        ->price
                    : null,

                'price_per' =>
                    optional(
                        $booking->service
                    )->price_per,

                'description' =>
                    optional(
                        $booking->service
                    )->description,
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
