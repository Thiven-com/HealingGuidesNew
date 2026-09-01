<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\FamilyMember;
use App\Models\PatientMedicalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientMedicalReportController extends Controller
{
    public function index(Request $request)
    {
        $query = PatientMedicalReport::with(['customer', 'familyMember', 'doctor', 'appointment',]); /* |-------------------------------------------------------------------------- | Search |-------------------------------------------------------------------------- */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_name', 'like', "%{$search}%")->orWhere('report_type', 'like', "%{$search}%")->orWhere('notes', 'like', "%{$search}%")->orWhereHas('customer', function ($customer) use ($search) {
                    $customer->where('name', 'like', "%{$search}%"); })->orWhereHas('doctor', function ($doctor) use ($search) {
                        $doctor->where('doctor_name', 'like', "%{$search}%"); }); });
        } /* |-------------------------------------------------------------------------- | Report Type |-------------------------------------------------------------------------- */
        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        } /* |-------------------------------------------------------------------------- | Report Date |-------------------------------------------------------------------------- */
        if ($request->filled('report_date')) {
            $query->whereDate('report_date', $request->report_date);
        } /* |-------------------------------------------------------------------------- | Pagination |-------------------------------------------------------------------------- */
        $reports = $query->latest('id')->paginate(15)->withQueryString();
        return view('admin.patient-medical-reports.index', compact('reports'));
    } /** * Show Report */
    public function show($id)
    {
        $report = PatientMedicalReport::with(['customer', 'familyMember', 'doctor', 'appointment',])->findOrFail($id);
        return view('admin.patient-medical-reports.show', compact('report'));
    } /** * Edit Report */
    public function edit($id)
    {
        $report = PatientMedicalReport::findOrFail($id);
        $customers = Customer::orderBy('name')->get();
        $familyMembers = FamilyMember::orderBy('name')->get();
        $doctors = Doctor::orderBy('doctor_name')->get();
        $appointments = DoctorAppointment::latest('id')->get();
        return view('admin.patient-medical-reports.edit', compact('report', 'customers', 'familyMembers', 'doctors', 'appointments'));
    } /** * Update Report */
    public function update(Request $request, $id)
    {
        $report = PatientMedicalReport::findOrFail($id);
        $validated = $request->validate(['customer_id' => ['nullable', 'integer', 'exists:customers,id',], 'family_member_id' => ['nullable', 'integer', 'exists:family_members,id',], 'appointment_id' => ['nullable', 'integer', 'exists:doctor_appointments,id',], 'doctor_id' => ['nullable', 'integer', 'exists:doctors,id',], 'report_type' => ['nullable', 'string', 'max:255',], 'report_name' => ['nullable', 'string', 'max:255',], 'report_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240',], 'report_date' => ['nullable', 'date',], 'notes' => ['nullable', 'string',],]); /* |-------------------------------------------------------------------------- | Update Fields |-------------------------------------------------------------------------- */
        $report->customer_id = $validated['customer_id'] ?? null;
        $report->family_member_id = $validated['family_member_id'] ?? null;
        $report->appointment_id = $validated['appointment_id'] ?? null;
        $report->doctor_id = $validated['doctor_id'] ?? null;
        $report->report_type = $validated['report_type'] ?? null;
        $report->report_name = $validated['report_name'] ?? null;
        $report->report_date = $validated['report_date'] ?? null;
        $report->notes = $validated['notes'] ?? null; /* |-------------------------------------------------------------------------- | Replace File |-------------------------------------------------------------------------- */
        if ($request->hasFile('report_file')) {
            if ($report->report_file && Storage::disk('public')->exists($report->report_file)) {
                Storage::disk('public')->delete($report->report_file);
            }
            $report->report_file = $request->file('report_file')->store('patient-medical-reports', 'public');
        }
        $report->save();
        return redirect()->route('admin.patient-medical-reports.index')->with('success', 'Patient medical report updated successfully.');
    } /** * Delete Report */
    public function destroy($id)
    {
        $report = PatientMedicalReport::findOrFail($id);
        if ($report->report_file && Storage::disk('public')->exists($report->report_file)) {
            Storage::disk('public')->delete($report->report_file);
        }
        $report->delete();
        return redirect()->route('admin.patient-medical-reports.index')->with('success', 'Patient medical report deleted successfully.');
    }
}
