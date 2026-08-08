<?php

namespace App\Http\Controllers\HospitalApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineCategoryCollection;
use App\Http\Resources\MedicineCollection;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function categories(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
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

            $search = $request->search;

            $categories->where(function ($query) use ($search) {

                $query->where(
                    'category_name',
                    'LIKE',
                    '%' . $search . '%'
                );
            });
        }

        $categories = $categories
            ->orderBy('category_name')
            ->get();

        if ($categories->isEmpty()) {
            return response()->json([
                'success' => 0,
                'message' => 'No Medicine Categories Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Categories Fetched Successfully',
            'data' => new MedicineCategoryCollection($categories)
        ]);
    }

    public function medicines(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $medicines = Medicine::with([
            'category',
            'hospital'
        ])
            ->where(
                'hospital_id',
                $hospital->id
            );
        if ($request->filled('id')) {

            $medicines->where(
                'id',
                $request->id
            );
        }
        if ($request->filled('medicine_category_id')) {

            $medicines->where(
                'medicine_category_id',
                $request->medicine_category_id
            );
        }
        if ($request->filled('search')) {

            $search = $request->search;

            $medicines->where(function ($query) use ($search) {

                $query->where(
                    'medicine_name',
                    'LIKE',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'medicine_code',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'manufacturer',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'composition',
                        'LIKE',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Prescription Required
        |--------------------------------------------------------------------------
        */

        if ($request->has('prescription_required')) {

            $medicines->where(
                'prescription_required',
                $request->prescription_required
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->has('status')) {

            $medicines->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('stock')) {

            if ($request->stock == 'available') {

                $medicines->where(
                    'stock_quantity',
                    '>',
                    0
                );

            } elseif ($request->stock == 'out_of_stock') {

                $medicines->where(
                    'stock_quantity',
                    '<=',
                    0
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $medicines = $medicines
            ->latest()
            ->paginate(20);

        if ($medicines->isEmpty()) {

            return response()->json([
                'success' => 0,
                'message' => 'No Medicines Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medicines Fetched Successfully',
            'data' => new MedicineCollection($medicines)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Medicine Details
    |--------------------------------------------------------------------------
    */

    public function medicineDetails($id)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $medicine = Medicine::with([
            'category',
            'hospital'
        ])
            ->where(
                'id',
                $id
            )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$medicine) {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine Not Found'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Details Fetched Successfully',

            'data' => new MedicineCollection(
                collect([$medicine])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Medicine
    |--------------------------------------------------------------------------
    */

    public function addMedicine(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'medicine_category_id' =>
                'required|integer',

            'medicine_name' =>
                'required|string|max:255',

            'manufacturer' =>
                'nullable|string|max:255',

            'composition' =>
                'nullable|string',

            'description' =>
                'nullable|string',

            'dosage_form' =>
                'nullable|string|max:100',

            'strength' =>
                'nullable|string|max:100',

            'pack_size' =>
                'nullable|string|max:100',

            'mrp' =>
                'required|numeric|min:0',

            'selling_price' =>
                'required|numeric|min:0',

            'stock_quantity' =>
                'required|integer|min:0',

            'prescription_required' =>
                'nullable|in:0,1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Category
        |--------------------------------------------------------------------------
        */

        $category = MedicineCategory::where(
            'id',
            $request->medicine_category_id
        )
            ->where(
                'status',
                1
            )
            ->first();

        if (!$category) {

            return response()->json([
                'success' => 0,
                'message' => 'Invalid Medicine Category'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Price Validation
        |--------------------------------------------------------------------------
        */

        if (
            (float) $request->selling_price >
            (float) $request->mrp
        ) {

            return response()->json([
                'success' => 0,
                'message' => 'Selling Price Cannot Be Greater Than MRP'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Medicine
        |--------------------------------------------------------------------------
        */

        $medicineExists = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'medicine_name',
                $request->medicine_name
            )
            ->exists();

        if ($medicineExists) {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine Already Exists'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Medicine Code
        |--------------------------------------------------------------------------
        */

        do {

            $medicineCode =
                'MED' . strtoupper(Str::random(7));

        } while (
            Medicine::where(
                'medicine_code',
                $medicineCode
            )->exists()
        );

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $image = null;

        if ($request->hasFile('image')) {

            $file =
                $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/medicines');

            if (!file_exists($directory)) {

                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $file->move(
                $directory,
                $fileName
            );

            $image =
                'uploads/medicines/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Medicine
        |--------------------------------------------------------------------------
        */

        $medicine = Medicine::create([

            'hospital_id' =>
                $hospital->id,

            'medicine_category_id' =>
                $request->medicine_category_id,

            'medicine_name' =>
                $request->medicine_name,

            'medicine_code' =>
                $medicineCode,

            'slug' =>
                Str::slug(
                    $request->medicine_name .
                    '-' .
                    $medicineCode
                ),

            'manufacturer' =>
                $request->manufacturer,

            'composition' =>
                $request->composition,

            'description' =>
                $request->description,

            'dosage_form' =>
                $request->dosage_form,

            'strength' =>
                $request->strength,

            'pack_size' =>
                $request->pack_size,

            'mrp' =>
                $request->mrp,

            'selling_price' =>
                $request->selling_price,

            'stock_quantity' =>
                $request->stock_quantity,

            'prescription_required' =>
                $request->prescription_required ?? 0,

            'image' =>
                $image,

            'status' =>
                $request->status ?? 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $medicine->load([
            'category',
            'hospital'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Added Successfully',

            'data' => new MedicineCollection(
                collect([$medicine])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Medicine
    |--------------------------------------------------------------------------
    */

    public function updateMedicine(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($request->all(), [

            'medicine_id' =>
                'required|integer',

            'medicine_category_id' =>
                'nullable|integer',

            'medicine_name' =>
                'nullable|string|max:255',

            'generic_name' =>
                'nullable|string|max:255',

            'brand_name' =>
                'nullable|string|max:255',

            'manufacturer' =>
                'nullable|string|max:255',

            'medicine_type' =>
                'nullable|string|max:100',

            'strength' =>
                'nullable|string|max:100',

            'pack_size' =>
                'nullable|string|max:100',

            'description' =>
                'nullable|string',

            'composition' =>
                'nullable|string',

            'usage_instructions' =>
                'nullable|string',

            'side_effects' =>
                'nullable|string',

            'storage_instructions' =>
                'nullable|string',

            'mrp' =>
                'nullable|numeric|min:0',

            'selling_price' =>
                'nullable|numeric|min:0',

            'stock_quantity' =>
                'nullable|integer|min:0',

            'prescription_required' =>
                'nullable|in:0,1',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'status' =>
                'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Hospital Medicine
        |--------------------------------------------------------------------------
        */

        $medicine = Medicine::where('id', $request->medicine_id)
            ->where('hospital_id', $hospital->id)
            ->first();

        if (!$medicine) {
            return response()->json([
                'success' => 0,
                'message' => 'Medicine Not Found'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Medicine Category
        |--------------------------------------------------------------------------
        */

        if ($request->has('medicine_category_id')) {

            if (!empty($request->medicine_category_id)) {

                $category = MedicineCategory::where(
                    'id',
                    $request->medicine_category_id
                )
                    ->where('status', 1)
                    ->first();

                if (!$category) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Invalid Medicine Category'
                    ]);
                }

                $medicine->medicine_category_id =
                    $request->medicine_category_id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Name
        |--------------------------------------------------------------------------
        |
        | Medicine name is NOT checked for uniqueness here.
        | medicine_code is used as the medicine's unique identity.
        |
        */

        if ($request->has('medicine_name')) {

            $medicineName = trim(
                (string) $request->medicine_name
            );

            if ($medicineName === '') {
                return response()->json([
                    'success' => 0,
                    'message' => 'Medicine Name Is Required'
                ]);
            }

            $medicine->medicine_name =
                $medicineName;

            /*
            |--------------------------------------------------------------------------
            | Regenerate Slug
            |--------------------------------------------------------------------------
            */

            $medicine->slug = Str::slug(
                $medicineName . '-' . $medicine->medicine_code
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Price
        |--------------------------------------------------------------------------
        */

        $mrp = $request->has('mrp')
            ? (float) $request->mrp
            : (float) $medicine->mrp;

        $sellingPrice = $request->has('selling_price')
            ? (float) $request->selling_price
            : (float) $medicine->selling_price;

        if ($sellingPrice > $mrp) {
            return response()->json([
                'success' => 0,
                'message' => 'Selling Price Cannot Be Greater Than MRP'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Generic Name
        |--------------------------------------------------------------------------
        */

        if ($request->has('generic_name')) {
            $medicine->generic_name =
                $request->generic_name;
        }

        /*
        |--------------------------------------------------------------------------
        | Brand Name
        |--------------------------------------------------------------------------
        */

        if ($request->has('brand_name')) {
            $medicine->brand_name =
                $request->brand_name;
        }

        /*
        |--------------------------------------------------------------------------
        | Manufacturer
        |--------------------------------------------------------------------------
        */

        if ($request->has('manufacturer')) {
            $medicine->manufacturer =
                $request->manufacturer;
        }

        /*
        |--------------------------------------------------------------------------
        | Medicine Type
        |--------------------------------------------------------------------------
        */

        if ($request->has('medicine_type')) {
            $medicine->medicine_type =
                $request->medicine_type;
        }

        /*
        |--------------------------------------------------------------------------
        | Strength
        |--------------------------------------------------------------------------
        */

        if ($request->has('strength')) {
            $medicine->strength =
                $request->strength;
        }

        /*
        |--------------------------------------------------------------------------
        | Pack Size
        |--------------------------------------------------------------------------
        */

        if ($request->has('pack_size')) {
            $medicine->pack_size =
                $request->pack_size;
        }

        /*
        |--------------------------------------------------------------------------
        | Description
        |--------------------------------------------------------------------------
        */

        if ($request->has('description')) {
            $medicine->description =
                $request->description;
        }

        /*
        |--------------------------------------------------------------------------
        | Composition
        |--------------------------------------------------------------------------
        */

        if ($request->has('composition')) {
            $medicine->composition =
                $request->composition;
        }

        /*
        |--------------------------------------------------------------------------
        | Usage Instructions
        |--------------------------------------------------------------------------
        */

        if ($request->has('usage_instructions')) {
            $medicine->usage_instructions =
                $request->usage_instructions;
        }

        /*
        |--------------------------------------------------------------------------
        | Side Effects
        |--------------------------------------------------------------------------
        */

        if ($request->has('side_effects')) {
            $medicine->side_effects =
                $request->side_effects;
        }

        /*
        |--------------------------------------------------------------------------
        | Storage Instructions
        |--------------------------------------------------------------------------
        */

        if ($request->has('storage_instructions')) {
            $medicine->storage_instructions =
                $request->storage_instructions;
        }

        /*
        |--------------------------------------------------------------------------
        | MRP
        |--------------------------------------------------------------------------
        */

        if ($request->has('mrp')) {
            $medicine->mrp =
                $request->mrp;
        }

        /*
        |--------------------------------------------------------------------------
        | Selling Price
        |--------------------------------------------------------------------------
        */

        if ($request->has('selling_price')) {
            $medicine->selling_price =
                $request->selling_price;
        }

        /*
        |--------------------------------------------------------------------------
        | Stock Quantity
        |--------------------------------------------------------------------------
        */

        if ($request->has('stock_quantity')) {
            $medicine->stock_quantity =
                $request->stock_quantity;
        }

        /*
        |--------------------------------------------------------------------------
        | Prescription Required
        |--------------------------------------------------------------------------
        */

        if ($request->has('prescription_required')) {
            $medicine->prescription_required =
                $request->prescription_required;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->has('status')) {
            $medicine->status =
                $request->status;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Medicine Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Old Image
            |--------------------------------------------------------------------------
            */

            if (
                !empty($medicine->image) &&
                file_exists(public_path($medicine->image))
            ) {
                @unlink(
                    public_path($medicine->image)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | New Image
            |--------------------------------------------------------------------------
            */

            $file = $request->file('image');

            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $directory =
                public_path('uploads/medicines');

            /*
            |--------------------------------------------------------------------------
            | Create Directory
            |--------------------------------------------------------------------------
            */

            if (!file_exists($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Move Image
            |--------------------------------------------------------------------------
            */

            $file->move(
                $directory,
                $fileName
            );

            $medicine->image =
                'uploads/medicines/' .
                $fileName;
        }

        /*
        |--------------------------------------------------------------------------
        | Save Medicine
        |--------------------------------------------------------------------------
        */

        $medicine->save();

        /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

        $medicine->load([
            'category',
            'hospital'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Updated Successfully',

            'data' => new MedicineCollection(
                collect([$medicine])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Medicine Stock
    |--------------------------------------------------------------------------
    */

    public function updateStock(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'medicine_id' =>
                'required|integer',

            'stock_quantity' =>
                'required|integer|min:0',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $medicine = Medicine::where(
            'id',
            $request->medicine_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$medicine) {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine Not Found'
            ]);
        }

        $medicine->stock_quantity =
            $request->stock_quantity;

        $medicine->save();

        $medicine->load([
            'category',
            'hospital'
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Medicine Stock Updated Successfully',

            'data' => new MedicineCollection(
                collect([$medicine])
            )
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Medicine Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {
        $hospital = auth('sanctum')->user();

        if (!$hospital) {

            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ], 401);
        }

        $validator = Validator::make($request->all(), [

            'medicine_id' =>
                'required|integer',

            'status' =>
                'required|in:0,1',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        $medicine = Medicine::where(
            'id',
            $request->medicine_id
        )
            ->where(
                'hospital_id',
                $hospital->id
            )
            ->first();

        if (!$medicine) {

            return response()->json([
                'success' => 0,
                'message' => 'Medicine Not Found'
            ]);
        }

        $medicine->status =
            $request->status;

        $medicine->save();

        $medicine->load([
            'category',
            'hospital'
        ]);

        return response()->json([
            'success' => 1,

            'message' =>
                $request->status == 1
                ? 'Medicine Activated Successfully'
                : 'Medicine Deactivated Successfully',

            'data' => new MedicineCollection(
                collect([$medicine])
            )
        ]);
    }
}