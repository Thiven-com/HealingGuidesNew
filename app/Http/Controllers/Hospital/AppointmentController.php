<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\DoctorAppointment;
use App\Models\DoctorSchedule;
use App\Services\NotificationService;
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

    // public function show($id)
    // {
    //     $hospital = Auth::guard('hospital')->user();

    //     $appointment = DoctorAppointment::with([
    //         'doctor.hospitalSpecialization.specialization',
    //         'customer',
    //         'familyMember',
    //         'doctorSchedule'
    //     ])
    //         ->where(
    //             'hospital_id',
    //             $hospital->id
    //         )
    //         ->where(
    //             'id',
    //             $id
    //         )
    //         ->firstOrFail();


    //     return view(
    //         'hospital.appointments.show',
    //         compact('appointment')
    //     );
    // }
    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        if (!$hospital) {
            return redirect()->route('hospital.login');
        }

        $appointment = DoctorAppointment::with([
            'doctor.hospitalSpecialization.specialization',
            'customer',
            'familyMember',
            'doctorSchedule',
            'patientVitals',
            'medicalReports',
            'prescription',
            'prescription.medicines',
        ])
            ->where('hospital_id', $hospital->id)
            ->where('id', $id)
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
        $oldStatus = $appointment->appointment_status;
        $appointment->appointment_status =
            $request->appointment_status;


        if ($request->filled('remarks')) {

            $appointment->remarks =
                $request->remarks;
        }


        $appointment->save();


        /*
        |--------------------------------------------------------------------------
        | Notify Customer App
        |--------------------------------------------------------------------------
        */

        $statusText = ucfirst(str_replace('_', ' ', $appointment->appointment_status));

        try {
            if ($appointment->customer_id) {
                NotificationService::send(
                    'customer',
                    $appointment->customer_id,
                    'appointment',
                    'Appointment Status Updated',
                    'Your appointment ' . $appointment->appointment_no .
                    ' status has been changed from ' .
                    ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' .
                    ucfirst(str_replace('_', ' ', $appointment->appointment_status)) . '.' .
                    ($appointment->cancel_reason
                        ? ' Reason: ' . $appointment->cancel_reason
                        : '') .
                    ($appointment->remarks
                        ? ' Remarks: ' . $appointment->remarks
                        : ''),
                    'doctor_appointment_status_updated',
                    $appointment->id,
                    'appointment_details',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,
                        'doctor_id' => $appointment->doctor_id,
                        'appointment_status' => $appointment->appointment_status,
                        'old_status' => $oldStatus,
                        'new_status' => $appointment->appointment_status,
                        'appointment_date' => $appointment->appointment_date,
                        'appointment_time' => $appointment->appointment_time,
                        'cancel_reason' => $appointment->cancel_reason,
                        'remarks' => $appointment->remarks,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Appointment customer notification failed: ' . $e->getMessage());
        }

        /*
        |--------------------------------------------------------------------------
        | Notify Doctor App
        |--------------------------------------------------------------------------
        */

        try {
            if ($appointment->doctor_id) {
                NotificationService::send(
                    'doctor',
                    $appointment->doctor_id,
                    'appointment',
                    'Appointment Status Updated',
                    'Appointment ' . $appointment->appointment_no .
                    ' status has been changed from ' .
                    ucfirst(str_replace('_', ' ', $oldStatus)) . ' to ' .
                    ucfirst(str_replace('_', ' ', $appointment->appointment_status)) . '.' .
                    ($appointment->cancel_reason
                        ? ' Reason: ' . $appointment->cancel_reason
                        : '') .
                    ($appointment->remarks
                        ? ' Remarks: ' . $appointment->remarks
                        : ''),
                    'doctor_appointment_status_updated',
                    $appointment->id,
                    'appointment_details',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,
                        'customer_id' => $appointment->customer_id,
                        'doctor_id' => $appointment->doctor_id,
                        'appointment_status' => $appointment->appointment_status,
                        'old_status' => $oldStatus,
                        'new_status' => $appointment->appointment_status,
                        'appointment_date' => $appointment->appointment_date,
                        'appointment_time' => $appointment->appointment_time,
                        'cancel_reason' => $appointment->cancel_reason,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Appointment doctor notification failed: ' . $e->getMessage());
        }



        return response()->json([
            'success' => 1,
            'message' =>
                'Appointment Status Updated Successfully'
        ]);
    }

    public function reschedule($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $appointment = DoctorAppointment::where('hospital_id', $hospital->id)
            ->where('id', $id)
            ->firstOrFail();

        return view(
            'hospital.appointments.reschedule',
            compact('appointment')
        );
    }


    public function getRescheduleSlots(Request $request, $id)
    {
        $hospital = Auth::guard('hospital')->user();

        $appointment = DoctorAppointment::where('hospital_id', $hospital->id)
            ->where('id', $id)
            ->firstOrFail();

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
            ->where('id', '!=', $appointment->id)
            ->where('appointment_status', '!=', 'cancelled')
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

        $start = Carbon::parse($schedule->available_from);

        $end = Carbon::parse($schedule->available_to);

        $slots = [];


        /*
        |--------------------------------------------------------------------------
        | Lunch Break
        |--------------------------------------------------------------------------
        */

        $breakStart = Carbon::createFromTimeString('13:00:00');

        $breakEnd = Carbon::createFromTimeString('14:00:00');


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

            'message' => 'Available slots fetched successfully.',

            'data' => [

                'schedule_id' => $schedule->id,

                'day' => $day,

                'available_from' => $schedule->available_from,

                'available_to' => $schedule->available_to,

                'slot_duration' => $schedule->slot_duration,

                'slots' => $slots,

            ]

        ]);
    }


    public function updateReschedule(Request $request, $id)
    {
        $hospital = Auth::guard('hospital')->user();

        $appointment = DoctorAppointment::where('hospital_id', $hospital->id)
            ->where('id', $id)
            ->firstOrFail();

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

        $oldDate = $appointment->appointment_date;
        $oldTime = $appointment->appointment_time;

        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'doctor_schedule_id' => $validated['doctor_schedule_id'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notify Customer App
        |--------------------------------------------------------------------------
        */

        try {
            if ($appointment->customer_id) {
                NotificationService::send(
                    'customer',
                    $appointment->customer_id,
                    'appointment',
                    'Appointment Rescheduled',
                    'Your appointment ' . $appointment->appointment_no .
                    ' has been successfully rescheduled from ' .
                    \Carbon\Carbon::parse($oldDate)->format('d M Y') .
                    ' at ' . $oldTime . ' to ' .
                    \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') .
                    ' at ' . $appointment->appointment_time .
                    '. Please check your appointment details for the latest information.',
                    'doctor_appointment_rescheduled',
                    $appointment->id,
                    'appointment_details',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,
                        'doctor_id' => $appointment->doctor_id,
                        'old_date' => $oldDate,
                        'old_time' => $oldTime,
                        'new_date' => $appointment->appointment_date,
                        'new_time' => $appointment->appointment_time,
                        'appointment_status' => $appointment->appointment_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Appointment reschedule customer notification failed: ' . $e->getMessage());
        }

        /*
        |--------------------------------------------------------------------------
        | Notify Doctor App
        |--------------------------------------------------------------------------
        */

        try {
            if ($appointment->doctor_id) {
                NotificationService::send(
                    'doctor',
                    $appointment->doctor_id,
                    'appointment',
                    'Appointment Rescheduled',
                    'Appointment ' . $appointment->appointment_no .
                    ' has been rescheduled from ' .
                    \Carbon\Carbon::parse($oldDate)->format('d M Y') .
                    ' at ' . $oldTime . ' to ' .
                    \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') .
                    ' at ' . $appointment->appointment_time .
                    '. Please check your updated appointment schedule.',
                    'doctor_appointment_rescheduled',
                    $appointment->id,
                    'appointment_details',
                    [
                        'appointment_id' => $appointment->id,
                        'appointment_no' => $appointment->appointment_no,
                        'customer_id' => $appointment->customer_id,
                        'doctor_id' => $appointment->doctor_id,
                        'old_date' => $oldDate,
                        'old_time' => $oldTime,
                        'new_date' => $appointment->appointment_date,
                        'new_time' => $appointment->appointment_time,
                        'appointment_status' => $appointment->appointment_status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Appointment reschedule doctor notification failed: ' . $e->getMessage());
        }


        return redirect()
            ->route('hospital.appointments.show', $appointment->id)
            ->with(
                'success',
                'Appointment rescheduled successfully.'
            );
    }
}