<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientMedicalReportCollection;
use App\Models\FamilyMember;
use App\Models\PatientMedicalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PatientMedicalReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Upload Medical Report
    |--------------------------------------------------------------------------
    */

    public function uploadReport(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'family_member_id' =>
                'nullable|exists:family_members,id',

            'report_type' =>
                'required|string|max:100',

            'report_name' =>
                'required|string|max:255',

            'report_file' =>
                'required|file|mimes:pdf,jpg,jpeg,png|max:10240',

            'report_date' =>
                'nullable|date|before_or_equal:today',

            'notes' =>
                'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Family Member Ownership
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $familyMember = FamilyMember::where(
                'id',
                $request->family_member_id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->first();

            if (!$familyMember) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Family member not found.'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */

        $filePath = null;

        if ($request->hasFile('report_file')) {

            $filePath = $request
                ->file('report_file')
                ->store(
                    'medical-reports',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Report
        |--------------------------------------------------------------------------
        */

        $report = new PatientMedicalReport();

        $report->customer_id =
            $customer->id;

        $report->family_member_id =
            $request->family_member_id;

        /*
        |--------------------------------------------------------------------------
        | Customer Upload Has No Appointment / Doctor
        |--------------------------------------------------------------------------
        */

        $report->appointment_id = null;

        $report->doctor_id = null;

        $report->report_type =
            $request->report_type;

        $report->report_name =
            $request->report_name;

        $report->report_file =
            $filePath;

        $report->report_date =
            $request->report_date;

        $report->notes =
            $request->notes;

        $report->save();

        return response()->json([
            'success' => 1,
            'message' => 'Medical report uploaded successfully.',
            'data' => $report
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | My Medical Reports
    |--------------------------------------------------------------------------
    */

    public function reports(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $reports = PatientMedicalReport::with([
            'familyMember',
            'doctor'
        ])
            ->where(
                'customer_id',
                $customer->id
            );

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {

            $reports->where(
                'id',
                $request->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $reports->where(
                'family_member_id',
                $request->family_member_id
            );

        } elseif ($request->has('family_member_id')) {

            $reports->whereNull(
                'family_member_id'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Report Type
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
        | Report Date
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
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $reports->where(function ($query) use ($request) {

                $query->where(
                    'report_name',
                    'LIKE',
                    '%' . $request->search . '%'
                )
                    ->orWhere(
                        'report_type',
                        'LIKE',
                        '%' . $request->search . '%'
                    );
            });
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

            'message' =>
                'Medical reports fetched successfully.'

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Medical Report Details
    |--------------------------------------------------------------------------
    */

    public function reportDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $report = PatientMedicalReport::with([
            'familyMember',
            'doctor'
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'customer_id',
                $customer->id
            )
            ->get();

        if ($report->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'Medical report not found.'
            ]);
        }

        return response()->json([

            'success' => 1,

            'data' => new PatientMedicalReportCollection(
                $report
            ),

            'message' =>
                'Medical report fetched successfully.'

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Medical Report
    |--------------------------------------------------------------------------
    */

    public function updateReport(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'report_id' =>
                'required|exists:patient_medical_reports,id',

            'family_member_id' =>
                'nullable|exists:family_members,id',

            'report_type' =>
                'nullable|string|max:100',

            'report_name' =>
                'nullable|string|max:255',

            'report_file' =>
                'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',

            'report_date' =>
                'nullable|date|before_or_equal:today',

            'notes' =>
                'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Report Ownership
        |--------------------------------------------------------------------------
        */

        $report = PatientMedicalReport::where(
            'id',
            $request->report_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
            ->first();

        if (!$report) {
            return response()->json([
                'success' => 0,
                'message' => 'Medical report not found.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member Ownership
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $familyMember = FamilyMember::where(
                'id',
                $request->family_member_id
            )
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->first();

            if (!$familyMember) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Family member not found.'
                ]);
            }

            $report->family_member_id =
                $request->family_member_id;
        }

        /*
        |--------------------------------------------------------------------------
        | Update File
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('report_file')) {

            if (
                $report->report_file &&
                Storage::disk('public')->exists(
                    $report->report_file
                )
            ) {
                Storage::disk('public')->delete(
                    $report->report_file
                );
            }

            $report->report_file =
                $request
                    ->file('report_file')
                    ->store(
                        'medical-reports',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Fields
        |--------------------------------------------------------------------------
        */

        if ($request->filled('report_type')) {
            $report->report_type =
                $request->report_type;
        }

        if ($request->filled('report_name')) {
            $report->report_name =
                $request->report_name;
        }

        if ($request->filled('report_date')) {
            $report->report_date =
                $request->report_date;
        }

        if ($request->has('notes')) {
            $report->notes =
                $request->notes;
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
        $customer = auth('sanctum')->user();

        if (!$customer) {
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
        | Report Ownership
        |--------------------------------------------------------------------------
        */

        $report = PatientMedicalReport::where(
            'id',
            $request->report_id
        )
            ->where(
                'customer_id',
                $customer->id
            )
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
            Storage::disk('public')->exists(
                $report->report_file
            )
        ) {
            Storage::disk('public')->delete(
                $report->report_file
            );
        }

        $report->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Medical report deleted successfully.'
        ]);
    }
}