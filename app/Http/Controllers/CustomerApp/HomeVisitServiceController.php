<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HomeVisitService;
use Illuminate\Http\Request;

class HomeVisitServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = HomeVisitService::with('category')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Filter by Category ID
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category_id')) {
            $query->where(
                'home_visit_service_categories_id',
                $request->category_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Services
        |--------------------------------------------------------------------------
        */
        $services = $query->get();

        $data = $services->map(function ($service) {

            return [
                'id' => $service->id,

                'category_id' =>
                    $service->home_visit_service_categories_id,

                'category_name' =>
                    optional($service->category)->name,

                'category_slug' =>
                    optional($service->category)->slug,

                'name' =>
                    $service->name,

                'slug' =>
                    $service->slug,

                'image' => $service->image
                    ? asset($service->image)
                    : null,

                'price' =>
                    $service->price !== null
                    ? (float) $service->price
                    : null,

                'price_per' =>
                    $service->price_per,

                'description' =>
                    $service->description,
            ];
        });

        return response()->json([
            'success' => 1,
            'message' => 'Home Visit Services fetched successfully.',
            'data' => $data,
        ], 200);
    }

    /**
     * Get Single Home Visit Service
     */
    // public function show($id)
    // {
    //     $service = HomeVisitService::with('category')
    //         ->find($id);

    //     if (!$service) {
    //         return response()->json([
    //             'success' => 0,
    //             'message' => 'Home Visit Service not found.',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'success' => 1,
    //         'message' => 'Home Visit Service fetched successfully.',
    //         'data' => [
    //             'id' => $service->id,

    //             'category_id' =>
    //                 $service->home_visit_service_categories_id,

    //             'category_name' =>
    //                 optional($service->category)->name,

    //             'category_slug' =>
    //                 optional($service->category)->slug,

    //             'name' =>
    //                 $service->name,

    //             'slug' =>
    //                 $service->slug,

    //             'image' => $service->image
    //                 ? asset($service->image)
    //                 : null,

    //             'price' =>
    //                 $service->price !== null
    //                 ? (float) $service->price
    //                 : null,

    //             'price_per' =>
    //                 $service->price_per,

    //             'description' =>
    //                 $service->description,
    //         ],
    //     ], 200);
    // }
}
