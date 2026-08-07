<?php

namespace App\Http\Controllers\MarketingApp;

use App\Http\Controllers\Controller;
use App\Models\MarketingLead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Logged In Marketing Staff
        |--------------------------------------------------------------------------
        */

        $staff = auth('sanctum')->user();

        if (!$staff) {

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

        $baseQuery = MarketingLead::where(
            'marketing_staff_id',
            $staff->id
        );


        /*
        |--------------------------------------------------------------------------
        | Total Leads
        |--------------------------------------------------------------------------
        */

        $totalLeads = (clone $baseQuery)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Lead Type Counts
        |--------------------------------------------------------------------------
        */

        $hospitalLeads = (clone $baseQuery)
            ->where('lead_type', 'hospital')
            ->count();

        $doctorLeads = (clone $baseQuery)
            ->where('lead_type', 'doctor')
            ->count();

        $customerLeads = (clone $baseQuery)
            ->where('lead_type', 'customer')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Lead Status Counts
        |--------------------------------------------------------------------------
        */

        $newLeads = (clone $baseQuery)
            ->where('lead_status', 'new')
            ->count();

        $contactedLeads = (clone $baseQuery)
            ->where('lead_status', 'contacted')
            ->count();

        $followupLeads = (clone $baseQuery)
            ->where('lead_status', 'follow_up')
            ->count();

        $interestedLeads = (clone $baseQuery)
            ->where('lead_status', 'interested')
            ->count();

        $notInterestedLeads = (clone $baseQuery)
            ->where('lead_status', 'not_interested')
            ->count();

        $convertedLeads = (clone $baseQuery)
            ->where('lead_status', 'converted')
            ->count();

        $closedLeads = (clone $baseQuery)
            ->where('lead_status', 'closed')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Leads
        |--------------------------------------------------------------------------
        */

        $todayLeads = (clone $baseQuery)
            ->whereDate('created_at', today())
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Follow-ups
        |--------------------------------------------------------------------------
        |
        | Currently using next_followup_at from marketing_leads.
        |
        */

        $todayFollowups = (clone $baseQuery)
            ->whereNotNull('next_followup_at')
            ->whereDate(
                'next_followup_at',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Upcoming Follow-ups
        |--------------------------------------------------------------------------
        */

        $upcomingFollowups = (clone $baseQuery)
            ->whereNotNull('next_followup_at')
            ->where(
                'next_followup_at',
                '>',
                now()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Overdue Follow-ups
        |--------------------------------------------------------------------------
        */

        $overdueFollowups = (clone $baseQuery)
            ->whereNotNull('next_followup_at')
            ->where(
                'next_followup_at',
                '<',
                now()
            )
            ->whereNotIn(
                'lead_status',
                [
                    'converted',
                    'closed',
                    'not_interested'
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Today's Converted Leads
        |--------------------------------------------------------------------------
        */

        $todayConverted = (clone $baseQuery)
            ->where(
                'lead_status',
                'converted'
            )
            ->whereDate(
                'converted_at',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Leads
        |--------------------------------------------------------------------------
        */

        $recentLeads = (clone $baseQuery)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($lead) {

                return [

                    'id' =>
                        $lead->id,

                    'lead_type' =>
                        $lead->lead_type,

                    'name' =>
                        $lead->name,

                    'mobile' =>
                        $lead->mobile,

                    'priority' =>
                        $lead->priority,

                    'lead_status' =>
                        $lead->lead_status,

                    'next_followup_at' =>
                        $lead->next_followup_at
                        ? $lead->next_followup_at
                            ->format('Y-m-d H:i:s')
                        : null,

                    'created_at' =>
                        $lead->created_at
                        ? $lead->created_at
                            ->format('Y-m-d H:i:s')
                        : null,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Today's Follow-up List
        |--------------------------------------------------------------------------
        */

        $todayFollowupList = (clone $baseQuery)
            ->whereNotNull('next_followup_at')
            ->whereDate(
                'next_followup_at',
                today()
            )
            ->orderBy(
                'next_followup_at',
                'asc'
            )
            ->limit(10)
            ->get()
            ->map(function ($lead) {

                return [

                    'id' =>
                        $lead->id,

                    'lead_type' =>
                        $lead->lead_type,

                    'name' =>
                        $lead->name,

                    'mobile' =>
                        $lead->mobile,

                    'lead_status' =>
                        $lead->lead_status,

                    'priority' =>
                        $lead->priority,

                    'next_followup_at' =>
                        $lead->next_followup_at
                        ? $lead->next_followup_at
                            ->format('Y-m-d H:i:s')
                        : null,
                ];
            });
        return response()->json([

            'success' => 1,

            'message' =>
                'Dashboard Fetched Successfully',

            'data' => [
                'staff' => [

                    'id' =>
                        $staff->id,

                    'name' =>
                        $staff->name,

                    'employee_code' =>
                        $staff->employee_code,

                    'designation' =>
                        $staff->designation,

                    'photo' =>
                        $staff->photo
                        ? asset($staff->photo)
                        : null,
                ],


                'overview' => [

                    'total_leads' =>
                        $totalLeads,

                    'today_leads' =>
                        $todayLeads,

                    'today_followups' =>
                        $todayFollowups,

                    'upcoming_followups' =>
                        $upcomingFollowups,

                    'overdue_followups' =>
                        $overdueFollowups,

                    'total_converted' =>
                        $convertedLeads,

                    'today_converted' =>
                        $todayConverted,
                ],

                'lead_types' => [

                    'hospital' =>
                        $hospitalLeads,

                    'doctor' =>
                        $doctorLeads,

                    'customer' =>
                        $customerLeads,
                ],
                'lead_status' => [

                    'new' =>
                        $newLeads,

                    'contacted' =>
                        $contactedLeads,

                    'follow_up' =>
                        $followupLeads,

                    'interested' =>
                        $interestedLeads,

                    'not_interested' =>
                        $notInterestedLeads,

                    'converted' =>
                        $convertedLeads,

                    'closed' =>
                        $closedLeads,
                ],
                'recent_leads' =>
                    $recentLeads,
                'today_followup_list' =>
                    $todayFollowupList,
            ]
        ]);
    }
}