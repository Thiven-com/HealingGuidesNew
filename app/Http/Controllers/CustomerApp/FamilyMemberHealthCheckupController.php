<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\FamilyMemberHealthCheckup;
use Illuminate\Http\Request;

class FamilyMemberHealthCheckupController extends Controller
{
    /**
     * Get complete health report of a family member.
     */
    public function index(Request $request)
    {
        $customer = auth('sanctum')->user();

        /*
        |--------------------------------------------------------------------------
        | Validate Family Member
        |--------------------------------------------------------------------------
        */

        $member = FamilyMember::where('customer_id', $customer->id)
            ->where('id', $request->family_member_id)
            ->first();

        if (!$member) {
            return response()->json([
                'success' => 0,
                'message' => 'Family Member Details Not Found',
                'data' => null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Get Reports
        |--------------------------------------------------------------------------
        */

        $query = FamilyMemberHealthCheckup::with([
            'familyMember',
            'healthCheckup',
        ])
            ->where('family_member_id', $member->id);


        /*
        |--------------------------------------------------------------------------
        | Optional Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('health_checkup_id')) {
            $query->where(
                'health_checkup_id',
                $request->health_checkup_id
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }


        $reports = $query
            ->latest('checked_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Counts
        |--------------------------------------------------------------------------
        */

        $totalChecks = $reports->count();

        $completedChecks = $reports
            ->where('status', 'completed')
            ->count();

        $pendingChecks = $reports
            ->where('status', 'pending')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Overall Health Score
        |--------------------------------------------------------------------------
        |
        | Calculate average percentage of completed reports.
        |
        */

        $completedReports = $reports
            ->where('status', 'completed')
            ->filter(function ($report) {
                return !is_null($report->percentage);
            });


        if ($completedReports->count() > 0) {

            $overallScore = round(
                $completedReports->avg(function ($report) {
                    return (float) $report->percentage;
                })
            );

        } else {

            $overallScore = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Health Status
        |--------------------------------------------------------------------------
        */

        if ($overallScore >= 80) {

            $healthStatus = 'Good';

        } elseif ($overallScore >= 60) {

            $healthStatus = 'Average';

        } elseif ($overallScore > 0) {

            $healthStatus = 'Needs Attention';

        } else {

            $healthStatus = 'Not Available';

        }


        /*
        |--------------------------------------------------------------------------
        | Last Health Check
        |--------------------------------------------------------------------------
        */

        $lastReport = $reports
            ->filter(function ($report) {
                return !is_null($report->checked_at);
            })
            ->sortByDesc('checked_at')
            ->first();


        $lastHealthCheck = $lastReport?->checked_at
            ? $lastReport->checked_at->format('Y-m-d')
            : null;


        /*
        |--------------------------------------------------------------------------
        | Health Checkups
        |--------------------------------------------------------------------------
        */

        $healthCheckups = $reports->map(function ($report) {

            return [

                'id' => $report->id,

                'health_checkup_id' => $report->health_checkup_id,

                'name' => optional($report->healthCheckup)->name,

                'slug' => optional($report->healthCheckup)->slug,

                'image' => optional($report->healthCheckup)->image
                    ? asset($report->healthCheckup->image)
                    : null,

                'description' => optional($report->healthCheckup)->description,

                'report_value' => $report->report_value,

                'percentage' => !is_null($report->percentage)
                    ? (float) $report->percentage
                    : null,

                'status' => $report->status,

                'status_label' => $report->status === 'completed'
                    ? 'Completed'
                    : 'Pending',

                'checked_at' => $report->checked_at
                    ? $report->checked_at->format('Y-m-d')
                    : null,

                'remarks' => $report->remarks,

            ];

        })->values();


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => 1,

            'message' => 'Family health report fetched successfully.',

            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Family Member
                |--------------------------------------------------------------------------
                */

                'family_member' => [

                    'id' => $member->id,

                    'name' => $member->name,

                    'relationship' => $member->relationship,

                    'gender' => $member->gender ?? null,

                    'dob' => $member->dob ?? null,

                    'last_health_check' => $lastHealthCheck,

                ],


                /*
                |--------------------------------------------------------------------------
                | Overall Health Score
                |--------------------------------------------------------------------------
                */

                'overall_health' => [

                    'score' => $overallScore,

                    'percentage' => $overallScore,

                    'out_of' => 100,

                    'status' => $healthStatus,

                    'message' => $overallScore > 0
                        ? 'Your health indicators are being tracked through regular checkups.'
                        : 'Complete health checkups to start tracking your health.',

                ],


                /*
                |--------------------------------------------------------------------------
                | Summary
                |--------------------------------------------------------------------------
                */

                'summary' => [

                    'completed' => $completedChecks,

                    'pending' => $pendingChecks,

                    'total_checks' => $totalChecks,

                ],


                /*
                |--------------------------------------------------------------------------
                | Health Checkups
                |--------------------------------------------------------------------------
                */

                'health_checkups' => $healthCheckups,

            ],
        ]);
    }


    /**
     * Get single health checkup report.
     */
    public function show($id)
    {
        $customer = auth('sanctum')->user();

        $report = FamilyMemberHealthCheckup::with([
            'familyMember',
            'healthCheckup',
        ])
            ->where('id', $id)
            ->whereHas('familyMember', function ($query) use ($customer) {

                $query->where(
                    'customer_id',
                    $customer->id
                );

            })
            ->first();


        if (!$report) {

            return response()->json([

                'success' => 0,

                'message' => 'Health checkup report not found.',

                'data' => null,

            ], 404);
        }


        return response()->json([

            'success' => 1,

            'message' => 'Health checkup report fetched successfully.',

            'data' => [

                'id' => $report->id,

                'family_member' => [

                    'id' => optional($report->familyMember)->id,

                    'name' => optional($report->familyMember)->name,

                    'relationship' =>
                        optional($report->familyMember)->relationship,

                ],

                'health_checkup' => [

                    'id' => optional($report->healthCheckup)->id,

                    'name' =>
                        optional($report->healthCheckup)->name,

                    'slug' =>
                        optional($report->healthCheckup)->slug,

                    'image' =>
                        optional($report->healthCheckup)->image
                            ? asset($report->healthCheckup->image)
                            : null,

                    'description' =>
                        optional($report->healthCheckup)->description,

                ],

                'report_value' => $report->report_value,

                'percentage' => !is_null($report->percentage)
                    ? (float) $report->percentage
                    : null,

                'status' => $report->status,

                'checked_at' => $report->checked_at
                    ? $report->checked_at->format('Y-m-d')
                    : null,

                'remarks' => $report->remarks,

                'created_at' => $report->created_at,

                'updated_at' => $report->updated_at,

            ],
        ]);
    }
}