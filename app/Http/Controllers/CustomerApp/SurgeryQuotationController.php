<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\Surgery;
use App\Models\SurgeryQuotation;
use App\Models\SurgeryQuotationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SurgeryQuotationController extends Controller
{
    /**
     * Submit Surgery Quotation Request
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

        $validator = Validator::make($request->all(), [

            'surgery_id' =>
                'required|integer|exists:surgeries,id',

            'family_member_id' =>
                'required|integer|exists:family_members,id',

            'prescription' =>
                'required|file|mimes:jpg,jpeg,png,pdf|max:10240',

            'notes' =>
                'nullable|string|max:2000',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

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
                'message' => 'Family Member Not Found',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Surgery
        |--------------------------------------------------------------------------
        */

        $surgery = Surgery::where('id', $request->surgery_id)
            ->where('status', 1)
            ->first();

        if (!$surgery) {
            return response()->json([
                'success' => 0,
                'message' => 'Surgery Not Found',
            ], 404);
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Upload Prescription
            |--------------------------------------------------------------------------
            */

            $prescription = null;

            if ($request->hasFile('prescription')) {

                $file = $request->file('prescription');

                $directory = public_path(
                    'uploads/surgery_prescriptions'
                );

                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $filename =
                    time() . '_' .
                    Str::random(10) . '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    $directory,
                    $filename
                );

                $prescription =
                    'uploads/surgery_prescriptions/' . $filename;
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Request Number
            |--------------------------------------------------------------------------
            */

            do {

                $requestNo =
                    'SURQ' .
                    date('YmdHis') .
                    strtoupper(Str::random(4));

            } while (
                SurgeryQuotationRequest::where(
                    'request_no',
                    $requestNo
                )->exists()
            );

            /*
            |--------------------------------------------------------------------------
            | Create Request
            |--------------------------------------------------------------------------
            */

            $quotationRequest =
                SurgeryQuotationRequest::create([

                    'request_no' =>
                        $requestNo,

                    'customer_id' =>
                        $customer->id,

                    'family_member_id' =>
                        $familyMember->id,

                    'surgery_id' =>
                        $surgery->id,

                    'prescription' =>
                        $prescription,

                    'notes' =>
                        $request->notes,

                    'status' =>
                        'pending',

                ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Relations
            |--------------------------------------------------------------------------
            */

            $quotationRequest->load([
                'familyMember',
                'surgery',
            ]);

            return response()->json([

                'success' => 1,

                'message' =>
                    'Surgery quotation request submitted successfully.',

                'data' => [

                    'id' =>
                        $quotationRequest->id,

                    'request_no' =>
                        $quotationRequest->request_no,

                    'status' =>
                        $quotationRequest->status,

                    'prescription' =>
                        $quotationRequest->prescription
                            ? asset($quotationRequest->prescription)
                            : null,

                    'notes' =>
                        $quotationRequest->notes,

                    'family_member' => [

                        'id' =>
                            optional(
                                $quotationRequest->familyMember
                            )->id,

                        'name' =>
                            optional(
                                $quotationRequest->familyMember
                            )->name,

                        'relationship' =>
                            optional(
                                $quotationRequest->familyMember
                            )->relationship,

                    ],

                    'surgery' => [

                        'id' =>
                            optional(
                                $quotationRequest->surgery
                            )->id,

                        'name' =>
                            optional(
                                $quotationRequest->surgery
                            )->name,

                        'slug' =>
                            optional(
                                $quotationRequest->surgery
                            )->slug,

                        'image' =>
                            optional(
                                $quotationRequest->surgery
                            )->image
                                ? asset(
                                    $quotationRequest->surgery->image
                                )
                                : null,

                    ],

                    'created_at' =>
                        $quotationRequest->created_at,

                ],
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' =>
                    'Unable to submit surgery quotation request.',
                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get customer's surgery quotation requests.
     */
    public function index(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $query = SurgeryQuotationRequest::with([
            'familyMember',
            'surgery',
            'quotations.hospital',
        ])
            ->where(
                'customer_id',
                $customer->id
            );

        /*
        |--------------------------------------------------------------------------
        | Optional Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }
        if ($request->filled('id')) {

            $query->where(
                'id',
                $request->id
            );
        }

        $requests = $query
            ->latest()
            ->get();

        return response()->json([

            'success' => 1,

            'message' =>
                'Surgery quotation requests fetched successfully.',

            'data' => $requests->map(
                function ($request) {

                    return $this->formatRequest(
                        $request
                    );

                }
            )->values(),

        ]);
    }


    /**
     * Get single quotation request.
     */
    public function show($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $quotationRequest =
            SurgeryQuotationRequest::with([
                'familyMember',
                'surgery',
                'quotations.hospital',
            ])
                ->where('id', $id)
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->first();

        if (!$quotationRequest) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Surgery quotation request not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([

            'success' => 1,

            'message' =>
                'Surgery quotation request fetched successfully.',

            'data' =>
                $this->formatRequest(
                    $quotationRequest
                ),

        ]);
    }


    /**
     * Accept quotation.
     */
    public function acceptQuotation($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        DB::beginTransaction();

        try {

            $quotation =
                SurgeryQuotation::with('request')
                    ->where('id', $id)
                    ->whereHas(
                        'request',
                        function ($query) use ($customer) {

                            $query->where(
                                'customer_id',
                                $customer->id
                            );
                        }
                    )
                    ->lockForUpdate()
                    ->first();

            if (!$quotation) {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Quotation not found.',
                ], 404);
            }

            if ($quotation->status !== 'sent') {

                DB::rollBack();

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This quotation cannot be accepted.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Expiry
            |--------------------------------------------------------------------------
            */

            if (
                $quotation->valid_until &&
                $quotation->valid_until->isPast()
            ) {

                $quotation->update([
                    'status' => 'expired',
                ]);

                DB::commit();

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This quotation has expired.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Reject Other Quotations
            |--------------------------------------------------------------------------
            */

            SurgeryQuotation::where(
                'surgery_quotation_request_id',
                $quotation->surgery_quotation_request_id
            )
                ->where(
                    'id',
                    '!=',
                    $quotation->id
                )
                ->where(
                    'status',
                    'sent'
                )
                ->update([
                    'status' =>
                        'rejected',

                    'rejected_at' =>
                        now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Accept Selected Quotation
            |--------------------------------------------------------------------------
            */

            $quotation->status =
                'accepted';

            $quotation->accepted_at =
                now();

            $quotation->save();

            /*
            |--------------------------------------------------------------------------
            | Complete Request
            |--------------------------------------------------------------------------
            */

            $quotation->request->update([
                'status' =>
                    'completed',
            ]);

            DB::commit();

            $quotation->load([
                'hospital',
                'request.surgery',
                'request.familyMember',
            ]);

            return response()->json([

                'success' => 1,

                'message' =>
                    'Surgery quotation accepted successfully.',

                'data' => [

                    'quotation_id' =>
                        $quotation->id,

                    'request_id' =>
                        $quotation->surgery_quotation_request_id,

                    'hospital' => [

                        'id' =>
                            optional(
                                $quotation->hospital
                            )->id,

                        'name' =>
                            $quotation->hospital_name
                            ??
                            optional(
                                $quotation->hospital
                            )->hospital_name,

                    ],

                    'surgery' => [

                        'id' =>
                            optional(
                                $quotation->request->surgery
                            )->id,

                        'name' =>
                            optional(
                                $quotation->request->surgery
                            )->name,

                    ],

                    'family_member' => [

                        'id' =>
                            optional(
                                $quotation->request->familyMember
                            )->id,

                        'name' =>
                            optional(
                                $quotation->request->familyMember
                            )->name,

                    ],

                    'total_amount' =>
                        (float)
                        $quotation->total_amount,

                    'status' =>
                        $quotation->status,

                    'accepted_at' =>
                        $quotation->accepted_at,

                ],
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => 0,
                'message' =>
                    'Unable to accept quotation.',
                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Reject quotation.
     */
    public function rejectQuotation(
        Request $request,
        $id
    ) {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login',
            ], 401);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'reason' =>
                    'nullable|string|max:1000',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' =>
                    $validator->errors()->first(),
                'errors' =>
                    $validator->errors(),
            ], 422);
        }

        $quotation =
            SurgeryQuotation::where(
                'id',
                $id
            )
                ->whereHas(
                    'request',
                    function ($query) use ($customer) {

                        $query->where(
                            'customer_id',
                            $customer->id
                        );
                    }
                )
                ->first();

        if (!$quotation) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Quotation not found.',
            ], 404);
        }

        if ($quotation->status !== 'sent') {

            return response()->json([
                'success' => 0,
                'message' =>
                    'This quotation cannot be rejected.',
            ], 422);
        }

        $quotation->status =
            'rejected';

        $quotation->rejected_at =
            now();

        $quotation->admin_notes =
            $request->reason;

        $quotation->save();

        return response()->json([

            'success' => 1,

            'message' =>
                'Quotation rejected successfully.',

            'data' => [

                'id' =>
                    $quotation->id,

                'status' =>
                    $quotation->status,

                'reason' =>
                    $quotation->admin_notes,

                'rejected_at' =>
                    $quotation->rejected_at,

            ],

        ]);
    }


    /**
     * Format request response.
     */
    private function formatRequest(
        SurgeryQuotationRequest $request
    ) {
        return [

            'id' =>
                $request->id,

            'request_no' =>
                $request->request_no,

            'status' =>
                $request->status,

            'prescription' =>
                $request->prescription
                    ? asset($request->prescription)
                    : null,

            'notes' =>
                $request->notes,

            /*
            |--------------------------------------------------------------------------
            | Family Member
            |--------------------------------------------------------------------------
            */

            'family_member' => [

                'id' =>
                    optional(
                        $request->familyMember
                    )->id,

                'name' =>
                    optional(
                        $request->familyMember
                    )->name,

                'mobile' =>
                    optional(
                        $request->familyMember
                    )->mobile,

                'relationship' =>
                    optional(
                        $request->familyMember
                    )->relationship,

                'gender' =>
                    optional(
                        $request->familyMember
                    )->gender,

                'dob' =>
                    optional(
                        $request->familyMember
                    )->dob,

                'age' =>
                    optional(
                        $request->familyMember
                    )->age,

                'blood_group' =>
                    optional(
                        $request->familyMember
                    )->blood_group,

                'photo' =>
                    optional(
                        $request->familyMember
                    )->photo
                        ? asset(
                            $request->familyMember->photo
                        )
                        : null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Surgery
            |--------------------------------------------------------------------------
            */

            'surgery' => [

                'id' =>
                    optional(
                        $request->surgery
                    )->id,

                'name' =>
                    optional(
                        $request->surgery
                    )->name,

                'slug' =>
                    optional(
                        $request->surgery
                    )->slug,

                'image' =>
                    optional(
                        $request->surgery
                    )->image
                        ? asset(
                            $request->surgery->image
                        )
                        : null,

                'short_description' =>
                    optional(
                        $request->surgery
                    )->short_description,

            ],

            /*
            |--------------------------------------------------------------------------
            | Quotations
            |--------------------------------------------------------------------------
            */

            'quotation_count' =>
                $request->quotations->count(),

            'quotations' =>
                $request->quotations->map(
                    function ($quotation) {

                        return [

                            'id' =>
                                $quotation->id,

                            'hospital' => [

                                'id' =>
                                    optional(
                                        $quotation->hospital
                                    )->id,

                                'name' =>
                                    $quotation->hospital_name
                                    ??
                                    optional(
                                        $quotation->hospital
                                    )->hospital_name,

                            ],

                            'amount' =>
                                (float)
                                $quotation->amount,

                            'discount' =>
                                (float)
                                $quotation->discount,

                            'tax' =>
                                (float)
                                $quotation->tax,

                            'total_amount' =>
                                (float)
                                $quotation->total_amount,

                            'quotation_details' =>
                                $quotation->quotation_details,

                            'included_services' =>
                                $quotation->included_services,

                            'excluded_services' =>
                                $quotation->excluded_services,

                            'valid_until' =>
                                $quotation->valid_until
                                    ? $quotation->valid_until
                                    : null,

                            'status' =>
                                $quotation->status,

                            'sent_at' =>
                                $quotation->sent_at,

                            'accepted_at' =>
                                $quotation->accepted_at,

                            'rejected_at' =>
                                $quotation->rejected_at,

                            'created_at' =>
                                $quotation->created_at,

                        ];
                    }
                )->values(),

            'created_at' =>
                $request->created_at,

            'updated_at' =>
                $request->updated_at,

        ];
    }
}