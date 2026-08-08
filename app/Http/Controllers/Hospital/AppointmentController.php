<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Appointment List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $query = DoctorAppointment::with([
            'doctor',
            'customer',
            'familyMember'
        ])
        ->where('hospital_id', $hospital->id);


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'appointment_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'token_no',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('doctor', function ($doctor) use ($search) {

                    $doctor->where(
                        'doctor_name',
                        'like',
                        "%{$search}%"
                    );

                })
                ->orWhereHas('customer', function ($customer) use ($search) {

                    $customer->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Appointment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'appointment_status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Consultation Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('consultation_type')) {

            $query->where(
                'consultation_type',
                $request->consultation_type
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Doctor
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor_id')) {

            $query->where(
                'doctor_id',
                $request->doctor_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            $query->whereDate(
                'appointment_date',
                $request->date
            );
        }


        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Hospital Doctors
        |--------------------------------------------------------------------------
        */

        $doctors = \App\Models\Doctor::where(
            'hospital_id',
            $hospital->id
        )
        ->where('status', 1)
        ->orderBy('doctor_name')
        ->get();


        return view(
            'hospital.appointments.index',
            compact(
                'appointments',
                'doctors'
            )
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Appointment Details
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $appointment = DoctorAppointment::with([
            'doctor.hospitalSpecialization.specialization',
            'customer',
            'familyMember',
            'doctorSchedule'
        ])
        ->where(
            'hospital_id',
            $hospital->id
        )
        ->where(
            'id',
            $id
        )
        ->firstOrFail();


        return view(
            'hospital.appointments.show',
            compact('appointment')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Update Appointment Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();


        $validator = Validator::make(
            $request->all(),
            [
                'id' =>
                    'required|integer',

                'appointment_status' =>
                    'required|string',

                'remarks' =>
                    'nullable|string|max:1000',

                'cancel_reason' =>
                    'nullable|string|max:1000',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' =>
                    $validator->errors()->first()
            ]);
        }


        $appointment = DoctorAppointment::where(
            'hospital_id',
            $hospital->id
        )
        ->where(
            'id',
            $request->id
        )
        ->first();


        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Appointment Not Found'
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent changes after final status
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $appointment->appointment_status,
                [
                    'completed',
                    'cancelled'
                ]
            )
        ) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'This appointment is already '
                    . $appointment->appointment_status
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Cancel
        |--------------------------------------------------------------------------
        */

        if (
            $request->appointment_status ===
            'cancelled'
        ) {

            if (!$request->filled('cancel_reason')) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'Cancel reason is required'
                ]);
            }


            $appointment->cancel_reason =
                $request->cancel_reason;

            $appointment->cancelled_at =
                now();
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $appointment->appointment_status =
            $request->appointment_status;


        if ($request->filled('remarks')) {

            $appointment->remarks =
                $request->remarks;
        }


        $appointment->save();


        return response()->json([
            'success' => 1,
            'message' =>
                'Appointment Status Updated Successfully'
        ]);
    }
}