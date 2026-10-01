<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Surgery;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    /**
     * Get all active surgeries.
     */
    public function index(Request $request)
    {
        $query = Surgery::where('status', 1);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere(
                        'short_description',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Get Surgeries
        |--------------------------------------------------------------------------
        */

        $surgeries = $query
            ->orderBy('display_order', 'asc')
            ->latest()
            ->get();

        return response()->json([

            'success' => 1,

            'message' =>
                'Surgeries fetched successfully.',

            'data' => $surgeries->map(function ($surgery) {

                return [

                    'id' =>
                        $surgery->id,

                    'name' =>
                        $surgery->name,

                    'slug' =>
                        $surgery->slug,

                    'image' =>
                        $surgery->image
                        ? asset($surgery->image)
                        : null,

                    'short_description' =>
                        $surgery->short_description,

                    'description' =>
                        $surgery->description,

                    'duration' =>
                        $surgery->duration,

                    'recovery_time' =>
                        $surgery->recovery_time,

                    'preparation_instructions' =>
                        $surgery->preparation_instructions,

                    'post_surgery_care' =>
                        $surgery->post_surgery_care,

                    'display_order' =>
                        $surgery->display_order,

                    'status' =>
                        $surgery->status,

                    'created_at' =>
                        $surgery->created_at,

                    'updated_at' =>
                        $surgery->updated_at,

                ];

            })->values(),

        ]);
    }


    /**
     * Get single surgery.
     */
    public function show($id)
    {
        $surgery = Surgery::where(
            'id',
            $id
        )
            ->where(
                'status',
                1
            )
            ->first();

        if (!$surgery) {

            return response()->json([

                'success' => 0,

                'message' =>
                    'Surgery not found.',

                'data' => null,

            ], 404);
        }

        return response()->json([

            'success' => 1,

            'message' =>
                'Surgery fetched successfully.',

            'data' => [

                'id' =>
                    $surgery->id,

                'name' =>
                    $surgery->name,

                'slug' =>
                    $surgery->slug,

                'image' =>
                    $surgery->image
                    ? asset($surgery->image)
                    : null,

                'short_description' =>
                    $surgery->short_description,

                'description' =>
                    $surgery->description,

                'duration' =>
                    $surgery->duration,

                'recovery_time' =>
                    $surgery->recovery_time,

                'preparation_instructions' =>
                    $surgery->preparation_instructions,

                'post_surgery_care' =>
                    $surgery->post_surgery_care,

                'display_order' =>
                    $surgery->display_order,

                'status' =>
                    $surgery->status,

                'created_at' =>
                    $surgery->created_at,

                'updated_at' =>
                    $surgery->updated_at,

            ],

        ]);
    }
}