<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineCategoryCollection;
use App\Http\Resources\MedicineCollection;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Medicine Categories
    |--------------------------------------------------------------------------
    */

    public function categories(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $categories = MedicineCategory::where('status', 1);

        if ($request->filled('id')) {
            $categories->where('id', $request->id);
        }

        if ($request->filled('search')) {
            $categories->where(
                'category_name',
                'LIKE',
                '%' . $request->search . '%'
            );
        }

        $categories = $categories
            ->orderBy('sort_order')
            ->paginate(20);

        if ($categories->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Medicine Categories Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new MedicineCategoryCollection($categories),
            'message' => 'Medicine Categories Fetched Successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | All Medicines
    |--------------------------------------------------------------------------
    */

    public function medicines(Request $request)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $medicines = Medicine::with([
            'category',
            'hospital'
        ])
            ->where('status', 1)
            ->where('stock_quantity', '>', 0);

        /*
        |--------------------------------------------------------------------------
        | Medicine ID
        |--------------------------------------------------------------------------
        */

        if ($request->filled('id')) {
            $medicines->where('id', $request->id);
        }

        /*
        |--------------------------------------------------------------------------
        | Hospital
        |--------------------------------------------------------------------------
        */

        if ($request->filled('hospital_id')) {
            $medicines->where(
                'hospital_id',
                $request->hospital_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Category
        |--------------------------------------------------------------------------
        */

        if ($request->filled('medicine_category_id')) {
            $medicines->where(
                'medicine_category_id',
                $request->medicine_category_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $medicines->where(function ($query) use ($search) {

                $query->where(
                    'medicine_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'generic_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'brand_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'manufacturer',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('medicine_type')) {
            $medicines->where(
                'medicine_type',
                $request->medicine_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prescription Required
        |--------------------------------------------------------------------------
        */

        if ($request->filled('prescription_required')) {
            $medicines->where(
                'prescription_required',
                $request->prescription_required
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->sort == 'price_low') {

            $medicines->orderBy(
                'selling_price',
                'asc'
            );

        } elseif ($request->sort == 'price_high') {

            $medicines->orderBy(
                'selling_price',
                'desc'
            );

        } else {

            $medicines->latest();
        }

        $medicines = $medicines->paginate(20);

        if ($medicines->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Medicines Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new MedicineCollection($medicines),
            'message' => 'Medicines Fetched Successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Medicine Details
    |--------------------------------------------------------------------------
    */

    public function medicineDetails($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $medicine = Medicine::with([
            'category',
            'hospital'
        ])
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$medicine) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine not found.'
            ]);
        }

        return response()->json([
            'success' => 1,
            'data' => new MedicineCollection(
                collect([$medicine])
            ),
            'message' => 'Medicine Details Fetched Successfully'
        ]);
    }
}