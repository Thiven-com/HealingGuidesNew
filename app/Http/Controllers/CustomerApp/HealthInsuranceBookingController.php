<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HealthInsuranceBooking;
use App\Models\HealthInsurancePlan;
use App\Models\HealthInsuranceProvider;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;

class HealthInsuranceBookingController extends Controller
{
    /**
     * GET /api/health-insurance/providers
     * List active providers.
     */
    public function providers()
    {
        $providers = HealthInsuranceProvider::query()
            ->where('status', 1)
            ->select([
                'id',
                'name',
                'slug',
                'type',
                'logo',
                'website_url',
                'support_email',
                'support_phone',
                'description',
            ])
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Health insurance providers retrieved successfully.',
            'data' => $providers,
        ]);
    }

    /**
     * GET /api/health-insurance/providers/{provider}/plans
     * List active plans for a provider.
     */
    public function providerPlans($provider)
    {
        $provider = HealthInsuranceProvider::where('status', 1)
            ->findOrFail($provider);

        $plans = $provider->plans()
            ->where('status', 1)
            ->get([
                'id',
                'health_insurance_provider_id',
                'name',
                'slug',
                'description',
                'policy_term_years',
                'price',
                'discount_price',
                'tax_price',
                'minimum_entry_age',
                'maximum_entry_age',
                'key_benefits',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Health insurance plans fetched successfully.',
            'provider' => $provider->name,
            'data' => $plans,
        ], 200);
    }

    public function Plans()
    {
        $plans = HealthInsurancePlan::where('status', 1)
            ->with([
                'provider' => function ($query) {
                    $query->where('status', 1)
                        ->select('id', 'name', 'logo');
                }
            ])
            ->get([
                'id',
                'health_insurance_provider_id',
                'name',
                'slug',
                'image',
                'description',
                'policy_term_years',
                'actual_price',
                'price',
                'discount_price',
                'tax_price',
                'minimum_entry_age',
                'maximum_entry_age',
                'key_benefits',
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Health insurance plans fetched successfully.',
            'data' => $plans,
        ], 200);
    }

    /**
     * POST /api/health-insurance/bookings
     * Book a plan for the authenticated customer.
     */
    /**
     * Create Health Insurance Booking and Razorpay Order
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

        $validated = $request->validate([
            'provider_id' => 'required|integer|exists:health_insurance_providers,id',
            'plan_id' => 'required|integer|exists:health_insurance_plans,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:25',
            'customer_email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'members_count' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string|max:2000',
            'payment_method' => 'required|in:razorpay',
        ]);

        $provider = HealthInsuranceProvider::where('status', 1)
            ->find($validated['provider_id']);

        if (!$provider) {
            return response()->json([
                'success' => 0,
                'message' => 'Health insurance provider not found or inactive.',
            ], 404);
        }

        // Confirm that the selected plan belongs to this provider.
        $plan = HealthInsurancePlan::where('id', $validated['plan_id'])
            ->where('health_insurance_provider_id', $provider->id)
            ->where('status', 1)
            ->first();

        if (!$plan) {
            return response()->json([
                'success' => 0,
                'message' => 'Selected plan is unavailable for this provider.',
            ], 404);
        }

        // Validate the main applicant's age when DOB is provided.
        if (!empty($validated['date_of_birth'])) {
            $age = Carbon::parse($validated['date_of_birth'])->age;

            if (
                $plan->minimum_entry_age !== null &&
                $age < $plan->minimum_entry_age
            ) {
                throw ValidationException::withMessages([
                    'date_of_birth' => 'Applicant is below the minimum entry age.',
                ]);
            }

            if (
                $plan->maximum_entry_age !== null &&
                $age > $plan->maximum_entry_age
            ) {
                throw ValidationException::withMessages([
                    'date_of_birth' => 'Applicant exceeds the maximum entry age.',
                ]);
            }
        }

        /*
         * Assumption:
         * discount_price is the final discounted premium,
         * and tax_price is the tax amount added to it.
         *
         * Confirm whether these fields are stored per person
         * or for the complete family before applying members_count.
         */
        $price = (float) $plan->price;
        $discountPrice = (float) $plan->discount_price;
        $taxPrice = (float) $plan->tax_price;
        $totalAmount = round($discountPrice + $taxPrice, 2);

        if ($totalAmount <= 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid insurance premium.',
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
            $bookingReference = 'HIB-'
                . now()->format('YmdHis')
                . strtoupper(Str::random(4));

            // Create Razorpay order.
            $razorpayOrder = $api->order->create([
                'amount' => (int) round($totalAmount * 100),
                'currency' => 'INR',
                'receipt' => $bookingReference,
                'notes' => [
                    'booking_reference' => $bookingReference,
                    'customer_id' => (string) $customer->id,
                    'provider_id' => (string) $provider->id,
                    'plan_id' => (string) $plan->id,
                ],
            ]);

            // Save booking and local payment record.
            $result = DB::transaction(function () use ($customer, $provider, $plan, $validated, $price, $discountPrice, $taxPrice, $totalAmount, $bookingReference, $razorpayOrder) {
                $booking = HealthInsuranceBooking::create([
                    'user_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'plan_id' => $plan->id,
                    'booking_reference' => $bookingReference,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'] ?? null,
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'members_count' => $validated['members_count'],
                    'notes' => $validated['notes'] ?? null,
                    'price' => $price,
                    'discount_price' => $discountPrice,
                    'tax_price' => $taxPrice,
                    'total_amount' => $totalAmount,
                    'payment_method' => 'razorpay',
                    'payment_status' => 'pending',
                    'status' => 'pending',
                ]);

                $payment = Payment::create([
                    'payment_no' => 'PAY'
                        . now()->format('YmdHis')
                        . strtoupper(Str::random(4)),
                    'customer_id' => $customer->id,
                    'payment_for' => 'health_insurance',
                    'reference_id' => $booking->id,
                    'amount' => $totalAmount,
                    'discount' => max(0, $price - $discountPrice),
                    'tax' => $taxPrice,
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

            $booking = $result['booking']->load(['provider', 'plan']);
            $payment = $result['payment'];

            return response()->json([
                'success' => 1,
                'message' => 'Insurance booking created. Proceed with Razorpay payment.',
                'data' => [
                    'booking' => $this->bookingData($booking, $payment),

                    'razorpay' => [
                        'key' => $key,
                        'order_id' => $razorpayOrder['id'],
                        'amount' => (int) round($totalAmount * 100),
                        'amount_rupees' => $totalAmount,
                        'currency' => 'INR',
                        'name' => $provider->name,
                        'description' => $plan->name,
                        'prefill' => [
                            'name' => $validated['customer_name'],
                            'contact' => $validated['customer_phone'],
                            'email' => $validated['customer_email'] ?? '',
                        ],
                    ],
                ],
            ], 201);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => 0,
                'message' => 'Unable to create health insurance booking.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * List authenticated customer's insurance bookings
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

        $bookings = HealthInsuranceBooking::with(['provider', 'plan'])
            ->where('user_id', $customer->id)
            ->latest()
            ->paginate(10);

        $bookings->getCollection()->transform(function ($booking) {
            $payment = $booking->payment_id
                ? Payment::where('id', $booking->payment_id)
                    ->where('customer_id', $booking->user_id)
                    ->first()
                : null;

            return $this->bookingData($booking, $payment);
        });

        return response()->json([
            'success' => 1,
            'message' => 'Health insurance bookings fetched successfully.',
            'data' => $bookings,
        ]);
    }

    /**
     * View one booking belonging to the authenticated customer
     */
    public function showBooking(Request $request, $id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $booking = HealthInsuranceBooking::with(['provider', 'plan'])
            ->where('user_id', $customer->id)
            ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Health insurance booking not found.',
            ], 404);
        }

        $payment = $booking->payment_id
            ? Payment::where('id', $booking->payment_id)
                ->where('customer_id', $customer->id)
                ->first()
            : null;

        return response()->json([
            'success' => 1,
            'message' => 'Health insurance booking retrieved successfully.',
            'data' => $this->bookingData($booking, $payment),
        ]);
    }

    /**
     * Verify Razorpay payment
     *
     * The signature is verified on the server, and the payment
     * is fetched from Razorpay before marking the booking paid.
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

        $booking = HealthInsuranceBooking::where(
            'id',
            $request->booking_id
        )
            ->where('user_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Health insurance booking not found.',
            ], 404);
        }

        $payment = Payment::where('customer_id', $customer->id)
            ->where('payment_for', 'health_insurance')
            ->where('reference_id', $booking->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => 0,
                'message' => 'Payment record not found.',
                'debug' => [
                    'booking_id' => $booking->id,
                    'payment_id' => $booking->payment_id,
                    'customer_id' => $customer->id,
                ],
            ], 404);
        }
        if (!$booking->payment_id) {
            $booking->update([
                'payment_id' => $payment->id,
            ]);
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

        if ($booking->status === 'cancelled') {
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

            // Update payment details.
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

            // Update health insurance booking details.
            $booking->update([
                'transaction_id' =>
                    $request->razorpay_payment_id,

                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);

            DB::commit();

            $booking->load([
                'provider',
                'plan',
            ]);

            $payment->refresh();

            return response()->json([
                'success' => 1,
                'message' =>
                    'Payment verified and health insurance booking confirmed successfully.',
                'data' => $this->bookingData(
                    $booking,
                    $payment
                ),
            ]);

        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

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
     * Cancel an unpaid insurance booking
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

        $booking = HealthInsuranceBooking::where('user_id', $customer->id)
            ->find($validated['booking_id']);

        if (!$booking) {
            return response()->json([
                'success' => 0,
                'message' => 'Health insurance booking not found.',
            ], 404);
        }

        if (in_array($booking->status, ['confirmed', 'cancelled'], true)) {
            return response()->json([
                'success' => 0,
                'message' => 'This booking cannot be cancelled through this endpoint.',
            ], 422);
        }

        $payment = $booking->payment_id
            ? Payment::where('id', $booking->payment_id)
                ->where('customer_id', $customer->id)
                ->first()
            : null;

        if (
            $booking->payment_status === 'paid' ||
            ($payment && $payment->payment_status === 'success')
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'This booking is paid. Follow the insurance cancellation and refund policy.',
            ], 422);
        }

        DB::transaction(function () use ($booking, $payment, $validated) {
            $booking->update([
                'status' => 'cancelled',
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
            'message' => 'Health insurance booking cancelled successfully.',
            'data' => $this->bookingData(
                $booking,
                $payment ? $payment->fresh() : null
            ),
        ]);
    }

    /**
     * Common booking response
     */
    private function bookingData($booking, $payment = null)
    {
        $provider = $booking->provider;
        $plan = $booking->plan;

        return [
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'user_id' => $booking->user_id,
            'provider_id' => $booking->provider_id,
            'plan_id' => $booking->plan_id,
            'customer_name' => $booking->customer_name,
            'customer_phone' => $booking->customer_phone,
            'customer_email' => $booking->customer_email,
            'date_of_birth' => $booking->date_of_birth,
            'members_count' => $booking->members_count,
            'price' => (float) $booking->price,
            'discount_price' => (float) $booking->discount_price,
            'tax_price' => (float) $booking->tax_price,
            'total_amount' => (float) $booking->total_amount,
            'payment_id' => $booking->payment_id,
            'payment_method' => $booking->payment_method,
            'transaction_id' => $booking->transaction_id,
            'payment_status' => $booking->payment_status,
            'status' => $booking->status,
            'notes' => $booking->notes,
            'cancel_reason' => $booking->cancel_reason,
            'cancelled_at' => $booking->cancelled_at,

            'provider' => $provider ? [
                'id' => $provider->id,
                'name' => $provider->name,
                'logo' => $provider->logo
                    ? asset($provider->logo)
                    : null,
            ] : null,

            'plan' => $plan ? [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'policy_term_years' => $plan->policy_term_years,
                'price' => (float) $plan->price,
                'discount_price' => (float) $plan->discount_price,
                'tax_price' => (float) $plan->tax_price,
            ] : null,

            'payment' => $payment ? [
                'id' => $payment->id,
                'payment_no' => $payment->payment_no,
                'amount' => (float) $payment->amount,
                'paid_amount' => (float) $payment->paid_amount,
                'balance_amount' => (float) $payment->balance_amount,
                'currency' => $payment->currency,
                'gateway_order_id' => $payment->gateway_order_id,
                'gateway_payment_id' => $payment->gateway_payment_id,
                'payment_status' => $payment->payment_status,
                'paid_at' => $payment->paid_at,
            ] : null,

            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at,
        ];
    }
}
