<?php

namespace App\Http\Controllers\MarketingApp;

use App\Http\Controllers\Controller;
use App\Models\MembershipRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembershipRegistrationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Static Memberships
    |--------------------------------------------------------------------------
    */

    private function membershipLists()
    {
        return [
            [
                'id' => 'premium_cash',
                'name' => 'Premium Cash',
                'description' => 'Premium Cash Membership',
                'color' => '#C026D3',
            ],
            [
                'id' => 'car_insurance',
                'name' => 'Car Insurance',
                'description' => 'Car Insurance Membership',
                'color' => '#38BDF8',
            ],
            [
                'id' => 'personal_health_insurance',
                'name' => 'Personal Health Insurance',
                'description' => 'Personal Health Insurance Membership',
                'color' => '#22C55E',
            ],
            [
                'id' => 'employee_health_insurance',
                'name' => 'Employee Health Insurance',
                'description' => 'Employee Health Insurance Membership',
                'color' => '#F97316',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Membership List
    |--------------------------------------------------------------------------
    */

    public function memberships(Request $request)
    {
        return response()->json([
            'success' => 1,
            'message' => 'Memberships Fetched Successfully',
            'data' => $this->membershipLists(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Register Membership
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [
                'full_name' =>
                    'required|string|max:255',

                'mobile_number' =>
                    'required|string|max:20',

                'email' =>
                    'nullable|email|max:255',

                'date_of_birth' =>
                    'required|date',

                'gender' =>
                    'required|in:male,female,other',

                'city' =>
                    'required|string|max:100',

                'membership' =>
                    'required|string|in:premium_cash,car_insurance,personal_health_insurance,employee_health_insurance',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Create Registration
        |--------------------------------------------------------------------------
        */

        $registration = MembershipRegistration::create([

            'full_name' =>
                trim($request->full_name),

            'mobile_number' =>
                trim($request->mobile_number),

            'email' =>
                $request->email
                ? trim($request->email)
                : null,

            'date_of_birth' =>
                $request->date_of_birth,

            'gender' =>
                $request->gender,

            'city' =>
                trim($request->city),

            'membership' =>
                $request->membership,

            'status' =>
                1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'message' => 'Membership Registration Successful',
            'data' => $this->registrationResponse($registration),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Registration Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $registration = MembershipRegistration::find($id);

        if (!$registration) {

            return response()->json([
                'success' => 0,
                'message' => 'Membership Registration Not Found'
            ], 404);
        }


        return response()->json([
            'success' => 1,
            'message' => 'Membership Registration Fetched Successfully',
            'data' => $this->registrationResponse($registration),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Registration Response
    |--------------------------------------------------------------------------
    */

    private function registrationResponse($registration)
    {
        $membership = collect($this->membershipLists())
            ->firstWhere('id', $registration->membership);


        return [

            'id' =>
                $registration->id,

            'full_name' =>
                $registration->full_name,

            'mobile_number' =>
                $registration->mobile_number,

            'email' =>
                $registration->email,

            'date_of_birth' =>
                $registration->date_of_birth
                ? $registration->date_of_birth->format('Y-m-d')
                : null,

            'gender' =>
                $registration->gender,

            'city' =>
                $registration->city,

            /*
            |--------------------------------------------------------------------------
            | Membership
            |--------------------------------------------------------------------------
            */

            'membership' => $membership,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                (bool) $registration->status,

            'created_at' =>
                $registration->created_at
                ? $registration->created_at->format('Y-m-d H:i:s')
                : null,
        ];
    }
}
