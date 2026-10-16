<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\Payment;
use App\Models\Procedure;
use App\Models\ProcedureBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class ProcedureBookingController extends Controller
{
    /**
     * Create Procedure Booking and Razorpay Order
     */
    public function bookProcedure(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validated = $request->validate([
            'family_member_id' => 'required|integer|exists:family_members,id',
            'procedure_id' => 'required|integer|exists:procedures,id',
            'doctor_id' => 'required|integer',
            'hospital_id' => 'required|integer',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'price_type' => 'nullable|string|max:50',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:2000',
            'payment_method' => 'required|in:razorpay',
        ]);

        $familyMember = FamilyMember::where('id', $validated['family_member_id'])
            ->where('customer_id', $customer->id)
            ->first();

        if (!$familyMember) {
            return response()->json([
                'success' => 0,
                'message' => 'Family member not found.',
            ], 404);
        }

        $procedure = Procedure::where('id', $validated['procedure_id'])
            ->where('status', 1)
            ->first();

        if (!$procedure) {
            return response()->json([
                'success' => 0,
                'message' => 'Procedure not found or inactive.',
            ], 404);
        }

        /*
         * IMPORTANT:
         * Validate that the doctor and hospital exist, are active,
         * and are associated with the selected procedure.
         * Add your project's actual relationship/pivot checks here.
         */

        $price = (float) $procedure->price;

        // Prefer server-calculated discounts and taxes in production.
        $discount = (float) ($validated['discount'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);

        if ($price <= 0 || $discount > $price) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid procedure amount or discount.',
            ], 422);
        }

        $totalAmount = round($price - $discount + $tax, 2);

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid booking amount.',
            ], 422);
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (!$key || !$secret) {
            return response()->json([
                'success' => 0,
                'message' => 'Razorpay credentials are not configured.',
            ], 500);
        }

        $api = new Api($key, $secret);

        try {
            $bookingNo = 'PRB'
                . now()->format('YmdHis')
                . strtoupper(Str::random(4));

            /*
             * Create the Razorpay order first.
             */
            $razorpayOrder = $api->order->create([
                'amount' => (int) round($totalAmount * 100),
                'currency' => 'INR',
                'receipt' => $bookingNo,
                'notes' => [
                    'booking_no' => $bookingNo,
                    'customer_id' => (string) $customer->id,
                    'procedure_id' => (string) $procedure->id,
                ],
            ]);

            /*
             * Save the booking and local payment record together.
             */
            $result = DB::transaction(function () use ($customer, $familyMember, $procedure, $validated, $price, $discount, $tax, $totalAmount, $bookingNo, $razorpayOrder) {
                $booking = ProcedureBooking::create([
                    'booking_no' => $bookingNo,
                    'customer_id' => $customer->id,
                    'family_member_id' => $familyMember->id,
                    'procedure_id' => $procedure->id,
                    'doctor_id' => $validated['doctor_id'],
                    'hospital_id' => $validated['hospital_id'],
                    'booking_date' => $validated['booking_date'],
                    'booking_time' => $validated['booking_time'],
                    'price_type' => $validated['price_type'] ?? 'standard',
                    'price' => $price,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total_amount' => $totalAmount,
                    'payment_method' => 'razorpay',
                    'transaction_id' => null,
                    'payment_id' => null,
                    'payment_status' => 'pending',
                    'booking_status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);

                $payment = Payment::create([
                    'payment_no' => 'PAY'
                        . now()->format('YmdHis')
                        . strtoupper(Str::random(4)),
                    'customer_id' => $customer->id,
                    'payment_for' => 'procedure',
                    'reference_id' => $booking->id,
                    'amount' => $totalAmount,
                    'discount' => $discount,
                    'tax' => $tax,
                    'paid_amount' => 0,
                    'balance_amount' => $totalAmount,
                    'currency' => 'INR',
                    'payment_gateway' => 'razorpay',
                    'payment_method' => 'razorpay',
                    'gateway_order_id' => $razorpayOrder['id'],
                    'gateway_payment_id' => null,
                    'transaction_id' => null,
                    'bank_reference_no' => null,
                    'invoice_no' => null,
                    'payment_status' => 'pending',
                    'failure_reason' => null,
                    'paid_at' => null,
                    'remarks' => $validated['notes'] ?? null,
                ]);

                $booking->update([
                    'payment_id' => $payment->id,
                ]);

                return [
                    'booking' => $booking,
                    'payment' => $payment,
                ];
            });

            $booking = $result['booking']->load([
                'procedure',
                'familyMember',
            ]);

            $payment = $result['payment'];

            return response()->json([
                'success' => 1,
                'message' => 'Procedure booking created. Proceed with Razorpay payment.',
                'data' => [
                    'booking' => $this->bookingData($booking, $payment),

                    // Local payment ID is available immediately.
                    'payment' => [
                        'id' => $payment->id,
                        'payment_no' => $payment->payment_no,
                        'payment_status' => $payment->payment_status,
                        'gateway_order_id' => $payment->gateway_order_id,
                        'gateway_payment_id' => $payment->gateway_payment_id,
                    ],

                    'razorpay' => [
                        'key' => $key,
                        'order_id' => $razorpayOrder['id'],
                        'amount' => (int) round($totalAmount * 100),
                        'amount_rupees' => $totalAmount,
                        'currency' => 'INR',
                        'name' => $procedure->name
                            ?? $procedure->procedure_name
                            ?? 'Procedure Booking',
                        'description' => $procedure->name
                            ?? $procedure->procedure_name
                            ?? 'Procedure',
                        'prefill' => [
                            'name' => $customer->name ?? $familyMember->name,
                            'contact' => $customer->mobile
                                ?? $familyMember->mobile,
                            'email' => $customer->email ?? null,
                        ],
                    ],
                ],
            ], 201);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => 0,
                'message' => 'Unable to create procedure booking.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get Customer Procedure Bookings
     */
    public function myBookings(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $request->validate([
            'family_member_id' => 'nullable|integer',
            'procedure_id' => 'nullable|integer',
            'doctor_id' => 'nullable|integer',
            'hospital_id' => 'nullable|integer',
            'booking_status' => 'nullable|string',
            'payment_status' => 'nullable|string',
        ]);

        $query = ProcedureBooking::with([
            'procedure',
            'familyMember',
        ])->where('customer_id', $customer->id);

        foreach ([
            'family_member_id',
            'procedure_id',
            'doctor_id',
            'hospital_id',
            'booking_status',
            'payment_status',
        ] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        $bookings = $query->latest()->get();

        $data = $bookings->map(function ($booking) {
            $payment = $booking->payment_id
                ? Payment::where('id', $booking->payment_id)
                    ->where('customer_id', $booking->customer_id)
                    ->first()
                : null;

            return $this->bookingData($booking, $payment);
        })->values();

        return response()->json([
            'success' => 1,
            'message' => 'Procedure bookings fetched successfully.',
            'data' => $data,
        ]);
    }

    /**
     * Get Single Procedure Booking
     */
    public function bookingDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $booking = ProcedureBooking::with([
            'procedure',
            'familyMember',
        ])->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Procedure booking not found.',
            ], 404);
        }

        $payment = $booking->payment_id
            ? Payment::where('id', $booking->payment_id)
                ->where('customer_id', $customer->id)
                ->first()
            : null;

        return response()->json([
            'success' => 1,
            'message' => 'Procedure booking fetched successfully.',
            'data' => $this->bookingData($booking, $payment),
        ]);
    }

    /**
     * Cancel Procedure Booking
     */
    public function cancelBooking(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validated = $request->validate([
            'booking_id' => 'required|integer',
            'cancel_reason' => 'nullable|string|max:1000',
        ]);

        $booking = ProcedureBooking::where('id', $validated['booking_id'])
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Procedure booking not found.',
            ], 404);
        }

        if (
            in_array($booking->booking_status, [
                'completed',
                'cancelled',
            ], true)
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'This booking cannot be cancelled.',
            ], 422);
        }

        $payment = $booking->payment_id
            ? Payment::where('id', $booking->payment_id)
                ->where('customer_id', $customer->id)
                ->first()
            : null;

        if (
            $booking->payment_status === 'paid'
            || ($payment && $payment->payment_status === 'success')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'This booking has been paid. Follow the refund/cancellation policy.',
            ], 422);
        }

        DB::transaction(function () use ($booking, $validated, $payment) {
            $booking->update([
                'booking_status' => 'cancelled',
                'cancel_reason' => $validated['cancel_reason'] ?? null,
                'cancelled_at' => now(),
            ]);

            if ($payment && $payment->payment_status === 'pending') {
                $payment->update([
                    'payment_status' => 'failed',
                    'failure_reason' => 'Booking cancelled before payment.',
                ]);
            }
        });

        $booking->refresh();

        return response()->json([
            'success' => 1,
            'message' => 'Procedure booking cancelled successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment ? $payment->fresh() : null
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
        'booking_id' => 'required|integer',
        'razorpay_order_id' => 'required|string',
        'razorpay_payment_id' => 'required|string',
    ]);

    $booking = ProcedureBooking::where(
        'id',
        $request->booking_id
    )
        ->where('customer_id', $customer->id)
        ->first();

    if (!$booking) {
        return response()->json([
            'success' => 0,
            'message' => 'Procedure booking not found.',
        ], 404);
    }

    $payment = Payment::where(
        'id',
        $booking->payment_id
    )
        ->where('customer_id', $customer->id)
        ->first();

    if (!$payment) {
        return response()->json([
            'success' => 0,
            'message' => 'Payment record not found.',
        ], 404);
    }

    if ($payment->payment_status === 'success') {
        return response()->json([
            'success' => 1,
            'message' => 'Payment already verified.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);
    }

    if ($booking->booking_status === 'cancelled') {
        return response()->json([
            'success' => 0,
            'message' => 'This booking has been cancelled.',
        ], 422);
    }

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
        $payment->update([
            'gateway_payment_id' =>
                $request->razorpay_payment_id,

            'transaction_id' =>
                $request->razorpay_payment_id,

            'paid_amount' => $payment->amount,
            'balance_amount' => 0,
            'payment_status' => 'success',
            'paid_at' => now(),
            'failure_reason' => null,
        ]);

        $booking->update([
            'transaction_id' =>
                $request->razorpay_payment_id,

            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        DB::commit();

        $booking->load([
            'procedure',
            'familyMember',
        ]);

        return response()->json([
            'success' => 1,
            'message' =>
                'Payment verified and procedure booking confirmed successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment
            ),
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        report($e);

        return response()->json([
            'success' => 0,
            'message' => 'Razorpay payment verification failed.',
            'error' => config('app.debug')
                ? $e->getMessage()
                : null,
        ], 422);
    }
}


    /**
     * Record Payment Failure
     *
     * This endpoint must not be treated as proof that a payment failed.
     * Reconcile with Razorpay before changing a payment to failed.
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

        $validated = $request->validate([
            'booking_id' => 'required|integer',
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'nullable|string',
            'failure_reason' => 'nullable|string|max:2000',
        ]);

        $booking = ProcedureBooking::where('id', $validated['booking_id'])
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Procedure booking not found.',
            ], 404);
        }

        $payment = Payment::where('id', $booking->payment_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => 0,
                'message' => 'Payment record not found.',
            ], 404);
        }

        if ($payment->payment_status === 'success') {
            return response()->json([
                'success' => 0,
                'message' => 'This payment is already successful.',
            ], 422);
        }

        if (
            $payment->gateway_order_id !== $validated['razorpay_order_id']
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Razorpay order ID.',
            ], 422);
        }

        /*
         * Do not mark a payment failed solely because the client
         * reports a failure. The gateway may have captured it.
         * Reconcile this order/payment through Razorpay or a trusted
         * server-side webhook before changing payment status.
         */
        return response()->json([
            'success' => 1,
            'message' => 'Payment failure report received. Verify the gateway status before updating payment records.',
            'data' => [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'gateway_order_id' => $payment->gateway_order_id,
                'gateway_payment_id' =>
                    $validated['razorpay_payment_id'] ?? null,
                'payment_status' => $payment->payment_status,
            ],
        ]);
    }

    /**
     * Common Booking Response
     */
    private function bookingData($booking, $payment = null)
    {
        $procedure = $booking->procedure;
        $familyMember = $booking->familyMember;

        return [
            'id' => $booking->id,
            'booking_no' => $booking->booking_no,
            'customer_id' => $booking->customer_id,
            'family_member_id' => $booking->family_member_id,
            'procedure_id' => $booking->procedure_id,
            'doctor_id' => $booking->doctor_id,
            'hospital_id' => $booking->hospital_id,

            'booking_date' => $booking->booking_date
                ? \Illuminate\Support\Carbon::parse(
                    $booking->booking_date
                )->format('Y-m-d')
                : null,

            'booking_time' => $booking->booking_time,
            'price_type' => $booking->price_type,
            'price' => (float) $booking->price,
            'discount' => (float) $booking->discount,
            'tax' => (float) $booking->tax,
            'total_amount' => (float) $booking->total_amount,
            'payment_method' => $booking->payment_method,
            'transaction_id' => $booking->transaction_id,

            // Local payments-table primary key.
            'payment_id' => $booking->payment_id,

            'payment_status' => $booking->payment_status,
            'booking_status' => $booking->booking_status,
            'notes' => $booking->notes,
            'cancel_reason' => $booking->cancel_reason,
            'cancelled_at' => $booking->cancelled_at,

            'family_member' => $familyMember ? [
                'id' => $familyMember->id,
                'name' => $familyMember->name,
                'mobile' => $familyMember->mobile,
                'relationship' => $familyMember->relationship,
                'gender' => $familyMember->gender,
                'dob' => $familyMember->dob,
                'age' => $familyMember->age,
                'blood_group' => $familyMember->blood_group,
            ] : null,

            'procedure' => $procedure ? [
                'id' => $procedure->id,
                'name' => $procedure->name
                    ?? $procedure->procedure_name
                    ?? null,
                'procedure_name' => $procedure->procedure_name
                    ?? $procedure->name
                    ?? null,
                'image' => $procedure->image
                    ? asset($procedure->image)
                    : null,
                'price' => $procedure->price !== null
                    ? (float) $procedure->price
                    : null,
                'member_package_price' =>
                    $procedure->member_package_price !== null
                    ? (float) $procedure->member_package_price
                    : null,
            ] : null,

            'payment' => $payment ? [
                'id' => $payment->id,
                'payment_no' => $payment->payment_no,
                'payment_for' => $payment->payment_for,
                'reference_id' => $payment->reference_id,
                'amount' => (float) $payment->amount,
                'discount' => (float) $payment->discount,
                'tax' => (float) $payment->tax,
                'paid_amount' => (float) $payment->paid_amount,
                'balance_amount' => (float) $payment->balance_amount,
                'currency' => $payment->currency,
                'payment_gateway' => $payment->payment_gateway,
                'payment_method' => $payment->payment_method,
                'gateway_order_id' => $payment->gateway_order_id,

                // Null before checkout payment succeeds.
                'gateway_payment_id' => $payment->gateway_payment_id,
                'transaction_id' => $payment->transaction_id,
                'invoice_no' => $payment->invoice_no,
                'payment_status' => $payment->payment_status,
                'failure_reason' => $payment->failure_reason,
                'paid_at' => $payment->paid_at,
                'remarks' => $payment->remarks,
            ] : null,

            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at,
        ];
    }
}


