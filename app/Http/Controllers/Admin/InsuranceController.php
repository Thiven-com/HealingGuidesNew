<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index(Request $request)
    {
        $query = Insurance::with('customer')->latest();

        if ($request->filled('insurance_provider')) {
            $query->where(
                'insurance_provider',
                'like',
                '%' . $request->insurance_provider . '%'
            );
        }

        if ($request->filled('policy_number')) {
            $query->where(
                'policy_number',
                'like',
                '%' . $request->policy_number . '%'
            );
        }

        if ($request->filled('policy_type')) {
            $query->where(
                'policy_type',
                'like',
                '%' . $request->policy_type . '%'
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('start_date_from')) {
            $query->whereDate(
                'start_date',
                '>=',
                $request->start_date_from
            );
        }

        if ($request->filled('start_date_to')) {
            $query->whereDate(
                'start_date',
                '<=',
                $request->start_date_to
            );
        }

        if ($request->filled('expiry_date_from')) {
            $query->whereDate(
                'expiry_date',
                '>=',
                $request->expiry_date_from
            );
        }

        if ($request->filled('expiry_date_to')) {
            $query->whereDate(
                'expiry_date',
                '<=',
                $request->expiry_date_to
            );
        }

        $insurances = $query
            ->paginate(10)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();

        return view(
            'admin.insurances.all',
            compact('insurances', 'customers')
        );
    }
}
