<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnostic;
use App\Models\Hospital;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\PrescriptionQuotation;
use App\Models\PrescriptionQuotationLabTest;
use App\Models\PrescriptionQuotationMedicine;
use App\Models\PrescriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PrescriptionQuotationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Quotation
    |--------------------------------------------------------------------------
    */

    public function create($requestId)
    {
        $prescriptionRequest =
            PrescriptionRequest::with([
                'customer',
                'familyMember',
                'prescription',
            ])->findOrFail($requestId);

        /*
        |--------------------------------------------------------------------------
        | Hospitals
        |--------------------------------------------------------------------------
        */

        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Diagnostics
        |--------------------------------------------------------------------------
        */

        $diagnostics = Diagnostic::where('status', 1)
            ->orderBy('diagnostic_name')
            ->get();

        return view(
            'admin.prescription_requests.quotation_create',
            compact(
                'prescriptionRequest',
                'hospitals',
                'diagnostics'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Quotation
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $requestId)
    {
        $prescriptionRequest =
            PrescriptionRequest::findOrFail($requestId);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'quotation_type' =>
                'required|in:medicines,lab_tests',

            'hospital_id' =>
                'nullable|exists:hospitals,id',

            'diagnostic_id' =>
                'nullable|exists:diagnostics,id',

            'delivery_charge' =>
                'nullable|numeric|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'quotation_details' =>
                'nullable|string',

            'admin_notes' =>
                'nullable|string',

            'valid_until' =>
                'nullable|date',

            'medicine_items' =>
                'nullable|array',

            'medicine_items.*.medicine_id' =>
                'nullable|exists:medicines,id',

            'medicine_items.*.medicine_name' =>
                'required_if:quotation_type,medicines|string',

            'medicine_items.*.medicine_code' =>
                'nullable|string',

            'medicine_items.*.strength' =>
                'nullable|string',

            'medicine_items.*.pack_size' =>
                'nullable|string',

            'medicine_items.*.quantity' =>
                'required_if:quotation_type,medicines|integer|min:1',

            'medicine_items.*.mrp' =>
                'required_if:quotation_type,medicines|numeric|min:0',

            'medicine_items.*.price' =>
                'required_if:quotation_type,medicines|numeric|min:0',

            'lab_test_items' =>
                'nullable|array',

            'lab_test_items.*.lab_test_id' =>
                'nullable|exists:lab_tests,id',

            'lab_test_items.*.test_name' =>
                'required_if:quotation_type,lab_tests|string',

            'lab_test_items.*.test_code' =>
                'nullable|string',

            'lab_test_items.*.mrp' =>
                'required_if:quotation_type,lab_tests|numeric|min:0',

            'lab_test_items.*.price' =>
                'required_if:quotation_type,lab_tests|numeric|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validate Hospital / Diagnostic
        |--------------------------------------------------------------------------
        */

        if (
            $validated['quotation_type'] ===
            'medicines'
        ) {

            if (empty($validated['hospital_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Please select a hospital for medicine quotation.'
                    );
            }

        } elseif (
            $validated['quotation_type'] ===
            'lab_tests'
        ) {

            if (empty($validated['diagnostic_id'])) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Please select a diagnostic centre for lab test quotation.'
                    );
            }
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Calculate Subtotal
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;


            /*
            |--------------------------------------------------------------------------
            | Medicine Items
            |--------------------------------------------------------------------------
            */

            if (
                $validated['quotation_type'] ===
                'medicines'
            ) {

                foreach (
                    ($validated['medicine_items'] ?? [])
                    as $item
                ) {

                    $quantity =
                        (int) $item['quantity'];

                    $price =
                        (float) $item['price'];

                    $total =
                        $quantity * $price;

                    $subtotal += $total;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Lab Test Items
            |--------------------------------------------------------------------------
            */

            if (
                $validated['quotation_type'] ===
                'lab_tests'
            ) {

                foreach (
                    ($validated['lab_test_items'] ?? [])
                    as $item
                ) {

                    $price =
                        (float) $item['price'];

                    $subtotal += $price;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Charges
            |--------------------------------------------------------------------------
            */

            $deliveryCharge =
                (float) ($validated['delivery_charge'] ?? 0);

            $discount =
                (float) ($validated['discount'] ?? 0);

            $tax =
                (float) ($validated['tax'] ?? 0);


            $totalAmount =
                $subtotal
                + $deliveryCharge
                + $tax
                - $discount;


            if ($totalAmount < 0) {
                $totalAmount = 0;
            }


            /*
            |--------------------------------------------------------------------------
            | Quotation Number
            |--------------------------------------------------------------------------
            */

            $quotationNo =
                'PQ'
                . date('YmdHis')
                . strtoupper(
                    Str::random(4)
                );


            /*
            |--------------------------------------------------------------------------
            | Create Quotation
            |--------------------------------------------------------------------------
            */

            $quotation =
                PrescriptionQuotation::create([

                    'prescription_request_id' =>
                        $prescriptionRequest->id,

                    'quotation_no' =>
                        $quotationNo,

                    'quotation_type' =>
                        $validated['quotation_type'],

                    'hospital_id' =>
                        $validated['hospital_id'] ?? null,

                    'diagnostic_id' =>
                        $validated['diagnostic_id'] ?? null,

                    'subtotal' =>
                        $subtotal,

                    'delivery_charge' =>
                        $deliveryCharge,

                    'discount' =>
                        $discount,

                    'tax' =>
                        $tax,

                    'total_amount' =>
                        $totalAmount,

                    'quotation_details' =>
                        $validated['quotation_details'] ?? null,

                    'admin_notes' =>
                        $validated['admin_notes'] ?? null,

                    'valid_until' =>
                        $validated['valid_until'] ?? null,

                    'status' =>
                        'draft',
                ]);


            /*
            |--------------------------------------------------------------------------
            | Save Medicine Items
            |--------------------------------------------------------------------------
            */

            if (
                $validated['quotation_type'] ===
                'medicines'
            ) {

                foreach (
                    ($validated['medicine_items'] ?? [])
                    as $item
                ) {

                    $quantity =
                        (int) $item['quantity'];

                    $price =
                        (float) $item['price'];

                    PrescriptionQuotationMedicine::create([

                        'prescription_quotation_id' =>
                            $quotation->id,

                        'medicine_id' =>
                            $item['medicine_id'] ?? null,

                        'medicine_name' =>
                            $item['medicine_name'],

                        'medicine_code' =>
                            $item['medicine_code'] ?? null,

                        'strength' =>
                            $item['strength'] ?? null,

                        'pack_size' =>
                            $item['pack_size'] ?? null,

                        'quantity' =>
                            $quantity,

                        'mrp' =>
                            $item['mrp'],

                        'price' =>
                            $price,

                        'total' =>
                            $quantity * $price,
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Save Lab Test Items
            |--------------------------------------------------------------------------
            */

            if (
                $validated['quotation_type'] ===
                'lab_tests'
            ) {

                foreach (
                    ($validated['lab_test_items'] ?? [])
                    as $item
                ) {

                    $price =
                        (float) $item['price'];

                    PrescriptionQuotationLabTest::create([

                        'prescription_quotation_id' =>
                            $quotation->id,

                        'lab_test_id' =>
                            $item['lab_test_id'] ?? null,

                        'test_name' =>
                            $item['test_name'],

                        'test_code' =>
                            $item['test_code'] ?? null,

                        'mrp' =>
                            $item['mrp'],

                        'price' =>
                            $price,

                        'total' =>
                            $price,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update Request
            |--------------------------------------------------------------------------
            */

            if (
                $prescriptionRequest->status ===
                'pending'
            ) {

                $prescriptionRequest->update([
                    'status' => 'reviewing',
                    'reviewed_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'admin.prescription-quotations.show',
                    $quotation->id
                )
                ->with(
                    'success',
                    'Quotation created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create quotation: '
                    . $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Show Quotation
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $quotation =
            PrescriptionQuotation::with([
                'prescriptionRequest.customer',
                'prescriptionRequest.familyMember',

                'hospital',
                'diagnostic',

                'medicineItems.medicine',
                'labTestItems.labTest',

                'payments',
            ])->findOrFail($id);

        return view(
            'admin.prescription_quotations.show',
            compact('quotation')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Quotation
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $quotation =
            PrescriptionQuotation::with([
                'prescriptionRequest',
                'hospital',
                'diagnostic',
                'medicineItems',
                'labTestItems',
            ])->findOrFail($id);

        $hospitals = Hospital::where('status', 1)
            ->orderBy('hospital_name')
            ->get();

        $diagnostics = Diagnostic::where('status', 1)
            ->orderBy('name')
            ->get();

        return view(
            'admin.prescription_quotations.edit',
            compact(
                'quotation',
                'hospitals',
                'diagnostics'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Quotation
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $quotation =
            PrescriptionQuotation::findOrFail($id);

        if (
            in_array(
                $quotation->status,
                [
                    'sent',
                    'approved',
                    'paid',
                    'completed',
                ]
            )
        ) {

            return back()
                ->with(
                    'error',
                    'This quotation cannot be edited.'
                );
        }

        $validated =
            $request->validate([

                'hospital_id' =>
                    'nullable|exists:hospitals,id',

                'diagnostic_id' =>
                    'nullable|exists:diagnostics,id',

                'delivery_charge' =>
                    'nullable|numeric|min:0',

                'discount' =>
                    'nullable|numeric|min:0',

                'tax' =>
                    'nullable|numeric|min:0',

                'quotation_details' =>
                    'nullable|string',

                'admin_notes' =>
                    'nullable|string',

                'valid_until' =>
                    'nullable|date',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Determine Subtotal From Existing Items
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;

        if (
            $quotation->quotation_type ===
            'medicines'
        ) {

            $subtotal =
                $quotation->medicineItems()
                    ->sum('total');

        } elseif (
            $quotation->quotation_type ===
            'lab_tests'
        ) {

            $subtotal =
                $quotation->labTestItems()
                    ->sum('total');
        }

        $deliveryCharge =
            (float) (
                $validated['delivery_charge']
                ?? 0
            );

        $discount =
            (float) (
                $validated['discount']
                ?? 0
            );

        $tax =
            (float) (
                $validated['tax']
                ?? 0
            );

        $total =
            $subtotal
            + $deliveryCharge
            + $tax
            - $discount;

        if ($total < 0) {
            $total = 0;
        }

        $quotation->update([

            'hospital_id' =>
                $validated['hospital_id'] ?? null,

            'diagnostic_id' =>
                $validated['diagnostic_id'] ?? null,

            'subtotal' =>
                $subtotal,

            'delivery_charge' =>
                $deliveryCharge,

            'discount' =>
                $discount,

            'tax' =>
                $tax,

            'total_amount' =>
                $total,

            'quotation_details' =>
                $validated['quotation_details'] ?? null,

            'admin_notes' =>
                $validated['admin_notes'] ?? null,

            'valid_until' =>
                $validated['valid_until'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.prescription-quotations.show',
                $quotation->id
            )
            ->with(
                'success',
                'Quotation updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Send Quotation
    |--------------------------------------------------------------------------
    */

    public function send($id)
    {
        $quotation =
            PrescriptionQuotation::findOrFail($id);

        if (
            $quotation->status !== 'draft'
        ) {

            return back()
                ->with(
                    'error',
                    'Only draft quotations can be sent.'
                );
        }

        if (
            $quotation->total_amount <= 0
        ) {

            return back()
                ->with(
                    'error',
                    'Quotation amount must be greater than zero.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Hospital / Diagnostic
        |--------------------------------------------------------------------------
        */

        if (
            $quotation->quotation_type ===
            'medicines'
            && !$quotation->hospital_id
        ) {

            return back()
                ->with(
                    'error',
                    'Please select a hospital.'
                );
        }

        if (
            $quotation->quotation_type ===
            'lab_tests'
            && !$quotation->diagnostic_id
        ) {

            return back()
                ->with(
                    'error',
                    'Please select a diagnostic centre.'
                );
        }

        $quotation->update([

            'status' =>
                'sent',

            'sent_at' =>
                now(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Quotation sent to customer successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject Quotation
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        $id
    ) {

        $quotation =
            PrescriptionQuotation::findOrFail($id);

        if (
            in_array(
                $quotation->status,
                [
                    'paid',
                    'completed',
                ]
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Paid or completed quotation cannot be rejected.'
                );
        }

        $request->validate([
            'admin_notes' =>
                'nullable|string|max:2000',
        ]);

        $quotation->update([

            'status' =>
                'rejected',

            'admin_notes' =>
                $request->admin_notes,

            'rejected_at' =>
                now(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Quotation rejected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Quotation
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $quotation =
            PrescriptionQuotation::findOrFail($id);

        if (
            in_array(
                $quotation->status,
                [
                    'approved',
                    'paid',
                    'completed',
                ]
            )
        ) {

            return back()
                ->with(
                    'error',
                    'Approved, paid or completed quotation cannot be deleted.'
                );
        }

        DB::transaction(function () use ($quotation) {

            $quotation->medicineItems()->delete();

            $quotation->labTestItems()->delete();

            $quotation->delete();
        });

        return redirect()
            ->route(
                'admin.prescription-requests.show',
                $quotation->prescription_request_id
            )
            ->with(
                'success',
                'Quotation deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Search Medicines
    |--------------------------------------------------------------------------
    */


    public function searchMedicines(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'search' => 'nullable|string|max:100',
        ]);

        $hospitalId = $request->hospital_id;
        $search = $request->search;

        $medicines = Medicine::query()
            ->where('hospital_id', $hospitalId)
            ->where('status', 1)
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'medicine_name',
                        'like',
                        "%{$search}%"
                    )

                        ->orWhere(
                            'medicine_code',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'generic_name',
                            'like',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'brand_name',
                            'like',
                            "%{$search}%"
                        );

                });

            })
            ->orderBy('medicine_name')
            ->limit(20)
            ->get([
                'id',
                'hospital_id',
                'medicine_name',
                'medicine_code',
                'generic_name',
                'brand_name',
                'strength',
                'pack_size',
                'mrp',
                'selling_price',
            ]);

        return response()->json([
            'success' => 1,
            'message' => 'Medicines fetched successfully.',
            'data' => $medicines,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Search Lab Tests
    |--------------------------------------------------------------------------
    */

    public function searchLabTests(Request $request)
    {
        $search =
            trim($request->get('search', ''));

        if ($search === '') {

            return response()->json([
                'success' => 1,
                'data' => [],
            ]);
        }

        $labTests =
            LabTest::query()
                ->where('status', 1)
                ->where(function ($query) use ($search) {

                    $query->where(
                        'name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'test_code',
                            'like',
                            "%{$search}%"
                        );
                })
                ->limit(20)
                ->get();

        return response()->json([
            'success' => 1,
            'data' => $labTests,
        ]);
    }
}