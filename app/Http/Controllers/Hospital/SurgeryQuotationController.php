<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\SurgeryQuotation;
use Illuminate\Http\Request;

class SurgeryQuotationController extends Controller
{
    public function index()
    {
        $hospitalId = auth('hospital')->id();

        abort_unless($hospitalId, 403);

        $quotationRequests = SurgeryQuotation::with([
            'quotationRequest.surgery',
        ])
            ->where('hospital_id', $hospitalId)
            ->latest()
            ->paginate(10);

        return view(
            'hospital.surgery-quotation-requests.index',
            compact('quotationRequests')
        );
    }

    public function show($id)
    {
        $hospitalId = auth('hospital')->id();

        abort_unless($hospitalId, 403);

        $quotation = SurgeryQuotation::with([
            'quotationRequest.surgery',
        ])
            ->where('hospital_id', $hospitalId)
            ->findOrFail($id);

        return view(
            'hospital.surgery-quotation-requests.show',
            compact('quotation')
        );
    }

    public function update(Request $request, $id)
    {
        $hospitalId = auth('hospital')->id();

        abort_unless($hospitalId, 403);

        $quotation = SurgeryQuotation::where(
            'hospital_id',
            $hospitalId
        )->findOrFail($id);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'quotation_details' => ['nullable', 'string'],
            'included_services' => ['nullable', 'string'],
            'excluded_services' => ['nullable', 'string'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $amount = (float) $validated['amount'];
        $discount = (float) ($validated['discount'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);

        if ($discount > $amount) {
            return back()
                ->withErrors([
                    'discount' => 'Discount cannot exceed the amount.',
                ])
                ->withInput();
        }

        $quotation->update([
            'amount' => $amount,
            'discount' => $discount,
            'tax' => $tax,
            'total_amount' => max(0, $amount - $discount + $tax),
            'quotation_details' => $validated['quotation_details'] ?? null,
            'included_services' => $validated['included_services'] ?? null,
            'excluded_services' => $validated['excluded_services'] ?? null,
            'valid_until' => $validated['valid_until'] ?? null,
            'status' => 'sent',
        ]);

        return redirect()
            ->route('hospital.surgery-quotation-requests.show', $quotation->id)
            ->with('success', 'Surgery quotation submitted successfully.');
    }
}
