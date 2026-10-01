<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\BookAdmission;
use App\Models\Doctor;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BookAdmissionController extends Controller
{
    /**
     * Get admission form data.
     */
    public function alldata(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $familyMembers = FamilyMember::where('customer_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($familyMember) {
                return [
                    'id' => $familyMember->id,
                    'name' => $familyMember->name,
                    'mobile' => $familyMember->mobile ?? null,
                    'gender' => $familyMember->gender ?? null,
                    'dob' => $familyMember->dob ?? null,
                ];
            });

        $doctors = Doctor::where('status', 1)
            ->latest()
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'doctor_name' => $doctor->doctor_name,
                    'qualification' => $doctor->qualification ?? null,
                    'designation' => $doctor->designation ?? null,
                    'image' => $doctor->image ?? null,
                ];
            });

        return response()->json([
            'success' => 1,
            'data' => [
                'admission_types' => [
                    [
                        'value' => 'general_admission',
                        'name' => 'General Admission',
                    ],
                    [
                        'value' => 'surgery_admission',
                        'name' => 'Surgery Admission',
                    ],
                ],

                'family_members' => $familyMembers,

                'doctors' => $doctors,
            ],
            'message' => 'Admission form data fetched successfully'
        ]);
    }


    /**
     * Create admission request.
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'family_member_id' => [
                'required',
                Rule::exists('family_members', 'id')
                    ->where(function ($query) use ($user) {
                        $query->where('customer_id', $user->id);
                    }),
            ],

            'admission_type' => [
                'required',
                'in:general_admission,surgery_admission'
            ],

            'preferred_admission_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],

            'surgery_procedure' => [
                'nullable',
                'string',
                'max:255'
            ],

            'preferred_doctor_id' => [
                'nullable',
                'exists:doctors,id'
            ],

            'additional_information' => [
                'nullable',
                'string'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Surgery / Procedure required for Surgery Admission
        |--------------------------------------------------------------------------
        */

        if (
            $request->admission_type === 'surgery_admission'
            && empty($request->surgery_procedure)
        ) {
            return response()->json([
                'success' => 0,
                'message' => 'Surgery / Procedure is required for Surgery Admission.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Book Admission
        |--------------------------------------------------------------------------
        */

        $bookAdmission = BookAdmission::create([
            'customer_id' => $user->id,
            'family_member_id' => $request->family_member_id,
            'admission_type' => $request->admission_type,
            'preferred_admission_date' => $request->preferred_admission_date,
            'surgery_procedure' => $request->surgery_procedure,
            'preferred_doctor_id' => $request->preferred_doctor_id,
            'additional_information' => $request->additional_information,
            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $bookAdmission->load([
            'familyMember',
            'preferredDoctor'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,

            'data' => [
                'id' => $bookAdmission->id,

                'family_member' => $bookAdmission->familyMember
                    ? [
                        'id' => $bookAdmission->familyMember->id,
                        'name' => $bookAdmission->familyMember->name,
                    ]
                    : null,

                'admission_type' => $bookAdmission->admission_type,

                'admission_type_name' => $bookAdmission->admission_type === 'general_admission'
                    ? 'General Admission'
                    : 'Surgery Admission',

                'preferred_admission_date' => $bookAdmission->preferred_admission_date
                    ? $bookAdmission->preferred_admission_date->format('Y-m-d')
                    : null,

                'surgery_procedure' => $bookAdmission->surgery_procedure,

                'preferred_doctor' => $bookAdmission->preferredDoctor
                    ? [
                        'id' => $bookAdmission->preferredDoctor->id,
                        'doctor_name' => $bookAdmission->preferredDoctor->doctor_name,
                    ]
                    : null,

                'additional_information' => $bookAdmission->additional_information,

                'status' => $bookAdmission->status,

                'created_at' => $bookAdmission->created_at,
            ],

            'message' => 'Admission request submitted successfully'
        ], 201);
    }


    /**
     * Get customer's admission requests.
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $bookAdmissions = BookAdmission::with([
            'familyMember',
            'preferredDoctor'
        ])
            ->where('customer_id', $user->id)
            ->latest()
            ->paginate(20);

        $data = $bookAdmissions->getCollection()->map(function ($bookAdmission) {
            return [
                'id' => $bookAdmission->id,

                'family_member' => $bookAdmission->familyMember
                    ? [
                        'id' => $bookAdmission->familyMember->id,
                        'name' => $bookAdmission->familyMember->name,
                    ]
                    : null,

                'admission_type' => $bookAdmission->admission_type,

                'admission_type_name' => $bookAdmission->admission_type === 'general_admission'
                    ? 'General Admission'
                    : 'Surgery Admission',

                'preferred_admission_date' => $bookAdmission->preferred_admission_date
                    ? $bookAdmission->preferred_admission_date->format('Y-m-d')
                    : null,

                'surgery_procedure' => $bookAdmission->surgery_procedure,

                'preferred_doctor' => $bookAdmission->preferredDoctor
                    ? [
                        'id' => $bookAdmission->preferredDoctor->id,
                        'doctor_name' => $bookAdmission->preferredDoctor->doctor_name,
                    ]
                    : null,

                'additional_information' => $bookAdmission->additional_information,

                'status' => $bookAdmission->status,

                'created_at' => $bookAdmission->created_at,
            ];
        });

        $bookAdmissions->setCollection($data);

        return response()->json([
            'success' => 1,
            'data' => $bookAdmissions,
            'message' => 'Admission requests fetched successfully'
        ]);
    }
}
