<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\EmergencyConnect;
use Illuminate\Http\Request;

class EmergencyConnectController extends Controller
{
    /**
     * Get all active Emergency Connects
     */
    public function index(Request $request)
    {
        $query = EmergencyConnect::query()
            ->where('status', 1);

        // Optional title search
        if ($request->filled('title')) {
            $query->where(
                'title',
                'like',
                '%' . trim($request->title) . '%'
            );
        }

        $emergencyConnects = $query
            ->latest('id')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Emergency Connects fetched successfully.',
            'data' => $emergencyConnects->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,

                    'image' => $item->image
                        ? asset($item->image)
                        : null,

                    'status' => (bool) $item->status,

                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            })->values(),
        ]);
    }
}
