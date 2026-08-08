<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\DoctorAppointment;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Coupons
    |--------------------------------------------------------------------------
    */

    public function myCoupons(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $hospitalId = $request->hospital_id;

        /*
        |--------------------------------------------------------------------------
        | Get Active Coupons
        |--------------------------------------------------------------------------
        */

        $query = Coupon::where('status', 1);

        /*
        |--------------------------------------------------------------------------
        | Hospital Filter
        |--------------------------------------------------------------------------
        */

        if ($hospitalId) {

            $query->where(function ($q) use ($hospitalId) {

                $q->whereNull('hospital_id')
                    ->orWhere('hospital_id', $hospitalId);

            });
        }

        $coupons = $query
            ->latest()
            ->get();


        $availableCoupons = [];
        $usedCoupons = [];
        $expiredCoupons = [];
        $upcomingCoupons = [];


        /*
        |--------------------------------------------------------------------------
        | Process Coupons
        |--------------------------------------------------------------------------
        */

        foreach ($coupons as $coupon) {

            /*
            |--------------------------------------------------------------------------
            | Upcoming
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->starts_at &&
                now()->lessThan($coupon->starts_at)
            ) {

                $coupon->coupon_status = 'upcoming';
                $coupon->status_label = 'Coming Soon';

                $upcomingCoupons[] = $coupon;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Expired
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->expires_at &&
                now()->greaterThan($coupon->expires_at)
            ) {

                $coupon->coupon_status = 'expired';
                $coupon->status_label = 'Expired';

                $expiredCoupons[] = $coupon;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Total Usage Limit
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->usage_limit !== null &&
                $coupon->used_count >= $coupon->usage_limit
            ) {

                $coupon->coupon_status = 'expired';
                $coupon->status_label = 'Usage Limit Reached';

                $expiredCoupons[] = $coupon;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Customer Usage
            |--------------------------------------------------------------------------
            */

            $usageCount = CouponUsage::where('coupon_id', $coupon->id)
                ->where('customer_id', $user->id)
                ->count();

            $coupon->customer_usage_count = $usageCount;


            /*
            |--------------------------------------------------------------------------
            | Already Used
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->usage_per_customer !== null &&
                $usageCount >= $coupon->usage_per_customer
            ) {

                $coupon->coupon_status = 'used';
                $coupon->status_label = 'Used';

                $usedCoupons[] = $coupon;

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | New Customer Check
            |--------------------------------------------------------------------------
            */

            if ($coupon->new_customer_only) {

                $hasPreviousAppointment = DoctorAppointment::where(
                    'customer_id',
                    $user->id
                )
                    ->whereNotIn('appointment_status', [
                        'cancelled',
                        'rejected',
                    ])
                    ->exists();


                if ($hasPreviousAppointment) {

                    $coupon->coupon_status = 'not_eligible';
                    $coupon->status_label = 'Not Eligible';

                    continue;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | First Appointment Check
            |--------------------------------------------------------------------------
            */

            if ($coupon->first_appointment_only) {

                $hasPreviousAppointment = DoctorAppointment::where(
                    'customer_id',
                    $user->id
                )
                    ->whereNotIn('appointment_status', [
                        'cancelled',
                        'rejected',
                    ])
                    ->exists();


                if ($hasPreviousAppointment) {

                    $coupon->coupon_status = 'not_eligible';
                    $coupon->status_label = 'Not Eligible';

                    continue;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Available
            |--------------------------------------------------------------------------
            */

            $coupon->coupon_status = 'available';
            $coupon->status_label = 'Available';

            $availableCoupons[] = $coupon;
        }


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'message' => 'Coupons fetched successfully.',

            'data' => [

                'available' => $availableCoupons,

                'upcoming' => $upcomingCoupons,

                'used' => $usedCoupons,

                'expired' => $expiredCoupons,

                'counts' => [
                    'available' => count($availableCoupons),
                    'upcoming' => count($upcomingCoupons),
                    'used' => count($usedCoupons),
                    'expired' => count($expiredCoupons),
                ],
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Coupon
    |--------------------------------------------------------------------------
    */

    public function validate(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | Request Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'code' => 'required|string',

            'amount' => 'required|numeric|min:0',

            'applicable_to' => 'nullable|string',

            'hospital_id' => 'nullable|integer',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Coupon
        |--------------------------------------------------------------------------
        */

        $coupon = Coupon::where(
            'code',
            $request->code
        )
            ->where('status', 1)
            ->first();


        if (!$coupon) {

            return response()->json([
                'success' => 0,
                'message' => 'Invalid coupon code.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Start Date
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->starts_at &&
            now()->lessThan($coupon->starts_at)
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This coupon is not active yet.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Expiry
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->expires_at &&
            now()->greaterThan($coupon->expires_at)
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This coupon has expired.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Hospital Validation
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->hospital_id &&
            $request->hospital_id &&
            (int) $coupon->hospital_id !==
            (int) $request->hospital_id
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This coupon is not valid for this hospital.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Applicable To
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->applicable_to &&
            $request->applicable_to
        ) {

            if (
                $coupon->applicable_to !== 'all' &&
                $coupon->applicable_to !==
                $request->applicable_to
            ) {

                return response()->json([
                    'success' => 0,
                    'message' => 'This coupon cannot be used for this service.'
                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Total Usage Limit
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->usage_limit !== null &&
            $coupon->used_count >= $coupon->usage_limit
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'This coupon usage limit has been reached.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Customer Usage
        |--------------------------------------------------------------------------
        */

        $customerUsageCount = CouponUsage::where(
            'coupon_id',
            $coupon->id
        )
            ->where(
                'customer_id',
                $user->id
            )
            ->count();


        if (
            $coupon->usage_per_customer !== null &&
            $customerUsageCount >=
            $coupon->usage_per_customer
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'You have already used this coupon.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | New Customer Only
        |--------------------------------------------------------------------------
        */

        if ($coupon->new_customer_only) {

            $hasPreviousAppointment =
                DoctorAppointment::where(
                    'customer_id',
                    $user->id
                )
                    ->whereNotIn('appointment_status', [
                        'cancelled',
                        'rejected',
                    ])
                    ->exists();


            if ($hasPreviousAppointment) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This coupon is available only for new customers.'
                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | First Appointment Only
        |--------------------------------------------------------------------------
        */

        if ($coupon->first_appointment_only) {

            $hasPreviousAppointment =
                DoctorAppointment::where(
                    'customer_id',
                    $user->id
                )
                    ->whereNotIn('appointment_status', [
                        'cancelled',
                        'rejected',
                    ])
                    ->exists();


            if ($hasPreviousAppointment) {

                return response()->json([
                    'success' => 0,
                    'message' =>
                        'This coupon is available only for your first appointment.'
                ], 422);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Minimum Order Amount
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->min_order_amount !== null &&
            (float) $request->amount <
            (float) $coupon->min_order_amount
        ) {

            return response()->json([
                'success' => 0,
                'message' =>
                    'Minimum order amount is ₹' .
                    number_format(
                        $coupon->min_order_amount,
                        2
                    )
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Discount
        |--------------------------------------------------------------------------
        */

        $amount = (float) $request->amount;

        $discount = 0;


        /*
        |--------------------------------------------------------------------------
        | Free Appointment
        |--------------------------------------------------------------------------
        */

        if ($coupon->free_appointment) {

            $discount = $amount;
        }


        /*
        |--------------------------------------------------------------------------
        | Percentage
        |--------------------------------------------------------------------------
        */ elseif (
            $coupon->discount_type === 'percentage'
        ) {

            $discount =
                ($amount *
                    (float) $coupon->discount_value)
                / 100;


            /*
            |--------------------------------------------------------------------------
            | Maximum Discount
            |--------------------------------------------------------------------------
            */

            if (
                $coupon->max_discount !== null &&
                $discount >
                (float) $coupon->max_discount
            ) {

                $discount =
                    (float) $coupon->max_discount;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Fixed
        |--------------------------------------------------------------------------
        */ elseif (
            $coupon->discount_type === 'fixed'
        ) {

            $discount =
                (float) $coupon->discount_value;
        }


        /*
        |--------------------------------------------------------------------------
        | Discount Cannot Exceed Amount
        |--------------------------------------------------------------------------
        */

        if ($discount > $amount) {
            $discount = $amount;
        }

        if ($discount < 0) {
            $discount = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Final Amount
        |--------------------------------------------------------------------------
        */

        $finalAmount = $amount - $discount;

        if ($finalAmount < 0) {
            $finalAmount = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'message' => 'Coupon applied successfully.',

            'data' => [

                'coupon_id' => $coupon->id,

                'coupon_code' => $coupon->code,

                'title' => $coupon->title,

                'description' => $coupon->description,

                'coupon_type' => $coupon->coupon_type,

                'discount_type' => $coupon->discount_type,

                'discount_value' =>
                    (float) $coupon->discount_value,

                'max_discount' =>
                    $coupon->max_discount !== null
                    ? (float) $coupon->max_discount
                    : null,

                'original_amount' =>
                    round($amount, 2),

                'discount_amount' =>
                    round($discount, 2),

                'final_amount' =>
                    round($finalAmount, 2),

                'free_appointment' =>
                    (bool) $coupon->free_appointment,

                'expires_at' =>
                    $coupon->expires_at,
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Coupon Usage History
    |--------------------------------------------------------------------------
    */

    public function usageHistory(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }


        $query = CouponUsage::with('coupon')
            ->where(
                'customer_id',
                $user->id
            );


        /*
        |--------------------------------------------------------------------------
        | Hospital Filter
        |--------------------------------------------------------------------------
        */

        if ($request->hospital_id) {

            $query->where(
                'hospital_id',
                $request->hospital_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $usages = $query
            ->latest('used_at')
            ->paginate(
                $request->per_page ?? 20
            );


        return response()->json([
            'success' => 1,
            'message' => 'Coupon usage history fetched successfully.',
            'data' => $usages,
        ]);
    }
}