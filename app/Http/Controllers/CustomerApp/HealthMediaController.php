<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\HealthMedia;
use Illuminate\Http\Request;

class HealthMediaController extends Controller
{
     /**
     * Display health media records.
     */
    public function index(Request $request)
    {
        $query = HealthMedia::query()
            ->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $healthMedia = $query
            ->latest()
            ->paginate(10);

        $healthMedia->getCollection()->transform(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'description' => $media->description,
                'image' => $media->image,
                'image_url' => $media->image
                    ? asset($media->image)
                    : null,
                'status' => $media->status,
                'created_at' => $media->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Health media retrieved successfully.',
            'data' => $healthMedia->items(),
            'pagination' => [
                'current_page' => $healthMedia->currentPage(),
                'last_page' => $healthMedia->lastPage(),
                'per_page' => $healthMedia->perPage(),
                'total' => $healthMedia->total(),
            ],
        ], 200);
    }

    /**
     * Display a single health media record.
     */
    public function show($id)
    {
        $media = HealthMedia::where('status', 'active')
            ->find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Health media not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Health media retrieved successfully.',
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'description' => $media->description,
                'image' => $media->image,
                'image_url' => $media->image
                    ? asset($media->image)
                    : null,
                'status' => $media->status,
                'created_at' => $media->created_at,
            ],
        ], 200);
    }
}
