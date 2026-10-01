<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\SurgeryQuotation;
use App\Models\SurgeryQuotationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SurgeryQuotationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SurgeryQuotationRequest::with([
            'customer',
            'familyMember',
            'surgery',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('request_no', 'LIKE', "%{$search}%")

                    ->orWhereHas('customer', function ($customer) use ($search) {

                        $customer
                            ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");

                    })

                    ->orWhereHas('familyMember', function ($family) use ($search) {

                        $family
                            ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('mobile', 'LIKE', "%{$search}%");

                    })

                    ->orWhereHas('surgery', function ($surgery) use ($search) {

                        $surgery->where(
                            'name',
                            'LIKE',
                            "%{$search}%"
                        );

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Requests
        |--------------------------------------------------------------------------
        */

        $quotationRequests = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.surgery_quotation_requests.index',
            compact('quotationRequests')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REQUEST
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $quotationRequest = SurgeryQuotationRequest::with([
            'customer',
            'familyMember',
            'surgery',
            'quotations.hospital',
        ])->findOrFail($id);

        return view(
            'admin.surgery_quotation_requests.show',
            compact('quotationRequest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ALL QUOTATIONS
    |--------------------------------------------------------------------------
    */

    public function quotations($id)
    {
        $quotationRequest = SurgeryQuotationRequest::with([
            'customer',
            'familyMember',
            'surgery',
            'quotations.hospital',
        ])->findOrFail($id);

        $quotations = $quotationRequest
            ->quotations()
            ->with('hospital')
            ->latest()
            ->get();

        return view(
            'admin.surgery_quotation_requests.quotations',
            compact(
                'quotationRequest',
                'quotations'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE MAIN REQUEST
    |--------------------------------------------------------------------------
    */

    public function approveRequest($id)
    {
        DB::beginTransaction();

        try {

            $quotationRequest =
                SurgeryQuotationRequest::lockForUpdate()
                    ->find($id);

            if (!$quotationRequest) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Surgery quotation request not found.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Already Approved
            |--------------------------------------------------------------------------
            */

            if ($quotationRequest->status === 'approved') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'This quotation request is already approved.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Approve Main Request
            |--------------------------------------------------------------------------
            */

            $quotationRequest->status = 'approved';

            $quotationRequest->save();

            DB::commit();

            return redirect()
                ->route(
                    'admin.surgery-quotation-requests.show',
                    $quotationRequest->id
                )
                ->with(
                    'success',
                    'Surgery quotation request approved successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Unable to approve request: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT MAIN REQUEST
    |--------------------------------------------------------------------------
    */

    public function rejectRequest($id)
    {
        DB::beginTransaction();

        try {

            $quotationRequest =
                SurgeryQuotationRequest::lockForUpdate()
                    ->find($id);

            if (!$quotationRequest) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Surgery quotation request not found.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Already Rejected
            |--------------------------------------------------------------------------
            */

            if ($quotationRequest->status === 'rejected') {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'This quotation request is already rejected.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Reject Main Request
            |--------------------------------------------------------------------------
            */

            $quotationRequest->status = 'rejected';

            $quotationRequest->save();

            DB::commit();

            return redirect()
                ->route(
                    'admin.surgery-quotation-requests.show',
                    $quotationRequest->id
                )
                ->with(
                    'success',
                    'Surgery quotation request rejected successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Unable to reject request: ' .
                    $e->getMessage()
                );
        }
    }

    public function destroyRequest($id)
    {
        $quotationRequest = SurgeryQuotationRequest::findOrFail($id);

        $quotationRequest->delete();

        return redirect()
            ->route('admin.surgery-quotation-requests.index')
            ->with(
                'success',
                'Surgery quotation request deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE HOSPITAL QUOTATION
    |--------------------------------------------------------------------------
    */

    public function create($requestId)
    {
        $quotationRequest =
            SurgeryQuotationRequest::with([
                'customer',
                'familyMember',
                'surgery',
            ])->findOrFail($requestId);

        /*
        |--------------------------------------------------------------------------
        | Hospitals
        |--------------------------------------------------------------------------
        */

        $hospitals = Hospital::where(
            'status',
            1
        )
            ->orderBy(
                'hospital_name'
            )
            ->get();

        return view(
            'admin.surgery_quotation_requests.quotation_create',
            compact(
                'quotationRequest',
                'hospitals'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE HOSPITAL QUOTATION
    |--------------------------------------------------------------------------
    */


    public function store(
        Request $request,
        $requestId
    ) {
        $quotationRequest = SurgeryQuotationRequest::findOrFail(
            $requestId
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'hospital_id' => [
                'required',
                'exists:hospitals,id',
                Rule::unique('surgery_quotations', 'hospital_id')
                    ->where(function ($query) use ($requestId) {
                        return $query->where(
                            'surgery_quotation_request_id',
                            $requestId
                        );
                    }),
            ],

            'amount' =>
                'required|numeric|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'quotation_details' =>
                'nullable|string',

            'included_services' =>
                'nullable|string',

            'excluded_services' =>
                'nullable|string',

            'valid_until' =>
                'nullable|date|after_or_equal:today',

            'admin_notes' =>
                'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::findOrFail(
            $validated['hospital_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Amount Calculation
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $discount = (float) (
            $validated['discount'] ?? 0
        );

        $tax = (float) (
            $validated['tax'] ?? 0
        );

        $totalAmount =
            $amount
            - $discount
            + $tax;

        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Quotation
        |--------------------------------------------------------------------------
        */

        SurgeryQuotation::create([

            'surgery_quotation_request_id' =>
                $quotationRequest->id,

            'hospital_id' =>
                $hospital->id,

            'hospital_name' =>
                $hospital->hospital_name,

            'amount' =>
                $amount,

            'discount' =>
                $discount,

            'tax' =>
                $tax,

            'total_amount' =>
                $totalAmount,

            'quotation_details' =>
                $validated['quotation_details'] ?? null,

            'included_services' =>
                $validated['included_services'] ?? null,

            'excluded_services' =>
                $validated['excluded_services'] ?? null,

            'valid_until' =>
                $validated['valid_until'] ?? null,

            'status' =>
                'sent',

            'sent_at' =>
                now(),

            'admin_notes' =>
                $validated['admin_notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.surgery-quotation-requests.show',
                $quotationRequest->id
            )
            ->with(
                'success',
                'Hospital quotation added successfully.'
            );
    }




    /*
    |--------------------------------------------------------------------------
    | EDIT HOSPITAL QUOTATION
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $quotation =
            SurgeryQuotation::with([
                'quotationRequest',
                'hospital',
            ])->findOrFail($id);

        $hospitals =
            Hospital::where(
                'status',
                1
            )
                ->orderBy(
                    'hospital_name'
                )
                ->get();

        return view(
            'admin.surgery_quotation_requests.quotation_edit',
            compact(
                'quotation',
                'hospitals'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE HOSPITAL QUOTATION
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $quotation = SurgeryQuotation::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'hospital_id' => [
                'required',
                'exists:hospitals,id',

                Rule::unique('surgery_quotations', 'hospital_id')
                    ->where(function ($query) use ($quotation) {
                        return $query->where(
                            'surgery_quotation_request_id',
                            $quotation->surgery_quotation_request_id
                        );
                    })
                    ->ignore($quotation->id),
            ],

            'amount' =>
                'required|numeric|min:0',

            'discount' =>
                'nullable|numeric|min:0',

            'tax' =>
                'nullable|numeric|min:0',

            'quotation_details' =>
                'nullable|string',

            'included_services' =>
                'nullable|string',

            'excluded_services' =>
                'nullable|string',

            'valid_until' =>
                'nullable|date',

            'admin_notes' =>
                'nullable|string',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::findOrFail(
            $validated['hospital_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Amount Calculation
        |--------------------------------------------------------------------------
        */

        $amount = (float) $validated['amount'];

        $discount = (float) (
            $validated['discount'] ?? 0
        );

        $tax = (float) (
            $validated['tax'] ?? 0
        );

        $totalAmount =
            $amount
            - $discount
            + $tax;

        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Quotation
        |--------------------------------------------------------------------------
        */

        $quotation->update([

            'hospital_id' =>
                $hospital->id,

            'hospital_name' =>
                $hospital->hospital_name,

            'amount' =>
                $amount,

            'discount' =>
                $discount,

            'tax' =>
                $tax,

            'total_amount' =>
                $totalAmount,

            'quotation_details' =>
                $validated['quotation_details'] ?? null,

            'included_services' =>
                $validated['included_services'] ?? null,

            'excluded_services' =>
                $validated['excluded_services'] ?? null,

            'valid_until' =>
                $validated['valid_until'] ?? null,

            'admin_notes' =>
                $validated['admin_notes'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.surgery-quotation-requests.show',
                $quotation->surgery_quotation_request_id
            )
            ->with(
                'success',
                'Hospital quotation updated successfully.'
            );
    }




    /*
    |--------------------------------------------------------------------------
    | DELETE HOSPITAL QUOTATION
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            $quotation =
                SurgeryQuotation::findOrFail($id);

            $requestId =
                $quotation->surgery_quotation_request_id;

            $quotation->delete();

            return redirect()
                ->route(
                    'admin.surgery-quotation-requests.show',
                    $requestId
                )
                ->with(
                    'success',
                    'Hospital quotation deleted successfully.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Unable to delete quotation: ' .
                    $e->getMessage()
                );
        }
    }
}