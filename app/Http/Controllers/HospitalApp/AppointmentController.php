<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorAppointmentCollection;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hospital Appointments
    |--------------------------------------------------------------------------
    */

    public function appointments(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Appointments
        |--------------------------------------------------------------------------
        |
        | Only appointments belonging to doctors of logged-in hospital.
        |
        */

        $appointments = DoctorAppointment::with([
            'doctor',
            'customer',
            'familyMember'
        ])
            ->whereHas('doctor', function ($query) use ($hospital) {

                $query->where(
                    'hospital_id',
                    $hospital->id
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Appointment ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {

            $appointments->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor_id')) {

            $appointments->where(
                'doctor_id',
                $request->doctor_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $appointments->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Appointment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('appointment_status')) {

            $appointments->where(
                'appointment_status',
                $request->appointment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Consultation Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('consultation_type')) {

            $appointments->where(
                'consultation_type',
                $request->consultation_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Appointment Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('appointment_date')) {

            $appointments->whereDate(
                'appointment_date',
                $request->appointment_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $appointments->whereDate(
                'appointment_date',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $appointments->whereDate(
                'appointment_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $appointments->where(function ($query) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Appointment Number
                |--------------------------------------------------------------------------
                */

                $query->where(
                    'appointment_no',
                    'LIKE',
                    '%' . $search . '%'
                );

                /*
                |--------------------------------------------------------------------------
                | Doctor
                |--------------------------------------------------------------------------
                */

                $query->orWhereHas(
                    'doctor',
                    function ($doctorQuery) use ($search) {

                        $doctorQuery->where(
                            'doctor_name',
                            'LIKE',
                            '%' . $search . '%'
                        );
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Customer
                |--------------------------------------------------------------------------
                */

                $query->orWhereHas(
                    'customer',
                    function ($customerQuery) use ($search) {

                        $customerQuery
                            ->where(
                                'name',
                                'LIKE',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'mobile',
                                'LIKE',
                                '%' . $search . '%'
                            );
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Family Member
                |--------------------------------------------------------------------------
                */

                $query->orWhereHas(
                    'familyMember',
                    function ($familyQuery) use ($search) {

                        $familyQuery->where(
                            'name',
                            'LIKE',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Latest Appointments
        |--------------------------------------------------------------------------
        */

        $appointments = $appointments
            ->orderBy('appointment_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => 1,
            'message' => 'Appointments Fetched Successfully',
            'data' => new DoctorAppointmentCollection($appointments),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment Details
    |--------------------------------------------------------------------------
    */

    public function appointmentDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::with([
            'doctor',
            'customer',
            'familyMember'
        ])
            ->where('id', $id)

            ->whereHas('doctor', function ($query) use ($hospital) {

                $query->where(
                    'hospital_id',
                    $hospital->id
                );
            })

            ->first();

        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' => 'Appointment Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Appointment Details Fetched Successfully',
            'data' => new DoctorAppointmentCollection(collect([$appointment])),
        ]);
    }

    public function appointmentSummary(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = DoctorAppointment::whereHas(
            'doctor',
            function ($query) use ($hospital) {

                $query->where(
                    'hospital_id',
                    $hospital->id
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Doctor Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('doctor_id')) {

            $baseQuery->where(
                'doctor_id',
                $request->doctor_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Total Appointments
        |--------------------------------------------------------------------------
        */

        $totalAppointments =
            (clone $baseQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Today's Appointments
        |--------------------------------------------------------------------------
        */

        $todayAppointments =
            (clone $baseQuery)
                ->whereDate(
                    'appointment_date',
                    today()
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Appointments
        |--------------------------------------------------------------------------
        */

        $upcomingAppointments =
            (clone $baseQuery)
                ->whereDate(
                    'appointment_date',
                    '>',
                    today()
                )
                ->whereNotIn(
                    'appointment_status',
                    [
                        'cancelled',
                        'completed'
                    ]
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Completed Appointments
        |--------------------------------------------------------------------------
        */

        $completedAppointments =
            (clone $baseQuery)
                ->where(
                    'appointment_status',
                    'completed'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Cancelled Appointments
        |--------------------------------------------------------------------------
        */

        $cancelledAppointments =
            (clone $baseQuery)
                ->where(
                    'appointment_status',
                    'cancelled'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Pending Appointments
        |--------------------------------------------------------------------------
        */

        $pendingAppointments =
            (clone $baseQuery)
                ->where(
                    'appointment_status',
                    'pending'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Confirmed Appointments
        |--------------------------------------------------------------------------
        */

        $confirmedAppointments =
            (clone $baseQuery)
                ->where(
                    'appointment_status',
                    'confirmed'
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'message' => 'Appointment Summary Fetched Successfully',

            'data' => [

                'total_appointments' =>
                    $totalAppointments,

                'today_appointments' =>
                    $todayAppointments,

                'upcoming_appointments' =>
                    $upcomingAppointments,

                'pending_appointments' =>
                    $pendingAppointments,

                'confirmed_appointments' =>
                    $confirmedAppointments,

                'completed_appointments' =>
                    $completedAppointments,

                'cancelled_appointments' =>
                    $cancelledAppointments,
            ]
        ]);
    }
}