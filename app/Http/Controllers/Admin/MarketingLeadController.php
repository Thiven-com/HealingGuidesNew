<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingLead;
use App\Models\User;
use Illuminate\Http\Request;

class MarketingLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingLead::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%')
                    ->orWhere('alternate_mobile', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('policy_number', 'like', '%' . $search . '%');

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Lead Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lead_type')) {

            $query->where(
                'lead_type',
                $request->lead_type
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Lead Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('lead_status')) {

            $query->where(
                'lead_status',
                $request->lead_status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Priority
        |--------------------------------------------------------------------------
        */

        if ($request->filled('priority')) {

            $query->where(
                'priority',
                $request->priority
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Source
        |--------------------------------------------------------------------------
        */

        if ($request->filled('source')) {

            $query->where(
                'source',
                'like',
                '%' . $request->source . '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | City
        |--------------------------------------------------------------------------
        */

        if ($request->filled('city')) {

            $query->where(
                'city',
                'like',
                '%' . $request->city . '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

        if ($request->filled('state')) {

            $query->where(
                'state',
                'like',
                '%' . $request->state . '%'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Marketing Staff
        |--------------------------------------------------------------------------
        */

        if ($request->filled('marketing_staff_id')) {

            $query->where(
                'marketing_staff_id',
                $request->marketing_staff_id
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $leads = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Marketing Staff
        |--------------------------------------------------------------------------
        */

        $marketingStaff = User::orderBy('name')->get();


        return view(
            'admin.marketing-leads.all',
            compact(
                'leads',
                'marketingStaff'
            )
        );
    }
}
