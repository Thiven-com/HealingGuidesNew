<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingCollection;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    /**
     * Get site settings.
     */
    public function index()
    {
        $settings = SiteSetting::latest('id')->first();

        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Site settings not found.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Site settings fetched successfully.',
            'data' => new SiteSettingCollection(collect([$settings])),
        ], 200);
    }
}
