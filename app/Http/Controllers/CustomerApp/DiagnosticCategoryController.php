<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticCategory;
use Illuminate\Http\Request;

class DiagnosticCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $query = DiagnosticCategory::query();

        // Search by category name
        if ($request->filled('search')) {
            $query->where(
                'name',
                'LIKE',
                '%' . $request->search . '%'
            );
        }

        $categories = $query
            ->orderBy('name', 'asc')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Diagnostic Categories Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => $categories,
            'message' => 'Diagnostic Categories Fetched Successfully'
        ]);
    }
}
