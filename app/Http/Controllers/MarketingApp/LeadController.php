<?php

namespace App\Http\Controllers\MarketingApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarketingApp\LeadCollection;
use App\Models\MarketingLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function leads(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        $query = MarketingLead::with('marketingStaff')
            ->where(
                'marketing_staff_id',
                $staff->id
            );


        /*
        |--------------------------------------------------------------------------
        | Lead Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lead_type')) {

            $query->where(
                'lead_type',
                $request->lead_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Lead Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lead_status')) {

            $query->where(
                'lead_status',
                $request->lead_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Priority Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {

            $query->where(
                'priority',
                $request->priority
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'mobile',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'email',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'organization_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'contact_person',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'city',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }


        $leads = $query
            ->latest()
            ->get();


        return response()->json([
            'success' => 1,
            'message' => 'Leads Fetched Successfully',

            'data' => new LeadCollection(
                $leads
            )
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Lead Details
    |--------------------------------------------------------------------------
    */

    public function leadDetails($id)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        $lead = MarketingLead::with('marketingStaff')
            ->where(
                'marketing_staff_id',
                $staff->id
            )
            ->where(
                'id',
                $id
            )
            ->first();


        if (!$lead) {

            return response()->json([
                'success' => 0,
                'message' => 'Lead Not Found'
            ], 404);
        }


        return response()->json([
            'success' => 1,
            'message' => 'Lead Details Fetched Successfully',

            'data' => new LeadCollection(
                collect([$lead])
            )
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Add Lead
    |--------------------------------------------------------------------------
    */

    public function addLead(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'lead_type' =>
                'required|in:hospital,doctor,customer',

            'name' =>
                'required|string|max:255',

            'mobile' =>
                'nullable|digits:10',

            'alternate_mobile' =>
                'nullable|digits:10',

            'email' =>
                'nullable|email|max:255',

            'organization_name' =>
                'nullable|string|max:255',

            'contact_person' =>
                'nullable|string|max:255',

            'specialization' =>
                'nullable|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'address' =>
                'nullable|string',

            'country' =>
                'nullable|string|max:100',

            'state' =>
                'nullable|string|max:100',

            'city' =>
                'nullable|string|max:100',

            'pincode' =>
                'nullable|string|max:10',

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',

            'source' =>
                'nullable|string|max:100',

            'priority' =>
                'nullable|in:low,normal,high',

            'next_followup_at' =>
                'nullable|date',

            'notes' =>
                'nullable|string',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        if ($request->filled('mobile')) {

            $exists = MarketingLead::where(
                'marketing_staff_id',
                $staff->id
            )
                ->where(
                    'lead_type',
                    $request->lead_type
                )
                ->where(
                    'mobile',
                    $request->mobile
                )
                ->exists();


            if ($exists) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Lead Already Exists With This Mobile Number'
                ]);
            }
        }


        $lead = new MarketingLead();

        $lead->marketing_staff_id =
            $staff->id;

        $lead->lead_type =
            $request->lead_type;

        $lead->name =
            trim($request->name);

        $lead->mobile =
            $request->mobile;

        $lead->alternate_mobile =
            $request->alternate_mobile;

        $lead->email =
            $request->email;

        $lead->organization_name =
            $request->organization_name;

        $lead->contact_person =
            $request->contact_person;

        $lead->specialization =
            $request->specialization;

        $lead->qualification =
            $request->qualification;

        $lead->address =
            $request->address;

        $lead->country =
            $request->country ?? 'India';

        $lead->state =
            $request->state;

        $lead->city =
            $request->city;

        $lead->pincode =
            $request->pincode;

        $lead->latitude =
            $request->latitude;

        $lead->longitude =
            $request->longitude;

        $lead->source =
            $request->source;

        $lead->priority =
            $request->priority ?? 'normal';

        $lead->lead_status =
            'new';

        $lead->next_followup_at =
            $request->next_followup_at;

        $lead->notes =
            $request->notes;

        $lead->status = 1;

        $lead->save();


        $lead->load('marketingStaff');


        return response()->json([
            'success' => 1,
            'message' => 'Lead Added Successfully',

            'data' => new LeadCollection(
                collect([$lead])
            )
        ]);
    }

    public function updateLead(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'lead_id' =>
                'required|integer',

            'lead_type' =>
                'nullable|in:hospital,doctor,customer',

            'name' =>
                'nullable|string|max:255',

            'mobile' =>
                'nullable|digits:10',

            'alternate_mobile' =>
                'nullable|digits:10',

            'email' =>
                'nullable|email|max:255',

            'organization_name' =>
                'nullable|string|max:255',

            'contact_person' =>
                'nullable|string|max:255',

            'specialization' =>
                'nullable|string|max:255',

            'qualification' =>
                'nullable|string|max:255',

            'address' =>
                'nullable|string',

            'country' =>
                'nullable|string|max:100',

            'state' =>
                'nullable|string|max:100',

            'city' =>
                'nullable|string|max:100',

            'pincode' =>
                'nullable|string|max:10',

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',

            'source' =>
                'nullable|string|max:100',

            'priority' =>
                'nullable|in:low,normal,high',

            'next_followup_at' =>
                'nullable|date',

            'notes' =>
                'nullable|string',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $lead = MarketingLead::where(
            'id',
            $request->lead_id
        )
            ->where(
                'marketing_staff_id',
                $staff->id
            )
            ->first();


        if (!$lead) {

            return response()->json([
                'success' => 0,
                'message' => 'Lead Not Found'
            ], 404);
        }


        $leadType = $request->has('lead_type')
            ? $request->lead_type
            : $lead->lead_type;


        if ($request->filled('mobile')) {

            $exists = MarketingLead::where(
                'marketing_staff_id',
                $staff->id
            )
                ->where(
                    'lead_type',
                    $leadType
                )
                ->where(
                    'mobile',
                    $request->mobile
                )
                ->where(
                    'id',
                    '!=',
                    $lead->id
                )
                ->exists();


            if ($exists) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Lead Already Exists With This Mobile Number'
                ]);
            }
        }

        if ($request->has('lead_type')) {
            $lead->lead_type =
                $request->lead_type;
        }

        if ($request->has('name')) {
            $lead->name =
                trim($request->name);
        }

        if ($request->has('mobile')) {
            $lead->mobile =
                $request->mobile;
        }

        if ($request->has('alternate_mobile')) {
            $lead->alternate_mobile =
                $request->alternate_mobile;
        }

        if ($request->has('email')) {
            $lead->email =
                $request->email;
        }

        if ($request->has('organization_name')) {
            $lead->organization_name =
                $request->organization_name;
        }

        if ($request->has('contact_person')) {
            $lead->contact_person =
                $request->contact_person;
        }

        if ($request->has('specialization')) {
            $lead->specialization =
                $request->specialization;
        }

        if ($request->has('qualification')) {
            $lead->qualification =
                $request->qualification;
        }

        if ($request->has('address')) {
            $lead->address =
                $request->address;
        }

        if ($request->has('country')) {
            $lead->country =
                $request->country;
        }

        if ($request->has('state')) {
            $lead->state =
                $request->state;
        }

        if ($request->has('city')) {
            $lead->city =
                $request->city;
        }

        if ($request->has('pincode')) {
            $lead->pincode =
                $request->pincode;
        }

        if ($request->has('latitude')) {
            $lead->latitude =
                $request->latitude;
        }

        if ($request->has('longitude')) {
            $lead->longitude =
                $request->longitude;
        }

        if ($request->has('source')) {
            $lead->source =
                $request->source;
        }

        if ($request->has('priority')) {
            $lead->priority =
                $request->priority;
        }

        if ($request->has('next_followup_at')) {
            $lead->next_followup_at =
                $request->next_followup_at;
        }

        if ($request->has('notes')) {
            $lead->notes =
                $request->notes;
        }


        $lead->save();

        $lead->load('marketingStaff');


        return response()->json([
            'success' => 1,
            'message' => 'Lead Updated Successfully',

            'data' => new LeadCollection(
                collect([$lead])
            )
        ]);
    }


    public function updateStatus(Request $request)
    {
        $staff = auth('sanctum')->user();

        if (!$staff) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        $validator = Validator::make($request->all(), [

            'lead_id' =>
                'required|integer',

            'lead_status' =>
                'required|in:new,contacted,follow_up,interested,not_interested,converted,closed',

            'notes' =>
                'nullable|string',

            'next_followup_at' =>
                'nullable|date',

            'converted_id' =>
                'nullable|integer',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $lead = MarketingLead::where(
            'id',
            $request->lead_id
        )
            ->where(
                'marketing_staff_id',
                $staff->id
            )
            ->first();


        if (!$lead) {

            return response()->json([
                'success' => 0,
                'message' => 'Lead Not Found'
            ], 404);
        }


        $lead->lead_status =
            $request->lead_status;


        if ($request->has('notes')) {

            $lead->notes =
                $request->notes;
        }


        if ($request->has('next_followup_at')) {

            $lead->next_followup_at =
                $request->next_followup_at;
        }


        if ($request->lead_status === 'converted') {

            if (!$request->filled('converted_id')) {

                return response()->json([
                    'success' => 0,
                    'message' => 'Converted ID Is Required'
                ]);
            }


            $lead->converted_id =
                $request->converted_id;

            $lead->converted_at =
                now();

            $lead->next_followup_at =
                null;
        }


        if ($request->lead_status !== 'converted') {

            $lead->converted_id =
                null;

            $lead->converted_at =
                null;
        }


        $lead->save();

        $lead->load('marketingStaff');


        return response()->json([
            'success' => 1,
            'message' => 'Lead Status Updated Successfully',

            'data' => new LeadCollection(
                collect([$lead])
            )
        ]);
    }
}