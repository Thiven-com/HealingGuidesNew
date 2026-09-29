<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerPackageBenefit;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class CustomerPackageService
{
    /*
    |--------------------------------------------------------------------------
    | Assign Package
    |--------------------------------------------------------------------------
    */

    public function assignPackage(
        Customer $customer,
        Package $package,
        $startDate = null
    ) {
        return DB::transaction(function () use ($customer, $package, $startDate) {

            $startDate = $startDate
                ? \Carbon\Carbon::parse($startDate)
                : now();

            $expiryDate = $startDate->copy()
                ->addDays($package->duration_days);


            /*
            |--------------------------------------------------------------------------
            | Remove Existing Customer Benefits
            |--------------------------------------------------------------------------
            */

            CustomerPackageBenefit::where(
                'customer_id',
                $customer->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Update Customer Package
            |--------------------------------------------------------------------------
            */

            $customer->update([

                'package_id' =>
                    $package->id,

                'package_start_date' =>
                    $startDate->toDateString(),

                'package_expiry_date' =>
                    $expiryDate->toDateString(),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Copy Package Benefits
            |--------------------------------------------------------------------------
            */

            $package->load('activeBenefits');

            foreach ($package->activeBenefits as $benefit) {

                CustomerPackageBenefit::create([

                    'customer_id' =>
                        $customer->id,

                    'package_id' =>
                        $package->id,

                    'benefit_type' =>
                        $benefit->benefit_type,

                    'benefit_name' =>
                        $benefit->benefit_name,

                    'total_quantity' =>
                        $benefit->quantity,

                    'used_quantity' =>
                        0,

                ]);
            }


            return $customer->fresh([
                'package',
                'packageBenefits',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Consume Benefit
    |--------------------------------------------------------------------------
    */

    public function consumeBenefit(
        Customer $customer,
        string $benefitType
    ) {

        return DB::transaction(function () use ($customer, $benefitType) {

            /*
            |--------------------------------------------------------------------------
            | Package Check
            |--------------------------------------------------------------------------
            */

            if (!$customer->package_id) {

                return [
                    'success' => false,
                    'message' =>
                        'Customer does not have an active package.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Expiry Check
            |--------------------------------------------------------------------------
            */

            if (
                !$customer->package_expiry_date ||
                now()->startOfDay()->gt(
                    $customer->package_expiry_date
                )
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'Customer package has expired.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Benefit
            |--------------------------------------------------------------------------
            */

            $benefit =
                CustomerPackageBenefit::where(
                    'customer_id',
                    $customer->id
                )
                    ->where(
                        'package_id',
                        $customer->package_id
                    )
                    ->where(
                        'benefit_type',
                        $benefitType
                    )
                    ->lockForUpdate()
                    ->first();


            if (!$benefit) {

                return [
                    'success' => false,
                    'message' =>
                        'This benefit is not available in your package.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Remaining Check
            |--------------------------------------------------------------------------
            */

            if (
                $benefit->used_quantity >=
                $benefit->total_quantity
            ) {

                return [
                    'success' => false,
                    'message' =>
                        'No remaining benefit available.'
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Use Benefit
            |--------------------------------------------------------------------------
            */

            $benefit->increment(
                'used_quantity'
            );

            $benefit->refresh();


            return [
                'success' => true,
                'message' =>
                    'Package benefit used successfully.',
                'benefit' => $benefit,
            ];
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Check Benefit
    |--------------------------------------------------------------------------
    */

    public function checkBenefit(
        Customer $customer,
        string $benefitType
    ) {

        if (!$customer->package_id) {

            return [
                'available' => false,
                'message' => 'No package assigned.'
            ];
        }


        if (
            !$customer->package_expiry_date ||
            now()->startOfDay()->gt(
                $customer->package_expiry_date
            )
        ) {

            return [
                'available' => false,
                'message' => 'Package expired.'
            ];
        }


        $benefit =
            CustomerPackageBenefit::where(
                'customer_id',
                $customer->id
            )
                ->where(
                    'package_id',
                    $customer->package_id
                )
                ->where(
                    'benefit_type',
                    $benefitType
                )
                ->first();


        if (!$benefit) {

            return [
                'available' => false,
                'message' => 'Benefit not available.'
            ];
        }


        if (
            $benefit->remaining_quantity <= 0
        ) {

            return [
                'available' => false,
                'message' => 'No remaining benefit.'
            ];
        }


        return [
            'available' => true,
            'message' => 'Benefit available.',
            'benefit' => $benefit,
        ];
    }
}