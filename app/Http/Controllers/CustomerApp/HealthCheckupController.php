<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HealthCheckup;
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
            'status' => true,
            'message' => 'Health checkups fetched successfully.',
            'data' => $healthCheckups->map(function ($healthCheckup) {
                return [
                    'id' => $healthCheckup->id,
                    'name' => $healthCheckup->name,
                    'slug' => $healthCheckup->slug,
                    'image' => $healthCheckup->image
                        ? asset('storage/' . $healthCheckup->image)
                        : null,
                    'description' => $healthCheckup->description,
                    'created_at' => $healthCheckup->created_at,
                    'updated_at' => $healthCheckup->updated_at,
                ];
            })->values(),
        ]);
    }

}
