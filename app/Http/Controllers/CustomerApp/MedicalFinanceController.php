<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FinanceProvider;
use App\Models\MedicalFinanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicalFinanceController extends Controller
{
    /**
     * Get available finance providers.
     */
    public function providers(Request $request)
    {
        $query = FinanceProvider::query()
            ->where('status', true)
            ->where('medical_finance', true)
            ->orderBy('display_order')
            ->orderBy('name');

        if ($request->filled('amount')) {
            $amount = (float) $request->amount;

            $query->where('min_amount', '<=', $amount)
                ->where('max_amount', '>=', $amount);
        }

        if ($request->filled('purpose')) {
            $purposeColumns = [
                'hospitalization' => 'hospitalization',
                'surgery' => 'surgery',
                'diagnostics' => 'diagnostics',
                'medicines' => 'medicines',
                'doctor_consultation' => 'doctor_consultation',
                'dental_treatment' => 'dental_treatment',
                'medical_treatment' => 'medical_treatment',
            ];

            $purpose = $request->purpose;

            if (isset($purposeColumns[$purpose])) {
                $query->where(
                    $purposeColumns[$purpose],
                    true
                );
            }
        }

        $providers = $query->get();

        return response()->json([
            'success' => 1,
            'message' => 'Finance providers fetched successfully.',
            'data' => $providers,
        ]);
    }

    /**
     * Get finance provider details.
     */
    public function providerDetails(Request $request, $id)
    {
        $provider = FinanceProvider::query()
            ->where('id', $id)
            ->where('status', true)
            ->where('medical_finance', true)
            ->first();

        if (!$provider) {
            return response()->json([
                'success' => 0,
                'message' => 'Finance provider not found.',
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Finance provider fetched successfully.',
            'data' => $provider,
        ]);
    }

    /**
     * Create medical finance request.
     *
     * No medical bill is required.
     */
    public function store(Request $request)
    {
        $customer = auth('sanctum')->user();

        $validated = $request->validate([
            'finance_provider_id' => [
                'required',
                'integer',
                'exists:finance_providers,id',
            ],

            'family_member_id' => [
                'nullable',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:20',
            ],

            'purpose' => [
                'required',
                'string',
            ],

            'requested_amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'tenure_months' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'consent_given' => [
                'required',
                'accepted',
            ],
        ]);

        /*
         * Get active finance provider.
         */
        $provider = FinanceProvider::query()
            ->where('id', $validated['finance_provider_id'])
            ->where('status', true)
            ->where('medical_finance', true)
            ->first();

        if (!$provider) {
            return response()->json([
                'success' => 0,
                'message' => 'Selected finance provider is not available.',
            ], 422);
        }

        /*
         * Check purpose supported by provider.
         */
        $purposeColumns = [
            'hospitalization' => 'hospitalization',
            'surgery' => 'surgery',
            'diagnostics' => 'diagnostics',
            'medicines' => 'medicines',
            'doctor_consultation' => 'doctor_consultation',
            'dental_treatment' => 'dental_treatment',
            'medical_treatment' => 'medical_treatment',
        ];

        if (isset($purposeColumns[$validated['purpose']])) {

            $column = $purposeColumns[$validated['purpose']];

            if (!$provider->{$column}) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Selected finance provider does not support this purpose.',
                ], 422);
            }
        }

        /*
         * Validate requested amount.
         */
        $requestedAmount = (float) $validated['requested_amount'];

        if (
            $requestedAmount < (float) $provider->min_amount ||
            $requestedAmount > (float) $provider->max_amount
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Requested amount must be between ₹'
                    . number_format((float) $provider->min_amount, 2)
                    . ' and ₹'
                    . number_format((float) $provider->max_amount, 2)
                    . '.',
            ], 422);
        }

        /*
         * Validate tenure.
         */
        if (!empty($validated['tenure_months'])) {

            $tenure = (int) $validated['tenure_months'];

            if (
                $provider->tenure_min_months &&
                $tenure < $provider->tenure_min_months
            ) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Selected tenure is below the minimum tenure.',
                ], 422);
            }

            if (
                $provider->tenure_max_months &&
                $tenure > $provider->tenure_max_months
            ) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Selected tenure exceeds the maximum tenure.',
                ], 422);
            }
        }

        /*
         * Validate family member belongs to customer.
         */
        if (!empty($validated['family_member_id'])) {

            $familyMemberExists = DB::table('family_members')
                ->where('id', $validated['family_member_id'])
                ->where('customer_id', $customer->id)
                ->exists();

            if (!$familyMemberExists) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Invalid family member.',
                ], 422);
            }
        }

        /*
         * Generate unique application number.
         */
        do {
            $applicationNo = 'MF'
                . now()->format('YmdHis')
                . strtoupper(Str::random(5));

        } while (
            MedicalFinanceRequest::where(
                'application_no',
                $applicationNo
            )->exists()
        );

        /*
         * Create request.
         */
        $financeRequest = MedicalFinanceRequest::create([
            'application_no' => $applicationNo,

            'customer_id' => $customer->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'family_member_id' =>
                $validated['family_member_id'] ?? null,

            'finance_provider_id' =>
                $provider->id,

            'purpose' =>
                $validated['purpose'],

            'requested_amount' =>
                $requestedAmount,

            'tenure_months' =>
                $validated['tenure_months'] ?? null,

            /*
             * Save provider terms at the time
             * of application.
             */
            'interest_rate' =>
                $provider->interest_rate,

            'processing_fee' =>
                $provider->processing_fee,

            /*
             * Third-party CIBIL integration
             * will be added later.
             */
            'cibil_status' => 'pending',
            'cibil_score' => null,

            'consent_given' => true,
            'consent_at' => now(),

            'status' => 'under_review',

            'submitted_at' => now(),
        ]);

        $financeRequest->load([
            'financeProvider',
            'familyMember',
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Medical finance request submitted successfully.',
            'data' => $financeRequest,
        ], 201);
    }

    /**
     * Get customer's finance requests.
     */
    public function index(Request $request)
    {
        $customer = auth('sanctum')->user();

        $query = MedicalFinanceRequest::query()
            ->where('customer_id', $customer->id)
            ->with([
                'financeProvider:id,name,logo',
                'familyMember:id,name,relationship',
            ])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(
            $request->integer('per_page', 10)
        );

        $data = $requests->getCollection()->map(function ($financeRequest) {

            return [
                'id' => $financeRequest->id,

                'application_no' => $financeRequest->application_no,

                'name' => $financeRequest->name,

                'email' => $financeRequest->email,

                'mobile' => $financeRequest->mobile,

                'finance_provider' => [
                    'id' => $financeRequest->financeProvider?->id,
                    'name' => $financeRequest->financeProvider?->name,
                    'logo' => $financeRequest->financeProvider?->logo,
                ],

                'family_member' => $financeRequest->familyMember ? [
                    'id' => $financeRequest->familyMember->id,
                    'name' => $financeRequest->familyMember->name,
                    'relationship' => $financeRequest->familyMember->relationship,
                ] : null,

                'purpose' => $financeRequest->purpose,

                'requested_amount' => (float) $financeRequest->requested_amount,

                'approved_amount' => $financeRequest->approved_amount !== null
                    ? (float) $financeRequest->approved_amount
                    : null,

                'disbursed_amount' => $financeRequest->disbursed_amount !== null
                    ? (float) $financeRequest->disbursed_amount
                    : null,

                'tenure_months' => $financeRequest->tenure_months,

                'interest_rate' => $financeRequest->interest_rate !== null
                    ? (float) $financeRequest->interest_rate
                    : null,

                'processing_fee' => $financeRequest->processing_fee !== null
                    ? (float) $financeRequest->processing_fee
                    : null,

                'cibil_status' => $financeRequest->cibil_status,

                'status' => $financeRequest->status,

                'submitted_at' => $financeRequest->submitted_at?->format('Y-m-d H:i:s'),

                'approved_at' => $financeRequest->approved_at?->format('Y-m-d H:i:s'),

                'rejected_at' => $financeRequest->rejected_at?->format('Y-m-d H:i:s'),

                'disbursed_at' => $financeRequest->disbursed_at?->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => 1,
            'message' => 'Medical finance requests fetched successfully.',
            'data' => $data->values(),
        ]);
    }

    /**
     * Get finance request details.
     */
    public function show(Request $request, $id)
    {
        $customer = auth('sanctum')->user();

        $financeRequest = MedicalFinanceRequest::query()
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->with([
                'financeProvider',
                'familyMember',
            ])
            ->first();

        if (!$financeRequest) {
            return response()->json([
                'success' => 0,
                'message' => 'Medical finance request not found.',
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medical finance request fetched successfully.',
            'data' => $financeRequest,
        ]);
    }

    /**
     * Cancel finance request.
     */
    public function cancel(Request $request, $id)
    {
        $customer = auth('sanctum')->user();

        $financeRequest = MedicalFinanceRequest::query()
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$financeRequest) {
            return response()->json([
                'success' => 0,
                'message' => 'Medical finance request not found.',
            ], 404);
        }

        if (
            in_array($financeRequest->status, [
                'approved',
                'disbursed',
                'completed',
            ])
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'This finance request cannot be cancelled.',
            ], 422);
        }

        if ($financeRequest->status === 'cancelled') {
            return response()->json([
                'success' => 0,
                'message' => 'Finance request is already cancelled.',
            ], 422);
        }

        $financeRequest->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Medical finance request cancelled successfully.',
            'data' => $financeRequest->fresh()->load([
                'financeProvider',
                'familyMember',
            ]),
        ]);
    }
}