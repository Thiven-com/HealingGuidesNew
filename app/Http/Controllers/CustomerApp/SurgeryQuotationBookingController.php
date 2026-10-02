<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurgeryQuotationBookingCollection;
use App\Models\FamilyMember;
use App\Models\Payment;
use App\Models\SurgeryQuotation;
use App\Models\SurgeryQuotationBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class SurgeryQuotationBookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Book Approved Quotation
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $request->validate([

            'surgery_quotation_id' =>
                'required|integer|exists:surgery_quotations,id',

            'family_member_id' =>
                'required|integer|exists:family_members,id',

            'booking_date' =>
                'required|date',

            'booking_time' =>
                'required|date_format:H:i',

            'payment_method' =>
                'required|string',

            'notes' =>
                'nullable|string|max:2000',
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
        | Quotation
        |--------------------------------------------------------------------------
        */

        $quotation = SurgeryQuotation::with([
            'hospital',
            'quotationRequest',
            'quotationRequest.surgery',
        ])->find($request->surgery_quotation_id);

        if (!$quotation) {

            return response()->json([
                'success' => 0,
                'message' => 'Quotation not found.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Request Belongs To Customer
        |--------------------------------------------------------------------------
        */

        $quotationRequest =
            $quotation->quotationRequest;

        if (!$quotationRequest) {

            return response()->json([
                'success' => 0,
                'message' => 'Quotation request not found.',
            ], 404);
        }

        if ($quotationRequest->customer_id != $customer->id) {

            return response()->json([
                'success' => 0,
                'message' => 'This quotation does not belong to you.',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Only Approved Quotation
        |--------------------------------------------------------------------------
        */

        if ($quotation->status !== 'accepted') {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Only accepted quotations can be booked.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Existing Booking
        |--------------------------------------------------------------------------
        */

        $existingBooking = SurgeryQuotationBooking::where(
            'surgery_quotation_id',
            $quotation->id
        )
            ->whereNotIn(
                'booking_status',
                ['cancelled', 'rejected']
            )
            ->first();

        if ($existingBooking) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'This quotation has already been booked.',
                'data' => [
                    'booking_no' =>
                        $existingBooking->booking_no,
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        $amount = (float) $quotation->amount;

        $discount = (float) $quotation->discount;

        $tax = (float) $quotation->tax;

        $totalAmount =
            (float) $quotation->total_amount;

        if ($totalAmount <= 0) {

            $totalAmount =
                $amount
                + $tax
                - $discount;
        }

        if ($totalAmount < 0) {

            $totalAmount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        if (
            strtolower($request->payment_method)
            !== 'razorpay'
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

            $bookingNo =
                'SQB'
                . date('YmdHis')
                . strtoupper(Str::random(4));

            /*
            |--------------------------------------------------------------------------
            | Hospital Address
            |--------------------------------------------------------------------------
            */

            $hospitalAddress =
                optional($quotation->hospital)
                    ->address;

            /*
            |--------------------------------------------------------------------------
            | Create Booking
            |--------------------------------------------------------------------------
            */

            $booking =
                SurgeryQuotationBooking::create([

                    'booking_no' =>
                        $bookingNo,

                    'customer_id' =>
                        $customer->id,

                    'family_member_id' =>
                        $familyMember->id,

                    'surgery_quotation_request_id' =>
                        $quotation->surgery_quotation_request_id,

                    'surgery_quotation_id' =>
                        $quotation->id,

                    'surgery_id' =>
                        optional(
                            $quotationRequest->surgery
                        )->id,

                    'hospital_id' =>
                        $quotation->hospital_id,

                    'amount' =>
                        $amount,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'total_amount' =>
                        $totalAmount,

                    'payment_method' =>
                        'razorpay',

                    'payment_id' =>
                        null,

                    'transaction_id' =>
                        null,

                    'payment_status' =>
                        'pending',

                    'booking_date' =>
                        $request->booking_date,

                    'booking_time' =>
                        $request->booking_time,

                    'booking_status' =>
                        'pending',

                    'hospital_address' =>
                        $hospitalAddress,

                    'notes' =>
                        $request->notes,
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

            $razorpayOrder =
                $api->order->create([

                    'amount' =>
                        (int) round(
                            $totalAmount * 100
                        ),

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

                        'quotation_id' =>
                            (string) $quotation->id,

                        'hospital_id' =>
                            (string) $quotation->hospital_id,
                    ],
                ]);

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            $payment =
                Payment::create([

                    'payment_no' =>
                        'PAY'
                        . date('YmdHis')
                        . strtoupper(
                            Str::random(4)
                        ),

                    'customer_id' =>
                        $customer->id,

                    'payment_for' =>
                        'surgery_quotation_booking',

                    'reference_id' =>
                        $booking->id,

                    'amount' =>
                        $totalAmount,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'paid_amount' =>
                        0,

                    'balance_amount' =>
                        $totalAmount,

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
                        $request->notes,
                ]);

            /*
            |--------------------------------------------------------------------------
            | Link Payment
            |--------------------------------------------------------------------------
            */

            $booking->update([

                'payment_id' =>
                    $payment->id,
            ]);

            DB::commit();

            $booking->load([
                'customer',
                'familyMember',
                'quotation',
                'hospital',
                'surgery',
                'payment',
            ]);

            return response()->json([

                'success' => 1,

                'message' =>
                    'Surgery quotation booking created. Proceed with Razorpay payment.',

                'data' => [

                    'booking' =>
                        $this->bookingData(
                            $booking
                        ),

                    'razorpay' => [

                        'key' =>
                            config(
                                'services.razorpay.key'
                            ),

                        'order_id' =>
                            $razorpayOrder['id'],

                        'amount' =>
                            (int) round(
                                $totalAmount * 100
                            ),

                        'amount_rupees' =>
                            $totalAmount,

                        'currency' =>
                            'INR',

                        'name' =>
                            $familyMember->name,

                        'description' =>
                            'Surgery Booking - '
                            . optional(
                                $quotation->hospital
                            )->hospital_name,

                        'prefill' => [

                            'name' =>
                                $familyMember->name,

                            'contact' =>
                                $familyMember->mobile,

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
                    'Unable to create surgery booking.',

                'error' =>
                    $e->getMessage(),

            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $request->validate([
            'booking_id' => 'required|integer|exists:surgery_quotation_bookings,id',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Find Booking
            |--------------------------------------------------------------------------
            */

            $booking = SurgeryQuotationBooking::where('id', $request->booking_id)
                ->where('customer_id', $customer->id)
                ->lockForUpdate()
                ->first();

            if (!$booking) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Surgery quotation booking not found.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Already Paid
            |--------------------------------------------------------------------------
            */

            if ($booking->payment_status === 'paid') {

                DB::rollBack();

                return response()->json([
                    'success' => 1,
                    'message' => 'Payment already verified.',
                    'data' => $this->bookingData($booking),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Find Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::where('id', $booking->payment_id)
                ->where('customer_id', $customer->id)
                ->where('payment_for', 'surgery_quotation_booking')
                ->lockForUpdate()
                ->first();

            if (!$payment) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Payment record not found.',
                ], 404);
            }
            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );
            /*
            |--------------------------------------------------------------------------
            | Verify Order ID
            |--------------------------------------------------------------------------
            */

            if ($payment->gateway_order_id !== $request->razorpay_order_id) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid Razorpay order ID.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Fetch Payment From Razorpay
            |--------------------------------------------------------------------------
            */

            $razorpayPayment = $api->payment
                ->fetch($request->razorpay_payment_id);

            /*
            |--------------------------------------------------------------------------
            | Verify Razorpay Order ID From Payment
            |--------------------------------------------------------------------------
            */

            if (
                isset($razorpayPayment->order_id) &&
                $razorpayPayment->order_id !== $request->razorpay_order_id
            ) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' => 'Payment does not belong to this Razorpay order.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Payment Status
            |--------------------------------------------------------------------------
            */

            if (
                !in_array(
                    $razorpayPayment->status,
                    ['authorized', 'captured']
                )
            ) {

                $payment->update([
                    'gateway_payment_id' => $request->razorpay_payment_id,
                    'payment_status' => 'failed',
                    'failure_reason' =>
                        'Razorpay payment status: '
                        . $razorpayPayment->status,
                ]);

                $booking->update([
                    'payment_status' => 'failed',
                ]);

                DB::commit();

                return response()->json([
                    'success' => 0,
                    'message' => 'Razorpay payment was not successful.',
                    'payment_status' => $razorpayPayment->status,
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Amount Verification
            |--------------------------------------------------------------------------
            */

            $expectedAmount = (int) round(
                (float) $booking->total_amount * 100
            );

            $paidAmount = (int) $razorpayPayment->amount;

            if ($expectedAmount !== $paidAmount) {

                $payment->update([
                    'gateway_payment_id' => $request->razorpay_payment_id,
                    'payment_status' => 'failed',
                    'failure_reason' => 'Payment amount mismatch.',
                ]);

                $booking->update([
                    'payment_status' => 'failed',
                ]);

                DB::commit();

                return response()->json([
                    'success' => 0,
                    'message' => 'Payment amount mismatch.',
                    'expected_amount' => $expectedAmount,
                    'paid_amount' => $paidAmount,
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Payment Details
            |--------------------------------------------------------------------------
            */

            $transactionId =
                $razorpayPayment->acquirer_data->rrn
                ?? $razorpayPayment->id;

            /*
            |--------------------------------------------------------------------------
            | Update Payment
            |--------------------------------------------------------------------------
            */

            $payment->update([
                'gateway_payment_id' => $request->razorpay_payment_id,

                'transaction_id' => $transactionId,

                'paid_amount' => (float) $booking->total_amount,

                'balance_amount' => 0,

                'payment_status' => 'paid',

                'paid_at' => now(),

                'failure_reason' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Booking
            |--------------------------------------------------------------------------
            */

            $booking->update([
                'payment_status' => 'paid',

                'transaction_id' => $transactionId,

                'booking_status' => 'confirmed',

                'confirmed_at' => now(),
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Reload
            |--------------------------------------------------------------------------
            */

            $booking->load([
                'customer',
                'familyMember',
                'quotation',
                'hospital',
                'surgery',
                'payment',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => 1,

                'message' =>
                    'Surgery booking payment verified successfully.',

                'data' =>
                    $this->bookingData($booking),
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,

                'message' =>
                    'Unable to verify payment.',

                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }

    public function bookings(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $query = SurgeryQuotationBooking::with([
            'familyMember',
            'quotation.hospital',
            'surgery',
            'payment',
        ])
            ->where('customer_id', $customer->id);

        /*
        |--------------------------------------------------------------------------
        | Booking ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Status
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
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'booking_no',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhereHas(
                    'surgery',
                    function ($surgeryQuery) use ($search) {

                        $surgeryQuery->where(
                            'name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );

                $q->orWhereHas(
                    'hospital',
                    function ($hospitalQuery) use ($search) {

                        $hospitalQuery->where(
                            'hospital_name',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
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
        | Pagination
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest('id')
            ->paginate(
                $request->get('per_page', 10)
            );

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => 1,

            'message' =>
                'Surgery Bookings Fetched Successfully',

            'data' =>
                new SurgeryQuotationBookingCollection(
                    $bookings
                ),

        ]);
    }


    private function bookingData($booking)
    {
        return [

            'id' =>
                $booking->id,

            'booking_no' =>
                $booking->booking_no,

            'customer_id' =>
                $booking->customer_id,

            'family_member_id' =>
                $booking->family_member_id,

            'surgery_quotation_request_id' =>
                $booking->surgery_quotation_request_id,

            'surgery_quotation_id' =>
                $booking->surgery_quotation_id,

            'surgery_id' =>
                $booking->surgery_id,

            'hospital_id' =>
                $booking->hospital_id,

            'amount' =>
                (float) $booking->amount,

            'discount' =>
                (float) $booking->discount,

            'tax' =>
                (float) $booking->tax,

            'total_amount' =>
                (float) $booking->total_amount,

            'booking_date' =>
                $booking->booking_date
                ? $booking->booking_date->format('Y-m-d')
                : null,

            'booking_time' =>
                $booking->booking_time,

            'payment_method' =>
                $booking->payment_method,

            'payment_id' =>
                $booking->payment_id,

            'transaction_id' =>
                $booking->transaction_id,

            'payment_status' =>
                $booking->payment_status,

            'booking_status' =>
                $booking->booking_status,

            'hospital_address' =>
                $booking->hospital_address,

            'notes' =>
                $booking->notes,

            'cancel_reason' =>
                $booking->cancel_reason,

            'confirmed_at' =>
                $booking->confirmed_at,

            'cancelled_at' =>
                $booking->cancelled_at,

            'completed_at' =>
                $booking->completed_at,

            /*
            |--------------------------------------------------------------------------
            | Family Member
            |--------------------------------------------------------------------------
            */

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

                'photo' =>
                    optional($booking->familyMember)->photo
                    ? asset(
                        $booking->familyMember->photo
                    )
                    : null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Hospital
            |--------------------------------------------------------------------------
            */

            'hospital' => [

                'id' =>
                    optional($booking->hospital)->id,

                'name' =>
                    optional($booking->hospital)->hospital_name,

                'address' =>
                    optional($booking->hospital)->address,

                'city' =>
                    optional($booking->hospital)->city,

                'state' =>
                    optional($booking->hospital)->state,

                'pincode' =>
                    optional($booking->hospital)->pincode,

                'phone' =>
                    optional($booking->hospital)->phone,

                'image' =>
                    optional($booking->hospital)->image
                    ? asset(
                        $booking->hospital->image
                    )
                    : null,
            ],

            /*
            |--------------------------------------------------------------------------
            | Quotation
            |--------------------------------------------------------------------------
            */

            'quotation' => [

                'id' =>
                    optional($booking->quotation)->id,

                'hospital_id' =>
                    optional($booking->quotation)->hospital_id,

                'hospital_name' =>
                    optional($booking->quotation)->hospital_name,

                'amount' =>
                    (float) optional(
                        $booking->quotation
                    )->amount,

                'discount' =>
                    (float) optional(
                        $booking->quotation
                    )->discount,

                'tax' =>
                    (float) optional(
                        $booking->quotation
                    )->tax,

                'total_amount' =>
                    (float) optional(
                        $booking->quotation
                    )->total_amount,

                'quotation_details' =>
                    optional(
                        $booking->quotation
                    )->quotation_details,

                'included_services' =>
                    optional(
                        $booking->quotation
                    )->included_services,

                'excluded_services' =>
                    optional(
                        $booking->quotation
                    )->excluded_services,

                'valid_until' =>
                    optional(
                        $booking->quotation
                    )->valid_until,

                'status' =>
                    optional(
                        $booking->quotation
                    )->status,
            ],

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment' => $booking->payment ? [

                'id' =>
                    $booking->payment->id,

                'payment_no' =>
                    $booking->payment->payment_no,

                'payment_for' =>
                    $booking->payment->payment_for,

                'reference_id' =>
                    $booking->payment->reference_id,

                'amount' =>
                    (float) $booking->payment->amount,

                'discount' =>
                    (float) $booking->payment->discount,

                'tax' =>
                    (float) $booking->payment->tax,

                'paid_amount' =>
                    (float) $booking->payment->paid_amount,

                'balance_amount' =>
                    (float) $booking->payment->balance_amount,

                'currency' =>
                    $booking->payment->currency,

                'payment_gateway' =>
                    $booking->payment->payment_gateway,

                'payment_method' =>
                    $booking->payment->payment_method,

                'gateway_order_id' =>
                    $booking->payment->gateway_order_id,

                'gateway_payment_id' =>
                    $booking->payment->gateway_payment_id,

                'transaction_id' =>
                    $booking->payment->transaction_id,

                'payment_status' =>
                    $booking->payment->payment_status,

                'paid_at' =>
                    $booking->payment->paid_at,

            ] : null,

            'created_at' =>
                $booking->created_at,

            'updated_at' =>
                $booking->updated_at,
        ];
    }
}