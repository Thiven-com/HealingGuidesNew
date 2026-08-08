<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display hospital coupons.
     */
    public function index(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $query = Coupon::where('hospital_id', $hospital->id);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $coupons = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'hospital.coupons.index',
            compact('coupons')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view('hospital.coupons.create');
    }


    /**
     * Store coupon.
     */
    public function store(Request $request)
    {
        $hospital = Auth::guard('hospital')->user();

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
            ],

            'coupon_type' => [
                'required',
                'in:coupon,offer',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'applicable_to' => [
                'required',
                'in:all,appointment,medicine',
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed,free',
            ],

            'discount_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_customer_only' => [
                'nullable',
                'boolean',
            ],

            'first_appointment_only' => [
                'nullable',
                'boolean',
            ],

            'free_appointment' => [
                'nullable',
                'boolean',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'usage_per_customer' => [
                'required',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Normalize Code
        |--------------------------------------------------------------------------
        */

        $validated['code'] = strtoupper(
            trim($validated['code'])
        );


        /*
        |--------------------------------------------------------------------------
        | Check Duplicate Coupon
        |--------------------------------------------------------------------------
        */

        $exists = Coupon::where('code', $validated['code'])
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'This coupon code already exists.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Free Appointment
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'free' ||
            !empty($validated['free_appointment'])
        ) {

            $validated['discount_type'] = 'free';

            $validated['discount_value'] = 100;

            $validated['free_appointment'] = 1;

            $validated['applicable_to'] = 'appointment';
        }


        /*
        |--------------------------------------------------------------------------
        | Hospital ID
        |--------------------------------------------------------------------------
        */

        $validated['hospital_id'] = $hospital->id;


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $validated['used_count'] = 0;

        $validated['status'] =
            $request->has('status') ? 1 : 0;

        $validated['new_customer_only'] =
            $request->has('new_customer_only') ? 1 : 0;

        $validated['first_appointment_only'] =
            $request->has('first_appointment_only') ? 1 : 0;

        $validated['free_appointment'] =
            $request->has('free_appointment') ? 1 : 0;
        $validated['created_by_type'] = 'hospital';


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Coupon::create($validated);


        return redirect()
            ->route('hospital.coupons.index')
            ->with(
                'success',
                'Coupon created successfully.'
            );
    }


    /**
     * Show coupon.
     */
    public function show($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $coupon = Coupon::where('hospital_id', $hospital->id)
            ->with([
                'usages.customer',
                'usages.appointment',
            ])
            ->findOrFail($id);

        return view(
            'hospital.coupons.show',
            compact('coupon')
        );
    }


    /**
     * Edit coupon.
     */
    public function edit($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $coupon = Coupon::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        return view(
            'hospital.coupons.edit',
            compact('coupon')
        );
    }


    /**
     * Update coupon.
     */
    public function update(Request $request, $id)
    {
        $hospital = Auth::guard('hospital')->user();

        $coupon = Coupon::where('hospital_id', $hospital->id)
            ->findOrFail($id);


        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
            ],

            'coupon_type' => [
                'required',
                'in:coupon,offer',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'applicable_to' => [
                'required',
                'in:all,appointment,medicine',
            ],

            'discount_type' => [
                'required',
                'in:percentage,fixed,free',
            ],

            'discount_value' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'new_customer_only' => [
                'nullable',
                'boolean',
            ],

            'first_appointment_only' => [
                'nullable',
                'boolean',
            ],

            'free_appointment' => [
                'nullable',
                'boolean',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'usage_per_customer' => [
                'required',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        $validated['code'] = strtoupper(
            trim($validated['code'])
        );


        /*
        |--------------------------------------------------------------------------
        | Duplicate Code
        |--------------------------------------------------------------------------
        */

        $exists = Coupon::where('hospital_id', $hospital->id)
            ->where('code', $validated['code'])
            ->where('id', '!=', $coupon->id)
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'This coupon code already exists in your hospital.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Free Appointment
        |--------------------------------------------------------------------------
        */

        if (
            $validated['discount_type'] === 'free' ||
            !empty($validated['free_appointment'])
        ) {

            $validated['discount_type'] = 'free';

            $validated['discount_value'] = 100;

            $validated['free_appointment'] = 1;

            $validated['applicable_to'] = 'appointment';
        }


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            $request->has('status') ? 1 : 0;

        $validated['new_customer_only'] =
            $request->has('new_customer_only') ? 1 : 0;

        $validated['first_appointment_only'] =
            $request->has('first_appointment_only') ? 1 : 0;

        $validated['free_appointment'] =
            $request->has('free_appointment') ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $coupon->update($validated);


        return redirect()
            ->route(
                'hospital.coupons.show',
                $coupon->id
            )
            ->with(
                'success',
                'Coupon updated successfully.'
            );
    }


    /**
     * Activate / Deactivate.
     */
    public function status($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $coupon = Coupon::where('hospital_id', $hospital->id)
            ->findOrFail($id);

        $coupon->status =
            $coupon->status ? 0 : 1;

        $coupon->save();


        return back()->with(
            'success',
            $coupon->status
            ? 'Coupon activated successfully.'
            : 'Coupon deactivated successfully.'
        );
    }


    /**
     * Delete coupon.
     */
    public function destroy($id)
    {
        $hospital = Auth::guard('hospital')->user();

        $coupon = Coupon::where('hospital_id', $hospital->id)
            ->findOrFail($id);


        if ($coupon->used_count > 0) {

            return back()->with(
                'error',
                'Used coupons cannot be deleted.'
            );
        }


        $coupon->delete();


        return redirect()
            ->route('hospital.coupons.index')
            ->with(
                'success',
                'Coupon deleted successfully.'
            );
    }
}