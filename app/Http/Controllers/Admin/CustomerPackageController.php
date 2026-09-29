<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use App\Services\CustomerPackageService;
use Illuminate\Http\Request;

class CustomerPackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Customer Package
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $customer = Customer::with([
            'package',
            'packageBenefits'
        ])->findOrFail($id);

        $packages = Package::where('status', 1)
            ->with('activeBenefits')
            ->orderBy('display_order')
            ->orderBy('price')
            ->get();

        return view(
            'admin.customers.package',
            compact(
                'customer',
                'packages'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Customer Package
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id,
        CustomerPackageService $packageService
    ) {

        $request->validate([
            'package_id' => [
                'required',
                'exists:packages,id'
            ],
        ]);


        $customer = Customer::findOrFail($id);


        $package = Package::where(
            'id',
            $request->package_id
        )
            ->where('status', 1)
            ->with('activeBenefits')
            ->first();


        if (!$package) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Selected package is not available.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Same Package Check
        |--------------------------------------------------------------------------
        */

        if (
            $customer->package_id &&
            $customer->package_id == $package->id
        ) {

            return redirect()
                ->back()
                ->with(
                    'warning',
                    'Customer already has this package.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Assign New Package
        |--------------------------------------------------------------------------
        */

        $packageService->assignPackage(
            $customer,
            $package
        );


        return redirect()
            ->route(
                'customers.package.show',
                $customer->id
            )
            ->with(
                'success',
                'Customer package updated successfully.'
            );
    }
}