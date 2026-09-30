<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthCheckup;
use App\Models\HealthCheckupPackage;
use App\Models\HealthCheckupTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HealthCheckupPackageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $packages = HealthCheckupPackage::with('healthCheckup')
            ->withCount('tests')
            ->orderBy('display_order')
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.health_checkup_packages.index',
            compact('packages')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $healthCheckups = HealthCheckup::orderBy('name')
            ->get();

        $tests = HealthCheckupTest::where('status', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.health_checkup_packages.create',
            compact(
                'healthCheckups',
                'tests'
            )
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

            'health_checkup_id' => [
                'required',
                'exists:health_checkups,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_checkup_packages,slug',
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'original_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'tests' => [
                'nullable',
                'array',
            ],

            'tests.*' => [
                'exists:health_checkup_tests,id',
            ],

        ]);


        DB::transaction(function () use ($request, $validated) {

            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */

            $slug = $validated['slug']
                ?? Str::slug($validated['name']);


            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Generated Slug
            |--------------------------------------------------------------------------
            */

            $originalSlug = $slug;
            $counter = 1;

            while (
                HealthCheckupPackage::where(
                    'slug',
                    $slug
                )->exists()
            ) {

                $slug =
                    $originalSlug .
                    '-' .
                    $counter++;

            }


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            $image = null;

            if ($request->hasFile('image')) {

                $file = $request->file('image');

                $fileName =
                    time() .
                    '_' .
                    Str::slug(
                        pathinfo(
                            $file->getClientOriginalName(),
                            PATHINFO_FILENAME
                        )
                    ) .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path('uploads/health-checkup-packages'),
                    $fileName
                );

                $image =
                    'uploads/health-checkup-packages/' .
                    $fileName;
            }


            /*
            |--------------------------------------------------------------------------
            | Package
            |--------------------------------------------------------------------------
            */

            $package =
                HealthCheckupPackage::create([

                    'health_checkup_id' =>
                        $validated['health_checkup_id'],

                    'name' =>
                        $validated['name'],

                    'slug' =>
                        $slug,

                    'short_description' =>
                        $validated['short_description'] ?? null,

                    'description' =>
                        $validated['description'] ?? null,

                    'image' =>
                        $image,

                    'original_price' =>
                        $validated['original_price'],

                    'price' =>
                        $validated['price'],

                    'display_order' =>
                        $validated['display_order'] ?? 0,

                    'status' =>
                        $request->boolean('status'),

                ]);


            /*
            |--------------------------------------------------------------------------
            | Attach Tests
            |--------------------------------------------------------------------------
            */

            $tests = $request->input('tests', []);

            $syncData = [];

            foreach ($tests as $index => $testId) {

                $syncData[$testId] = [
                    'display_order' =>
                        $index,
                ];

            }

            $package->tests()->sync(
                $syncData
            );
        });


        return redirect()
            ->route(
                'admin.health-checkup-packages.index'
            )
            ->with(
                'success',
                'Health checkup package created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        HealthCheckupPackage $healthCheckupPackage
    ) {

        $healthCheckupPackage->load([
            'healthCheckup',
            'tests',
        ]);

        return view(
            'admin.health_checkup_packages.show',
            compact(
                'healthCheckupPackage'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        HealthCheckupPackage $healthCheckupPackage
    ) {

        $healthCheckups =
            HealthCheckup::orderBy('name')
                ->get();

        $tests =
            HealthCheckupTest::where('status', 1)
                ->orderBy('display_order')
                ->orderBy('name')
                ->get();

        $healthCheckupPackage->load('tests');

        $selectedTests =
            $healthCheckupPackage
                ->tests
                ->pluck('id')
                ->toArray();

        return view(
            'admin.health_checkup_packages.edit',
            compact(
                'healthCheckupPackage',
                'healthCheckups',
                'tests',
                'selectedTests'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        HealthCheckupPackage $healthCheckupPackage
    ) {

        $validated = $request->validate([

            'health_checkup_id' => [
                'required',
                'exists:health_checkups,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_checkup_packages,slug,' .
                $healthCheckupPackage->id,
            ],

            'short_description' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'original_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'tests' => [
                'nullable',
                'array',
            ],

            'tests.*' => [
                'exists:health_checkup_tests,id',
            ],

        ]);


        DB::transaction(function () use ($request, $validated, $healthCheckupPackage) {

            /*
            |--------------------------------------------------------------------------
            | Slug
            |--------------------------------------------------------------------------
            */

            $slug =
                $validated['slug']
                ?? Str::slug($validated['name']);


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            $image =
                $healthCheckupPackage->image;

            if ($request->hasFile('image')) {

                /*
                |--------------------------------------------------------------------------
                | Delete Old Image
                |--------------------------------------------------------------------------
                */

                if (
                    $image &&
                    file_exists(
                        public_path($image)
                    )
                ) {

                    unlink(
                        public_path($image)
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Upload New Image
                |--------------------------------------------------------------------------
                */

                $file =
                    $request->file('image');

                $fileName =
                    time() .
                    '_' .
                    Str::slug(
                        pathinfo(
                            $file->getClientOriginalName(),
                            PATHINFO_FILENAME
                        )
                    ) .
                    '.' .
                    $file->getClientOriginalExtension();

                $file->move(
                    public_path(
                        'uploads/health-checkup-packages'
                    ),
                    $fileName
                );

                $image =
                    'uploads/health-checkup-packages/' .
                    $fileName;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Package
            |--------------------------------------------------------------------------
            */

            $healthCheckupPackage->update([

                'health_checkup_id' =>
                    $validated['health_checkup_id'],

                'name' =>
                    $validated['name'],

                'slug' =>
                    $slug,

                'short_description' =>
                    $validated['short_description'] ?? null,

                'description' =>
                    $validated['description'] ?? null,

                'image' =>
                    $image,

                'original_price' =>
                    $validated['original_price'],

                'price' =>
                    $validated['price'],

                'display_order' =>
                    $validated['display_order'] ?? 0,

                'status' =>
                    $request->boolean('status'),

            ]);


            /*
            |--------------------------------------------------------------------------
            | Sync Tests
            |--------------------------------------------------------------------------
            */

            $tests =
                $request->input(
                    'tests',
                    []
                );

            $syncData = [];

            foreach ($tests as $index => $testId) {

                $syncData[$testId] = [
                    'display_order' =>
                        $index,
                ];

            }

            $healthCheckupPackage
                ->tests()
                ->sync($syncData);
        });


        return redirect()
            ->route(
                'admin.health-checkup-packages.index'
            )
            ->with(
                'success',
                'Health checkup package updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        HealthCheckupPackage $healthCheckupPackage
    ) {

        DB::transaction(function () use ($healthCheckupPackage) {

            /*
            |--------------------------------------------------------------------------
            | Delete Image
            |--------------------------------------------------------------------------
            */

            if (
                $healthCheckupPackage->image &&
                file_exists(
                    public_path(
                        $healthCheckupPackage->image
                    )
                )
            ) {

                unlink(
                    public_path(
                        $healthCheckupPackage->image
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Remove Tests
            |--------------------------------------------------------------------------
            */

            $healthCheckupPackage
                ->tests()
                ->detach();


            /*
            |--------------------------------------------------------------------------
            | Delete Package
            |--------------------------------------------------------------------------
            */

            $healthCheckupPackage->delete();
        });


        return redirect()
            ->route(
                'admin.health-checkup-packages.index'
            )
            ->with(
                'success',
                'Health checkup package deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function status(
        Request $request,
        HealthCheckupPackage $healthCheckupPackage
    ) {

        $request->validate([
            'status' => [
                'required',
                'boolean',
            ],
        ]);


        $healthCheckupPackage->update([

            'status' =>
                $request->boolean('status'),

        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Package status updated successfully.',

            'status' =>
                $healthCheckupPackage->status,

        ]);
    }
}