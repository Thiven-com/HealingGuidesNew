<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('applicable_to')) {
            $query->where(
                'applicable_to',
                $request->applicable_to
            );
        }

        $coupons = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.coupons.index',
            compact('coupons')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.coupons.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupons,code',
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

            'coupon_type' => [
                'required',
                'in:coupon,offer',
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

            'applicable_to' => [
                'required',
                'in:all,appointment,medicine',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'usage_per_customer' => [
                'nullable',
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

        $code = strtoupper(
            trim($validated['code'])
        );


        /*
        |--------------------------------------------------------------------------
        | Free Appointment
        |--------------------------------------------------------------------------
        */

        $freeAppointment =
            $request->boolean(
                'free_appointment'
            );


        if ($freeAppointment) {

            $validated['discount_type'] = 'free';

            $validated['discount_value'] = 100;

            $validated['applicable_to'] = 'appointment';

        }


        /*
        |--------------------------------------------------------------------------
        | Create Coupon
        |--------------------------------------------------------------------------
        */

        Coupon::create([

            'created_by_type' => 'admin',

            'created_by_id' => auth('admin')->id(),

            'hospital_id' => null,

            'code' => $code,

            'title' => $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'coupon_type' =>
                $validated['coupon_type'],

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'] ?? 0,

            'max_discount' =>
                $validated['max_discount'] ?? null,

            'min_order_amount' =>
                $validated['min_order_amount'] ?? 0,

            'applicable_to' =>
                $validated['applicable_to'],

            'new_customer_only' =>
                $request->boolean(
                    'new_customer_only'
                ),

            'first_appointment_only' =>
                $request->boolean(
                    'first_appointment_only'
                ),

            'free_appointment' =>
                $freeAppointment,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'usage_per_customer' =>
                $validated['usage_per_customer'] ?? 1,

            'used_count' => 0,

            'starts_at' =>
                $validated['starts_at'] ?? null,

            'expires_at' =>
                $validated['expires_at'] ?? null,

            'status' =>
                $request->has('status')
                ? $request->boolean('status')
                : true,
        ]);


        return redirect()
            ->route('admin.coupons.index')
            ->with(
                'success',
                'Coupon created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $coupon = Coupon::with([
            'usages.customer',
            'usages.appointment',
        ])->findOrFail($id);

        return view(
            'admin.coupons.show',
            compact('coupon')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);

        return view(
            'admin.coupons.edit',
            compact('coupon')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupons,code,' . $coupon->id,
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

            'coupon_type' => [
                'required',
                'in:coupon,offer',
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

            'applicable_to' => [
                'required',
                'in:all,appointment,medicine',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'usage_per_customer' => [
                'nullable',
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

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);


        $freeAppointment =
            $request->boolean(
                'free_appointment'
            );


        if ($freeAppointment) {

            $validated['discount_type'] = 'free';

            $validated['discount_value'] = 100;

            $validated['applicable_to'] =
                'appointment';
        }


        $coupon->update([

            'code' => strtoupper(
                trim($validated['code'])
            ),

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'coupon_type' =>
                $validated['coupon_type'],

            'discount_type' =>
                $validated['discount_type'],

            'discount_value' =>
                $validated['discount_value'] ?? 0,

            'max_discount' =>
                $validated['max_discount'] ?? null,

            'min_order_amount' =>
                $validated['min_order_amount'] ?? 0,

            'applicable_to' =>
                $validated['applicable_to'],

            'new_customer_only' =>
                $request->boolean(
                    'new_customer_only'
                ),

            'first_appointment_only' =>
                $request->boolean(
                    'first_appointment_only'
                ),

            'free_appointment' =>
                $freeAppointment,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'usage_per_customer' =>
                $validated['usage_per_customer'] ?? 1,

            'starts_at' =>
                $validated['starts_at'] ?? null,

            'expires_at' =>
                $validated['expires_at'] ?? null,

            'status' =>
                $request->boolean('status'),
        ]);


        return redirect()
            ->route(
                'admin.coupons.index'
            )
            ->with(
                'success',
                'Coupon updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->status =
            !$coupon->status;

        $coupon->save();

        return redirect()
            ->back()
            ->with(
                'success',
                $coupon->status
                ? 'Coupon activated successfully.'
                : 'Coupon deactivated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Don't delete coupons that have been used
        |--------------------------------------------------------------------------
        */

        if ($coupon->used_count > 0) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This coupon cannot be deleted because it has already been used.'
                );
        }


        $coupon->delete();

        return redirect()
            ->route(
                'admin.coupons.index'
            )
            ->with(
                'success',
                'Coupon deleted successfully.'
            );
    }
}