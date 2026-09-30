<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FamilyMember;
use App\Models\FamilyMemberHealthCheckup;
use App\Models\HealthCheckup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FamilyMemberHealthCheckupController extends Controller
{
    /**
     * Display health checkup reports.
     */
    public function index(Request $request)
    {
        $query = FamilyMemberHealthCheckup::with([
            'familyMember.customer',
            'healthCheckup',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('familyMember', function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%");

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Family Member Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('family_member_id')) {

            $query->where(
                'family_member_id',
                $request->family_member_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Health Checkup Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('health_checkup_id')) {

            $query->where(
                'health_checkup_id',
                $request->health_checkup_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $reports = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $healthCheckups = HealthCheckup::orderBy('name')
            ->get();

        return view(
            'admin.family_member_health_checkups.index',
            compact(
                'reports',
                'healthCheckups'
            )
        );
    }


    /**
     * Create health checkup report.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')
            ->get();

        $familyMembers = FamilyMember::with('customer')
            ->orderBy('name')
            ->get();

        $healthCheckups = HealthCheckup::orderBy('name')
            ->get();

        return view(
            'admin.family_member_health_checkups.create',
            compact(
                'customers',
                'familyMembers',
                'healthCheckups'
            )
        );
    }


    /**
     * Store health checkup report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'family_member_id' => [
                'required',
                'integer',
                'exists:family_members,id',
            ],

            'health_checkup_id' => [
                'required',
                'integer',
                'exists:health_checkups,id',
            ],

            'report_value' => [
                'nullable',
                'string',
                'max:255',
            ],

            'percentage' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'completed',
                ]),
            ],

            'checked_at' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Checkup
        |--------------------------------------------------------------------------
        */

        $exists = FamilyMemberHealthCheckup::where(
            'family_member_id',
            $validated['family_member_id']
        )
            ->where(
                'health_checkup_id',
                $validated['health_checkup_id']
            )
            ->exists();

        if ($exists) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'This health checkup already exists for this family member.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        FamilyMemberHealthCheckup::create($validated);

        return redirect()
            ->route('admin.family-member-health-checkups.index')
            ->with(
                'success',
                'Family member health checkup report added successfully.'
            );
    }


    /**
     * Show report.
     */
    public function show($id)
    {
        $report = FamilyMemberHealthCheckup::with([
            'familyMember.customer',
            'healthCheckup',
        ])->findOrFail($id);

        return view(
            'admin.family_member_health_checkups.show',
            compact('report')
        );
    }


    /**
     * Edit report.
     */
    public function edit($id)
    {
        $report = FamilyMemberHealthCheckup::findOrFail($id);

        $customers = Customer::orderBy('name')
            ->get();

        $familyMembers = FamilyMember::with('customer')
            ->orderBy('name')
            ->get();

        $healthCheckups = HealthCheckup::orderBy('name')
            ->get();

        return view(
            'admin.family_member_health_checkups.edit',
            compact(
                'report',
                'customers',
                'familyMembers',
                'healthCheckups'
            )
        );
    }


    /**
     * Update report.
     */
    public function update(Request $request, $id)
    {
        $report = FamilyMemberHealthCheckup::findOrFail($id);

        $validated = $request->validate([

            'family_member_id' => [
                'required',
                'integer',
                'exists:family_members,id',
            ],

            'health_checkup_id' => [
                'required',
                'integer',
                'exists:health_checkups,id',
            ],

            'report_value' => [
                'nullable',
                'string',
                'max:255',
            ],

            'percentage' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'completed',
                ]),
            ],

            'checked_at' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate
        |--------------------------------------------------------------------------
        */

        $exists = FamilyMemberHealthCheckup::where(
            'family_member_id',
            $validated['family_member_id']
        )
            ->where(
                'health_checkup_id',
                $validated['health_checkup_id']
            )
            ->where(
                'id',
                '!=',
                $report->id
            )
            ->exists();

        if ($exists) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'This health checkup already exists for this family member.'
                );
        }

        $report->update($validated);

        return redirect()
            ->route('admin.family-member-health-checkups.index')
            ->with(
                'success',
                'Family member health checkup report updated successfully.'
            );
    }


    /**
     * Delete report.
     */
    public function destroy($id)
    {
        $report = FamilyMemberHealthCheckup::findOrFail($id);

        $report->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Family member health checkup report deleted successfully.'
            );
    }
}