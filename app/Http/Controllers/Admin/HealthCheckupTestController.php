<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthCheckupTest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HealthCheckupTestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $tests = HealthCheckupTest::withCount('packages')
            ->orderBy('display_order')
            ->latest()
            ->get();

        return view(
            'admin.health_checkup_tests.index',
            compact('tests')
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
            'admin.health_checkup_tests.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:health_checkup_tests,slug',

            'short_description' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'sample_type' =>
                'nullable|string|max:255',

            'report_time' =>
                'nullable|string|max:255',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->slug
            ?: Str::slug($request->name);


        /*
        |--------------------------------------------------------------------------
        | Create Test
        |--------------------------------------------------------------------------
        */

        HealthCheckupTest::create([

            'name' =>
                $request->name,

            'slug' =>
                $slug,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'sample_type' =>
                $request->sample_type,

            'report_time' =>
                $request->report_time,

            'display_order' =>
                $request->display_order ?? 0,

            'status' =>
                $request->has('status')
                ? $request->boolean('status')
                : true,

        ]);


        return redirect()
            ->route('admin.health-checkup-tests.index')
            ->with(
                'success',
                'Health checkup test created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        HealthCheckupTest $healthCheckupTest
    ) {

        $healthCheckupTest->load([
            'packages.healthCheckup',
        ]);

        return view(
            'admin.health_checkup_tests.show',
            compact('healthCheckupTest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        HealthCheckupTest $healthCheckupTest
    ) {
        return view(
            'admin.health_checkup_tests.edit',
            compact('healthCheckupTest')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        HealthCheckupTest $healthCheckupTest
    ) {

        $request->validate([

            'name' =>
                'required|string|max:255',

            'slug' =>
                'nullable|string|max:255|unique:health_checkup_tests,slug,' .
                $healthCheckupTest->id,

            'short_description' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'sample_type' =>
                'nullable|string|max:255',

            'report_time' =>
                'nullable|string|max:255',

            'display_order' =>
                'nullable|integer|min:0',

            'status' =>
                'nullable|boolean',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = $request->slug
            ?: Str::slug($request->name);


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $healthCheckupTest->update([

            'name' =>
                $request->name,

            'slug' =>
                $slug,

            'short_description' =>
                $request->short_description,

            'description' =>
                $request->description,

            'sample_type' =>
                $request->sample_type,

            'report_time' =>
                $request->report_time,

            'display_order' =>
                $request->display_order ?? 0,

            'status' =>
                $request->has('status')
                ? $request->boolean('status')
                : false,

        ]);


        return redirect()
            ->route('admin.health-checkup-tests.index')
            ->with(
                'success',
                'Health checkup test updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        HealthCheckupTest $healthCheckupTest
    ) {

        /*
        |--------------------------------------------------------------------------
        | Check Package Usage
        |--------------------------------------------------------------------------
        */

        if ($healthCheckupTest->packages()->exists()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'This test is already assigned to one or more packages. Remove it from the packages before deleting.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $healthCheckupTest->delete();


        return redirect()
            ->route('admin.health-checkup-tests.index')
            ->with(
                'success',
                'Health checkup test deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status(
        HealthCheckupTest $healthCheckupTest
    ) {

        $healthCheckupTest->update([

            'status' =>
                !$healthCheckupTest->status,

        ]);


        return response()->json([

            'success' => 1,

            'status' =>
                $healthCheckupTest->status,

            'message' =>
                'Status updated successfully.',

        ]);
    }
}