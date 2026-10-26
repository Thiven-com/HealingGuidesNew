<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Models\SurgeryQuotation;
use Illuminate\Http\Request;

class SurgeryQuotationController extends Controller
{
   /**
     * Get all surgery quotations for the authenticated hospital.
     */
    public function index(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Please Login',
            ], 401);
        }

        $quotations = SurgeryQuotation::with([
            'quotationRequest.surgery',
        ])
            ->where('hospital_id', $hospital->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Surgery quotation requests retrieved successfully.',
            'data' => $quotations->items(),
            'pagination' => [
                'current_page' => $quotations->currentPage(),
                'last_page' => $quotations->lastPage(),
                'per_page' => $quotations->perPage(),
                'total' => $quotations->total(),
            ],
        ]);
    }

    /**
     * Get a single surgery quotation.
     */
    public function show($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Please Login',
            ], 401);
        }

        $quotation = SurgeryQuotation::with([
            'quotationRequest.surgery',
        ])
            ->where('hospital_id', $hospital->id)
            ->find($id);

        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Surgery quotation not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Surgery quotation retrieved successfully.',
            'data' => $quotation,
        ]);
    }

    /**
     * Submit or update a hospital's quotation.
     */
    public function update(Request $request, $id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Please Login',
            ], 401);
        }

        $quotation = SurgeryQuotation::where(
            'hospital_id',
            $hospital->id
        )->find($id);

        if (!$quotation) {
            return response()->json([
                'success' => false,
                'message' => 'Surgery quotation not found.',
            ], 404);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'quotation_details' => ['nullable', 'string'],
            'included_services' => ['nullable', 'string'],
            'excluded_services' => ['nullable', 'string'],
            'valid_until' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
        ]);

        $amount = (float) $validated['amount'];
        $discount = (float) ($validated['discount'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);

        if ($discount > $amount) {
            return response()->json([
                'success' => false,
                'message' => 'Discount cannot exceed the amount.',
                'errors' => [
                    'discount' => [
                        'Discount cannot exceed the amount.',
                    ],
                ],
            ], 422);
        }

        $totalAmount = $amount - $discount + $tax;

        $quotation->update([
            'amount' => $amount,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => $totalAmount,
            'quotation_details' =>
                $validated['quotation_details'] ?? null,
            'included_services' =>
                $validated['included_services'] ?? null,
            'excluded_services' =>
                $validated['excluded_services'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,s
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Surgery quotation updated successfully.',
            'data' => $quotation->fresh()->load([
                'quotationRequest.surgery',
            ]),
        ]);
    }
}
