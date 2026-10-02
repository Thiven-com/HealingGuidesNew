<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\Prescription;
use App\Models\PrescriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrescriptionRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Prescription Request
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

        $validator = validator($request->all(), [

            'family_member_id' =>
                'nullable|integer|exists:family_members,id',

            'request_type' =>
                'required|in:medicines,lab_tests,both',

            'prescription' =>
                'required|file|mimes:jpg,jpeg,png,pdf|max:10240',

            'notes' =>
                'nullable|string|max:2000',

            'address' =>
                'nullable|string|max:500',

            'city' =>
                'nullable|string|max:100',

            'state' =>
                'nullable|string|max:100',

            'pincode' =>
                'nullable|string|max:20',

            'latitude' =>
                'nullable|numeric',

            'longitude' =>
                'nullable|numeric',
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
        | Family Member Validation
        |--------------------------------------------------------------------------
        */

        $familyMember = null;

        if ($request->filled('family_member_id')) {

            $familyMember = FamilyMember::where('id', $request->family_member_id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$familyMember) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Family member not found.',
                ], 404);
            }
        }

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Upload Prescription
            |--------------------------------------------------------------------------
            */

            $prescriptionPath = null;

            if ($request->hasFile('prescription')) {

                $file = $request->file('prescription');

                $fileName =
                    'prescription_' .
                    time() .
                    '_' .
                    Str::random(8) .
                    '.' .
                    $file->getClientOriginalExtension();

                $prescriptionPath = $file->storeAs(
                    'prescriptions',
                    $fileName,
                    'public'
                );
            }
            /*
            |--------------------------------------------------------------------------
            | Create Prescription Request
            |--------------------------------------------------------------------------
            */

            $prescriptionRequest = PrescriptionRequest::create([

                'customer_id' =>
                    $customer->id,

                'family_member_id' =>
                    $familyMember?->id,

                'request_type' =>
                    $request->request_type,

                'prescription_image' =>
                    $prescriptionPath,

                'notes' =>
                    $request->notes,

                'address' =>
                    $request->address,

                'city' =>
                    $request->city,

                'state' =>
                    $request->state,

                'pincode' =>
                    $request->pincode,

                'latitude' =>
                    $request->latitude,

                'longitude' =>
                    $request->longitude,

                'status' =>
                    'pending',
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Load Relations
            |--------------------------------------------------------------------------
            */

            $prescriptionRequest->load([
                'customer',
                'familyMember',
                'prescription',
                // 'quotations',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => 1,

                'message' =>
                    'Prescription request submitted successfully.',

                'data' => [

                    'id' =>
                        $prescriptionRequest->id,

                    'customer_id' =>
                        $prescriptionRequest->customer_id,

                    'family_member_id' =>
                        $prescriptionRequest->family_member_id,

                    'prescription_id' =>
                        $prescriptionRequest->prescription_id,

                    'request_type' =>
                        $prescriptionRequest->request_type,

                    'prescription_image' =>
                        $prescriptionRequest->prescription_image
                        ? asset($prescriptionRequest->prescription_image)
                        : null,

                    'notes' =>
                        $prescriptionRequest->notes,

                    'address' =>
                        $prescriptionRequest->address,

                    'city' =>
                        $prescriptionRequest->city,

                    'state' =>
                        $prescriptionRequest->state,

                    'pincode' =>
                        $prescriptionRequest->pincode,

                    'latitude' =>
                        $prescriptionRequest->latitude,

                    'longitude' =>
                        $prescriptionRequest->longitude,

                    'status' =>
                        $prescriptionRequest->status,

                    'created_at' =>
                        $prescriptionRequest->created_at,
                ],
            ], 201);

        } catch (\Throwable $e) {

            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | Remove Uploaded File If Database Creation Failed
            |--------------------------------------------------------------------------
            */

            if (!empty($prescriptionPath)) {

                Storage::disk('public')
                    ->delete($prescriptionPath);
            }

            return response()->json([
                'success' => 0,
                'message' =>
                    'Unable to submit prescription request.',
                'error' =>
                    $e->getMessage(),
            ], 500);
        }
    }
}