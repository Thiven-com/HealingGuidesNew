<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
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

        $query = DoctorAppointment::with([
            'doctor',
            'customer',
            'familyMember'
        ]);



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

        $doctors = \App\Models\Doctor::where('status', 1)->orderBy('doctor_name')->get();


        return view(
            'admin.appointments.index',
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

        $appointment = DoctorAppointment::with([
            'doctor.hospitalSpecialization.specialization',
            'customer',
            'familyMember',
            'doctorSchedule'
        ])

            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        return view(
            'admin.appointments.show',
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


        /*
        |--------------------------------------------------------------------------
        | Find Appointment
        |--------------------------------------------------------------------------
        |
        | Admin can update appointments from all hospitals.
        |
        */

        $appointment = DoctorAppointment::where(
            'id',
            $request->id
        )->first();


        if (!$appointment) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Appointment Not Found'
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Changes After Final Status
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
        | If Status Is Not Cancelled
        |--------------------------------------------------------------------------
        */

        if (
            $request->appointment_status !==
            'cancelled'
        ) {

            $appointment->cancelled_at = null;
            $appointment->cancel_reason = null;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Status
        |--------------------------------------------------------------------------
        */

        $appointment->appointment_status =
            $request->appointment_status;


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

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




    public function reschedule($id)
    {
        $appointment = DoctorAppointment::where(
            'id',
            $id
        )->firstOrFail();

        return view(
            'admin.appointments.reschedule',
            compact('appointment')
        );
    }





    public function getRescheduleSlots(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Appointment
        |--------------------------------------------------------------------------
        | Admin can access appointments from all hospitals.
        */

        $appointment = DoctorAppointment::where(
            'id',
            $id
        )->firstOrFail();


        $request->validate([
            'appointment_date' => 'required|date',
        ]);


        $date = $request->appointment_date;

        $day = Carbon::parse($date)->format('l');


        /*
        |--------------------------------------------------------------------------
        | Doctor Schedule
        |--------------------------------------------------------------------------
        */

        $schedule = DoctorSchedule::where(
            'doctor_id',
            $appointment->doctor_id
        )
            ->where('day_of_week', $day)
            ->where('status', 1)
            ->first();


        if (!$schedule) {

            return response()->json([
                'success' => 0,
                'message' => 'Doctor is not available on this day.',
                'data' => []
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Appointments
        |--------------------------------------------------------------------------
        |
        | Exclude the appointment currently being rescheduled.
        |
        */

        $appointments = DoctorAppointment::where(
            'doctor_id',
            $appointment->doctor_id
        )
            ->whereDate(
                'appointment_date',
                $date
            )
            ->where(
                'id',
                '!=',
                $appointment->id
            )
            ->where(
                'appointment_status',
                '!=',
                'cancelled'
            )
            ->get([
                'appointment_time',
                'appointment_status'
            ]);


        /*
        |--------------------------------------------------------------------------
        | Slot Status Lookup
        |--------------------------------------------------------------------------
        */

        $slotStatuses = [];


        foreach ($appointments as $existingAppointment) {

            $time = Carbon::parse(
                $existingAppointment->appointment_time
            )->format('H:i');


            switch ($existingAppointment->appointment_status) {

                case 'pending':

                    $slotStatuses[$time] = 'Blocked';

                    break;


                case 'confirmed':

                    $slotStatuses[$time] = 'Booked';

                    break;


                default:

                    $slotStatuses[$time] = 'Booked';

                    break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Slots
        |--------------------------------------------------------------------------
        */

        $start = Carbon::parse(
            $schedule->available_from
        );

        $end = Carbon::parse(
            $schedule->available_to
        );

        $slots = [];


        /*
        |--------------------------------------------------------------------------
        | Lunch Break
        |--------------------------------------------------------------------------
        */

        $breakStart = Carbon::createFromTimeString(
            '13:00:00'
        );

        $breakEnd = Carbon::createFromTimeString(
            '14:00:00'
        );


        while ($start < $end) {

            /*
            |--------------------------------------------------------------------------
            | Skip Lunch Break
            |--------------------------------------------------------------------------
            */

            if (
                $start >= $breakStart &&
                $start < $breakEnd
            ) {

                $start->addMinutes(
                    $schedule->slot_duration
                );

                continue;
            }


            $time = $start->format('H:i');

            $status = $slotStatuses[$time] ?? 'Available';


            $slots[] = [

                'slot' => $time,

                'slot_time' => $start->format('h:i A'),

                'status' => $status,

                'available' => $status === 'Available',

            ];


            $start->addMinutes(
                $schedule->slot_duration
            );
        }


        return response()->json([

            'success' => 1,

            'message' =>
                'Available slots fetched successfully.',

            'data' => [

                'schedule_id' =>
                    $schedule->id,

                'day' =>
                    $day,

                'available_from' =>
                    $schedule->available_from,

                'available_to' =>
                    $schedule->available_to,

                'slot_duration' =>
                    $schedule->slot_duration,

                'slots' =>
                    $slots,

            ]

        ]);
    }


    public function updateReschedule(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Appointment
        |--------------------------------------------------------------------------
        | Admin can update appointments from all hospitals.
        */

        $appointment = DoctorAppointment::where(
            'id',
            $id
        )->firstOrFail();


        $validated = $request->validate([

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'appointment_time' => [
                'required',
            ],

            'doctor_schedule_id' => [
                'nullable',
                'exists:doctor_schedules,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Appointment
        |--------------------------------------------------------------------------
        */

        $appointment->update([

            'appointment_date' =>
                $validated['appointment_date'],

            'appointment_time' =>
                $validated['appointment_time'],

            'doctor_schedule_id' =>
                $validated['doctor_schedule_id'] ?? null,

        ]);


        return redirect()
            ->route(
                'admin.appointments.show',
                $appointment->id
            )
            ->with(
                'success',
                'Appointment rescheduled successfully.'
            );
    }


}