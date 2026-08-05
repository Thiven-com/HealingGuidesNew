<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Medicine List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $medicines = Medicine::with([
            'hospital',
            'category'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

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
        if ($request->filled('hospital_id')) {

            $medicines->where(
                'hospital_id',
                $request->hospital_id
            );
        }
        if ($request->filled('medicine_category_id')) {

            $medicines->where(
                'medicine_category_id',
                $request->medicine_category_id
            );
        }
        if ($request->filled('status')) {

            $medicines->where(
                'status',
                $request->status
            );
        }
        $medicines = $medicines
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.medicines.index',
            compact('medicines')
        );
    }



    public function create()
    {
        $hospitals = Hospital::where(
            'status',
            1
        )
            ->orderBy('hospital_name')
            ->get();

        $categories = MedicineCategory::where(
            'status',
            1
        )
            ->orderBy('sort_order')
            ->orderBy('category_name')
            ->get();

        return view(
            'admin.medicines.create',
            compact(
                'hospitals',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Medicine
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [

                'hospital_id' =>
                    'required|integer',

                'medicine_category_id' =>
                    'required|integer',

                'medicine_name' =>
                    'required|string|max:255',

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
                    'required|numeric|min:0',

                'selling_price' =>
                    'required|numeric|min:0',

                'stock_quantity' =>
                    'required|integer|min:0',

                'prescription_required' =>
                    'required|in:0,1',

                'status' =>
                    'required|in:0,1',

                'image' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]
        );


        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'id',
            $request->hospital_id
        )
            ->where('status', 1)
            ->first();


        if (!$hospital) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Invalid Hospital'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Category
        |--------------------------------------------------------------------------
        */

        $category = MedicineCategory::where(
            'id',
            $request->medicine_category_id
        )
            ->where('status', 1)
            ->first();


        if (!$category) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Invalid Medicine Category'
                )
                ->withInput();
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

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Selling Price Cannot Be Greater Than MRP'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Medicine
        |--------------------------------------------------------------------------
        */

        $medicineExists = Medicine::where(
            'hospital_id',
            $request->hospital_id
        )
            ->where(
                'medicine_name',
                trim($request->medicine_name)
            )
            ->exists();


        if ($medicineExists) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Medicine Already Exists For This Hospital'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Medicine Code
        |--------------------------------------------------------------------------
        */

        do {

            $medicineCode =
                'MED' .
                strtoupper(
                    Str::random(7)
                );

        } while (
            Medicine::where(
                'medicine_code',
                $medicineCode
            )->exists()
        );


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug(
            $request->medicine_name .
            '-' .
            $medicineCode
        );


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $imagePath = null;


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
                public_path(
                    'uploads/medicines'
                );


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


            $imagePath =
                'uploads/medicines/' .
                $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Medicine
        |--------------------------------------------------------------------------
        */

        Medicine::create([

            'hospital_id' =>
                $request->hospital_id,

            'medicine_category_id' =>
                $request->medicine_category_id,

            'medicine_name' =>
                trim($request->medicine_name),

            'medicine_code' =>
                $medicineCode,

            'slug' =>
                $slug,

            'generic_name' =>
                $request->generic_name,

            'brand_name' =>
                $request->brand_name,

            'manufacturer' =>
                $request->manufacturer,

            'medicine_type' =>
                $request->medicine_type,

            'strength' =>
                $request->strength,

            'pack_size' =>
                $request->pack_size,

            'image' =>
                $imagePath,

            'description' =>
                $request->description,

            'composition' =>
                $request->composition,

            'usage_instructions' =>
                $request->usage_instructions,

            'side_effects' =>
                $request->side_effects,

            'storage_instructions' =>
                $request->storage_instructions,

            'mrp' =>
                $request->mrp,

            'selling_price' =>
                $request->selling_price,

            'stock_quantity' =>
                $request->stock_quantity,

            'prescription_required' =>
                $request->prescription_required,

            'status' =>
                $request->status,
        ]);


        return redirect()
            ->route(
                'admin.medicines.index'
            )
            ->with(
                'success',
                'Medicine Created Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Edit Medicine
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $medicine = Medicine::findOrFail(
            $id
        );


        $hospitals = Hospital::where(
            'status',
            1
        )
            ->orderBy('hospital_name')
            ->get();


        $categories = MedicineCategory::where(
            'status',
            1
        )
            ->orderBy('sort_order')
            ->orderBy('category_name')
            ->get();


        return view(
            'admin.medicines.edit',
            compact(
                'medicine',
                'hospitals',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Medicine
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $medicine = Medicine::findOrFail(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [

                'hospital_id' =>
                    'required|integer',

                'medicine_category_id' =>
                    'required|integer',

                'medicine_name' =>
                    'required|string|max:255',

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
                    'required|numeric|min:0',

                'selling_price' =>
                    'required|numeric|min:0',

                'stock_quantity' =>
                    'required|integer|min:0',

                'prescription_required' =>
                    'required|in:0,1',

                'status' =>
                    'required|in:0,1',

                'image' =>
                    'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]
        );


        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Hospital
        |--------------------------------------------------------------------------
        */

        $hospital = Hospital::where(
            'id',
            $request->hospital_id
        )
            ->where('status', 1)
            ->first();


        if (!$hospital) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Invalid Hospital'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Category
        |--------------------------------------------------------------------------
        */

        $category = MedicineCategory::where(
            'id',
            $request->medicine_category_id
        )
            ->where('status', 1)
            ->first();


        if (!$category) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Invalid Medicine Category'
                )
                ->withInput();
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

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Selling Price Cannot Be Greater Than MRP'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Check
        |--------------------------------------------------------------------------
        */

        $medicineExists = Medicine::where(
            'hospital_id',
            $request->hospital_id
        )
            ->where(
                'medicine_name',
                trim($request->medicine_name)
            )
            ->where(
                'id',
                '!=',
                $medicine->id
            )
            ->exists();


        if ($medicineExists) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Medicine Already Exists For This Hospital'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Existing Image
            |--------------------------------------------------------------------------
            */

            if (
                $medicine->image &&
                file_exists(
                    public_path(
                        $medicine->image
                    )
                )
            ) {

                @unlink(
                    public_path(
                        $medicine->image
                    )
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Image
            |--------------------------------------------------------------------------
            */

            $file =
                $request->file('image');


            $fileName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();


            $directory =
                public_path(
                    'uploads/medicines'
                );


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


            $medicine->image =
                'uploads/medicines/' .
                $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Update Medicine
        |--------------------------------------------------------------------------
        */

        $medicine->hospital_id =
            $request->hospital_id;


        $medicine->medicine_category_id =
            $request->medicine_category_id;


        $medicine->medicine_name =
            trim(
                $request->medicine_name
            );


        $medicine->slug =
            Str::slug(
                $request->medicine_name .
                '-' .
                $medicine->medicine_code
            );


        $medicine->generic_name =
            $request->generic_name;


        $medicine->brand_name =
            $request->brand_name;


        $medicine->manufacturer =
            $request->manufacturer;


        $medicine->medicine_type =
            $request->medicine_type;


        $medicine->strength =
            $request->strength;


        $medicine->pack_size =
            $request->pack_size;


        $medicine->description =
            $request->description;


        $medicine->composition =
            $request->composition;


        $medicine->usage_instructions =
            $request->usage_instructions;


        $medicine->side_effects =
            $request->side_effects;


        $medicine->storage_instructions =
            $request->storage_instructions;


        $medicine->mrp =
            $request->mrp;


        $medicine->selling_price =
            $request->selling_price;


        $medicine->stock_quantity =
            $request->stock_quantity;


        $medicine->prescription_required =
            $request->prescription_required;


        $medicine->status =
            $request->status;


        $medicine->save();


        return redirect()
            ->route(
                'admin.medicines.index'
            )
            ->with(
                'success',
                'Medicine Updated Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Status
    |--------------------------------------------------------------------------
    */

    public function status($id)
    {
        $medicine = Medicine::findOrFail(
            $id
        );


        $medicine->status =
            $medicine->status ? 0 : 1;


        $medicine->save();


        return redirect()
            ->back()
            ->with(
                'success',
                'Medicine Status Updated Successfully'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Medicine
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail(
            $id
        );


        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (
            $medicine->image &&
            file_exists(
                public_path(
                    $medicine->image
                )
            )
        ) {

            @unlink(
                public_path(
                    $medicine->image
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $medicine->delete();


        return redirect()
            ->back()
            ->with(
                'success',
                'Medicine Deleted Successfully'
            );
    }
}