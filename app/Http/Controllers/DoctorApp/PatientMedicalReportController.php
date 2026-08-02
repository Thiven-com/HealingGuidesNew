<?php

namespace App\Http\Controllers\DoctorApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientMedicalReportCollection;
use App\Models\DoctorAppointment;
use App\Models\PatientMedicalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PatientMedicalReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Upload Medical Report
    |--------------------------------------------------------------------------
    */

    public function uploadReport(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'appointment_id' => 'required|exists:doctor_appointments,id',

            'report_type' => 'required|string|max:100',

            'report_name' => 'required|string|max:255',

            'report_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',

            'report_date' => 'required|date',

            'notes' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Report
        |--------------------------------------------------------------------------
        */

        $file = $request->file('report_file');

        $filename = time() . '_' . uniqid() . '.' .
            $file->getClientOriginalExtension();

        $destinationPath = public_path('uploads/medical-reports');

        if (!File::exists($destinationPath)) {

            File::makeDirectory(
                $destinationPath,
                0755,
                true
            );
        }

        $file->move(
            $destinationPath,
            $filename
        );

        /*
        |--------------------------------------------------------------------------
        | Create Report
        |--------------------------------------------------------------------------
        */

        $report = new PatientMedicalReport();

        $report->customer_id = $appointment->customer_id;

        $report->family_member_id = $appointment->family_member_id;

        $report->appointment_id = $appointment->id;

        $report->doctor_id = $doctor->id;

        $report->report_type = $request->report_type;

        $report->report_name = $request->report_name;

        $report->report_file =
            'uploads/medical-reports/' . $filename;

        $report->report_date = $request->report_date;

        $report->notes = $request->notes;

        $report->save();

        return response()->json([
            'success' => 1,
            'message' => 'Medical report uploaded successfully.',
            'data' => $report
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Patient Medical Reports
    |--------------------------------------------------------------------------
    */

    public function reports(Request $request, $appointmentId)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Appointment
        |--------------------------------------------------------------------------
        */

        $appointment = DoctorAppointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => 0,
                'message' => 'Appointment not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Patient Reports
        |--------------------------------------------------------------------------
        */

        $reports = PatientMedicalReport::where(
            'customer_id',
            $appointment->customer_id
        );

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        if ($appointment->family_member_id) {

            $reports->where(
                'family_member_id',
                $appointment->family_member_id
            );

        } else {

            $reports->whereNull('family_member_id');
        }

        /*
        |--------------------------------------------------------------------------
        | Report Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('report_type')) {

            $reports->where(
                'report_type',
                $request->report_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Report Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('report_date')) {

            $reports->whereDate(
                'report_date',
                $request->report_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Reports
        |--------------------------------------------------------------------------
        */

        $reports = $reports
            ->orderByDesc('report_date')
            ->orderByDesc('id')
            ->paginate(20);

        if ($reports->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Medical Reports Found'
            ]);
        }

        return response()->json([
            'success' => 1,

            'data' => new PatientMedicalReportCollection(
                $reports
            ),

            'message' => 'Patient medical reports fetched successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Report Details
    |--------------------------------------------------------------------------
    */

    public function reportDetails($id)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Report
        |--------------------------------------------------------------------------
        */

        $report = PatientMedicalReport::find($id);

        if (!$report) {

            return response()->json([
                'success' => 0,
                'message' => 'Medical report not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Doctor Access
        |--------------------------------------------------------------------------
        */

        $hasAccess = DoctorAppointment::where(
            'doctor_id',
            $doctor->id
        )
            ->where(
                'customer_id',
                $report->customer_id
            )
            ->when(
                $report->family_member_id,

                function ($query) use ($report) {

                    $query->where(
                        'family_member_id',
                        $report->family_member_id
                    );
                },

                function ($query) {

                    $query->whereNull(
                        'family_member_id'
                    );
                }
            )
            ->exists();

        if (!$hasAccess) {

            return response()->json([
                'success' => 0,
                'message' => 'You are not authorized to view this report.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Convert Single Record To Collection
        |--------------------------------------------------------------------------
        */

        $reports = collect([
            $report
        ]);

        return response()->json([
            'success' => 1,

            'data' => new PatientMedicalReportCollection(
                $reports
            ),

            'message' => 'Medical report fetched successfully.'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Medical Report
    |--------------------------------------------------------------------------
    */

    public function updateReport(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'report_id' => 'required|exists:patient_medical_reports,id',

            'report_type' => 'nullable|string|max:100',

            'report_name' => 'nullable|string|max:255',

            'report_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',

            'report_date' => 'nullable|date',

            'notes' => 'nullable|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Only Report Uploaded By Doctor
        |--------------------------------------------------------------------------
        */

        $report = PatientMedicalReport::where(
            'id',
            $request->report_id
        )
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => 0,
                'message' => 'Medical report not found.'
            ]);
        }

        if ($request->has('report_type')) {

            $report->report_type = $request->report_type;
        }

        if ($request->has('report_name')) {

            $report->report_name = $request->report_name;
        }

        if ($request->has('report_date')) {

            $report->report_date = $request->report_date;
        }

        if ($request->has('notes')) {

            $report->notes = $request->notes;
        }

        /*
        |--------------------------------------------------------------------------
        | Replace File
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('report_file')) {

            /*
            | Delete Old File
            */

            if (
                $report->report_file &&
                File::exists(public_path($report->report_file))
            ) {

                File::delete(
                    public_path($report->report_file)
                );
            }

            /*
            | Upload New File
            */

            $file = $request->file('report_file');

            $filename = time() . '_' . uniqid() . '.' .
                $file->getClientOriginalExtension();

            $destinationPath =
                public_path('uploads/medical-reports');

            if (!File::exists($destinationPath)) {

                File::makeDirectory(
                    $destinationPath,
                    0755,
                    true
                );
            }

            $file->move(
                $destinationPath,
                $filename
            );

            $report->report_file =
                'uploads/medical-reports/' . $filename;
        }

        $report->save();

        return response()->json([
            'success' => 1,
            'message' => 'Medical report updated successfully.',
            'data' => $report
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Medical Report
    |--------------------------------------------------------------------------
    */

    public function deleteReport(Request $request)
    {
        $doctor = auth('sanctum')->user();

        if (!$doctor) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'report_id' =>
                'required|exists:patient_medical_reports,id',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Doctor Can Delete Own Uploaded Report
        |--------------------------------------------------------------------------
        */

        $report = PatientMedicalReport::where(
            'id',
            $request->report_id
        )
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$report) {
            return response()->json([
                'success' => 0,
                'message' => 'Medical report not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete File
        |--------------------------------------------------------------------------
        */

        if (
            $report->report_file &&
            File::exists(public_path($report->report_file))
        ) {

            File::delete(
                public_path($report->report_file)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $report->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Medical report deleted successfully.'
        ]);
    }
}