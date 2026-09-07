<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingStaff;
use Illuminate\Http\Request;

class MarketingStaffController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingStaff::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('employee_code', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('designation', 'like', '%' . $search . '%');
            });
        }

        // Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Designation
        if ($request->filled('designation')) {
            $query->where('designation', 'like', '%' . $request->designation . '%');
        }

        // State
        if ($request->filled('state')) {
            $query->where('state', 'like', '%' . $request->state . '%');
        }

        // City
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Availability
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $marketingStaff = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.marketing-staff.all', compact('marketingStaff'));
    }
}
