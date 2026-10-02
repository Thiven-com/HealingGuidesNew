<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HealthCheckup;
use App\Models\HealthCheckupPackage;
use Illuminate\Http\Request;

class HealthCheckupController extends Controller
{
    /**
     * Get all health checkups
     */
    public function index()
    {
        $healthCheckups = HealthCheckup::latest()->get();

        return response()->json([
            'success' => 1,
            'message' => 'Health checkups fetched successfully.',
            'data' => $healthCheckups->map(function ($healthCheckup) {

                return [
                    'id' => $healthCheckup->id,
                    'name' => $healthCheckup->name,
                    'slug' => $healthCheckup->slug,

                    'image' => $healthCheckup->image
                        ? asset($healthCheckup->image)
                        : null,

                    'description' => $healthCheckup->description,

                    'created_at' => $healthCheckup->created_at,
                    'updated_at' => $healthCheckup->updated_at,
                ];

            })->values(),
        ]);
    }


    /**
     * Get health checkup with packages
     */
    public function details($id)
    {
        $healthCheckup = HealthCheckup::find($id);

        if (!$healthCheckup) {

            return response()->json([
                'success' => 0,
                'message' => 'Health checkup not found.',
            ], 404);
        }


        $packages = HealthCheckupPackage::where(
            'health_checkup_id',
            $healthCheckup->id
        )
            ->where('status', 1)
            ->withCount('tests')
            ->orderBy('display_order')
            ->latest()
            ->get();


        return response()->json([
            'success' => 1,
            'message' => 'Health checkup details fetched successfully.',
            'data' => [

                'health_checkup' => [
                    'id' => $healthCheckup->id,
                    'name' => $healthCheckup->name,
                    'slug' => $healthCheckup->slug,

                    'image' => $healthCheckup->image
                        ? asset($healthCheckup->image)
                        : null,

                    'description' => $healthCheckup->description,
                ],

                'packages' => $packages->map(function ($package) {

                    return $this->packageData($package);

                })->values(),

            ],
        ]);
    }


    /**
     * Get all health checkup packages
     */
    public function packages(Request $request)
    {
        $query = HealthCheckupPackage::where('status', 1)
            ->with([
                'healthCheckup:id,name,slug,image'
            ])
            ->withCount('tests');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'short_description',
                        'like',
                        "%{$search}%"
                    );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Health Checkup Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('health_checkup_id')) {

            $query->where(
                'health_checkup_id',
                $request->health_checkup_id
            );
        }

        if ($request->filled('home_collection') && $request->home_collection == 1) {
            $query->where('home_collection', 1);
        }


        if ($request->filled('centre_collection') && $request->centre_collection == 1) {
            $query->where('centre_collection', 1);
        }



        $packages = $query
            ->orderBy('display_order')
            ->latest()
            ->get();


        return response()->json([
            'success' => 1,
            'message' => 'Health checkup packages fetched successfully.',
            'data' => $packages->map(function ($package) {

                return $this->packageData($package);

            })->values(),
        ]);
    }


    /**
     * Get single package details
     */
    public function packageDetails($id)
    {
        $package = HealthCheckupPackage::where('id', $id)
            ->where('status', 1)
            ->with([
                'healthCheckup:id,name,slug,image',
                'tests' => function ($query) {

                    $query->where('status', 1)
                        ->orderBy('display_order')
                        ->orderBy('name');

                }
            ])
            ->withCount('tests')
            ->first();


        if (!$package) {

            return response()->json([
                'success' => 0,
                'message' => 'Health checkup package not found.',
            ], 404);
        }


        return response()->json([
            'success' => 1,
            'message' => 'Health checkup package fetched successfully.',
            'data' => [

                'id' => $package->id,

                'name' => $package->name,

                'slug' => $package->slug,

                'image' => $package->image
                    ? asset($package->image)
                    : null,

                'short_description' =>
                    $package->short_description,

                'description' =>
                    $package->description,

                'mrp' =>
                    (float) $package->mrp,

                'price' =>
                    (float) $package->price,

                'discount_percentage' =>
                    $this->discountPercentage(
                        $package->mrp,
                        $package->price
                    ),

                'test_count' =>
                    $package->tests_count,

                'health_checkup' => $package->healthCheckup
                    ? [
                        'id' => $package->healthCheckup->id,
                        'name' => $package->healthCheckup->name,
                        'slug' => $package->healthCheckup->slug,

                        'image' => $package->healthCheckup->image
                            ? asset(
                                $package->healthCheckup->image
                            )
                            : null,
                    ]
                    : null,

                'tests' => $package->tests->map(function ($test) {

                    return [
                        'id' => $test->id,
                        'name' => $test->name,
                        'code' => $test->code ?? null,
                        'description' =>
                            $test->description ?? null,
                        'sample_type' =>
                            $test->sample_type ?? null,
                        'report_time' =>
                            $test->report_time ?? null,
                    ];

                })->values(),

            ],
        ]);
    }


    /**
     * Get tests of a package
     */
    public function packageTests($id)
    {
        $package = HealthCheckupPackage::where('id', $id)
            ->where('status', 1)
            ->first();


        if (!$package) {

            return response()->json([
                'success' => 0,
                'message' => 'Health checkup package not found.',
            ], 404);
        }


        $tests = $package->tests()
            ->where('status', 1)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();


        return response()->json([
            'success' => 1,
            'message' => 'Package tests fetched successfully.',
            'data' => [

                'package' => [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price' => (float) $package->price,
                    'mrp' =>
                        (float) $package->mrp,
                ],

                'total_tests' => $tests->count(),

                'tests' => $tests->map(function ($test) {

                    return [
                        'id' => $test->id,
                        'name' => $test->name,
                        'code' => $test->code ?? null,
                        'description' =>
                            $test->description ?? null,
                        'sample_type' =>
                            $test->sample_type ?? null,
                        'report_time' =>
                            $test->report_time ?? null,
                        'display_order' =>
                            $test->display_order ?? 0,
                    ];

                })->values(),

            ],
        ]);
    }


    /**
     * Package response helper
     */
    private function packageData($package)
    {
        return [
            'id' => $package->id,

            'name' => $package->name,

            'slug' => $package->slug,

            'image' => $package->image
                ? asset($package->image)
                : null,

            'short_description' =>
                $package->short_description,

            'mrp' =>
                (float) $package->mrp,

            'price' =>
                (float) $package->price,

            'discount_percentage' =>
                $this->discountPercentage(
                    $package->mrp,
                    $package->price
                ),
            'home_collection' => (int) $package->home_collection,

            'centre_collection' => (int) $package->centre_collection,

            'test_count' =>
                $package->tests_count ?? 0,

            'health_checkup' => $package->healthCheckup
                ? [
                    'id' => $package->healthCheckup->id,
                    'name' => $package->healthCheckup->name,
                    'slug' => $package->healthCheckup->slug,

                    'image' => $package->healthCheckup->image
                        ? asset(
                            $package->healthCheckup->image
                        )
                        : null,
                ]
                : null,
        ];
    }


    /**
     * Calculate discount percentage
     */
    private function discountPercentage($originalPrice, $price)
    {
        $originalPrice = (float) $originalPrice;
        $price = (float) $price;

        if ($originalPrice <= 0 || $price >= $originalPrice) {
            return 0;
        }

        return round(
            (($originalPrice - $price) / $originalPrice) * 100,
            2
        );
    }
}