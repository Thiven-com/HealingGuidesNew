<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('customer_code', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Verification filter
        if ($request->filled('is_verified')) {
            $query->where('is_verified', $request->is_verified);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Country filter
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // State filter
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        // City filter
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $customers = $query->latest()->get();

        // Filter dropdown data
        $genders = Customer::whereNotNull('gender')
            ->where('gender', '!=', '')
            ->distinct()
            ->pluck('gender');

        $countries = Customer::whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->pluck('country');

        $states = Customer::whereNotNull('state')
            ->where('state', '!=', '')
            ->distinct()
            ->pluck('state');

        $cities = Customer::whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city');

        return view('admin.customers.index', compact(
            'customers',
            'genders',
            'countries',
            'states',
            'cities'
        ));
    }

    public function show(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $slug = $request->get('slug', 'profile');

        switch ($slug) {

            case 'family-members':

                $familyMembers = DB::table('family_members')
                    ->where('customer_id', $customer->id)
                    ->latest('created_at')
                    ->get();

                $familyCount = $familyMembers->count();

                return view('admin.customers.family-members', compact(
                    'customer',
                    'familyMembers',
                    'familyCount'
                ));

            case 'reports':

                $reports = DB::table('patient_medical_reports')
                    ->where('customer_id', $customer->id)
                    ->latest('created_at')
                    ->get();

                $reportsCount = $reports->count();

                return view('admin.customers.reports', compact(
                    'customer',
                    'reports',
                    'reportsCount'
                ));

            case 'profile':
            default:

                return view('admin.customers.profile', compact('customer'));
        }

    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.edit', compact('customer'));

    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'gender' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:20',
            'is_verified' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Photo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('uploads/customers'),
                $filename
            );

            $validated['photo'] = 'uploads/customers/' . $filename;
        }

        $customer->update($validated);

        return redirect()->route('admin.customers.show', [
            'id' => $customer->id,
            'slug' => 'profile'
        ])->with('success', 'Customer profile updated successfully.');

    }
}
