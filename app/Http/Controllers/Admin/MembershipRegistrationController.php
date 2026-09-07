<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipRegistration;
use Illuminate\Http\Request;

class MembershipRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = MembershipRegistration::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('mobile_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Gender Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        /*
        |--------------------------------------------------------------------------
        | Membership Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('membership')) {
            $query->where('membership', $request->membership);
        }

        /*
        |--------------------------------------------------------------------------
        | City Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Date of Birth Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('dob_from')) {
            $query->whereDate('date_of_birth', '>=', $request->dob_from);
        }

        if ($request->filled('dob_to')) {
            $query->whereDate('date_of_birth', '<=', $request->dob_to);
        }

        $membershipRegistrations = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.membership-registrations.all',
            compact('membershipRegistrations')
        );
    }
}
