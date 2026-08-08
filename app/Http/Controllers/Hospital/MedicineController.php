<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    /**
     * Get logged-in hospital
     */
    private function hospital()
    {
        return Auth::guard('hospital')->user();
    }


    /**
     * Medicine List
     */
    public function index(Request $request)
    {
        $hospital = $this->hospital();

        $query = Medicine::where(
            'hospital_id',
            $hospital->id
        );


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'medicine_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'medicine_code',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'generic_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'brand_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'manufacturer',
                        'like',
                        "%{$search}%"
                    );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Medicine Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('medicine_category_id')) {

            $query->where(
                'medicine_category_id',
                $request->medicine_category_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prescription Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('prescription_required')) {

            $query->where(
                'prescription_required',
                $request->prescription_required
            );
        }


        $medicines = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        return view(
            'hospital.medicines.index',
            compact('medicines')
        );
    }


    /**
     * Create Medicine
     */
    public function create()
    {
        return view(
            'hospital.medicines.create'
        );
    }


    /**
     * Store Medicine
     */
    public function store(Request $request)
    {
        $hospital = $this->hospital();


        $validated = $request->validate([

            'medicine_category_id' => [
                'nullable',
                'integer'
            ],

            'medicine_name' => [
                'required',
                'string',
                'max:255'
            ],

            'medicine_code' => [
                'nullable',
                'string',
                'max:100'
            ],

            'generic_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255'
            ],

            'medicine_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'strength' => [
                'nullable',
                'string',
                'max:100'
            ],

            'pack_size' => [
                'nullable',
                'string',
                'max:100'
            ],

            'image' => [
                'nullable',
                'string',
                'max:500'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'composition' => [
                'nullable',
                'string'
            ],

            'usage_instructions' => [
                'nullable',
                'string'
            ],

            'side_effects' => [
                'nullable',
                'string'
            ],

            'storage_instructions' => [
                'nullable',
                'string'
            ],

            'mrp' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0'
            ],

            'prescription_required' => [
                'nullable',
                'in:0,1'
            ],

            'status' => [
                'nullable',
                'in:0,1'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Hospital
        |--------------------------------------------------------------------------
        */

        $validated['hospital_id'] =
            $hospital->id;


        /*
        |--------------------------------------------------------------------------
        | Medicine Code
        |--------------------------------------------------------------------------
        */

        if (
            empty(
            $validated['medicine_code']
        )
        ) {

            do {

                $medicineCode =
                    'MED' .
                    strtoupper(
                        Str::random(8)
                    );

            } while (
                Medicine::where(
                    'medicine_code',
                    $medicineCode
                )->exists()
            );


            $validated['medicine_code'] =
                $medicineCode;
        }


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug(
            $validated['medicine_name']
        );


        $originalSlug = $slug;

        $counter = 1;


        while (
            Medicine::where(
                'slug',
                $slug
            )->exists()
        ) {

            $slug =
                $originalSlug .
                '-' .
                $counter++;

        }


        $validated['slug'] =
            $slug;


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
            $validated['prescription_required']
        )
        ) {

            $validated['prescription_required'] = 0;
        }


        if (
            !isset(
            $validated['status']
        )
        ) {

            $validated['status'] = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        Medicine::create(
            $validated
        );


        return redirect()
            ->route(
                'hospital.medicines.index'
            )
            ->with(
                'success',
                'Medicine added successfully.'
            );
    }


    /**
     * Show Medicine
     */
    public function show($id)
    {
        $hospital = $this->hospital();


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        return view(
            'hospital.medicines.show',
            compact('medicine')
        );
    }


    /**
     * Edit Medicine
     */
    public function edit($id)
    {
        $hospital = $this->hospital();


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        return view(
            'hospital.medicines.edit',
            compact('medicine')
        );
    }


    /**
     * Update Medicine
     */
    public function update(
        Request $request,
        $id
    ) {
        $hospital = $this->hospital();


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $validated = $request->validate([

            'medicine_category_id' => [
                'nullable',
                'integer'
            ],

            'medicine_name' => [
                'required',
                'string',
                'max:255'
            ],

            'medicine_code' => [
                'nullable',
                'string',
                'max:100'
            ],

            'generic_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'brand_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:255'
            ],

            'medicine_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'strength' => [
                'nullable',
                'string',
                'max:100'
            ],

            'pack_size' => [
                'nullable',
                'string',
                'max:100'
            ],

            'image' => [
                'nullable',
                'string',
                'max:500'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'composition' => [
                'nullable',
                'string'
            ],

            'usage_instructions' => [
                'nullable',
                'string'
            ],

            'side_effects' => [
                'nullable',
                'string'
            ],

            'storage_instructions' => [
                'nullable',
                'string'
            ],

            'mrp' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0'
            ],

            'prescription_required' => [
                'nullable',
                'in:0,1'
            ],

            'status' => [
                'nullable',
                'in:0,1'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        if (
            $medicine->medicine_name
            !== $validated['medicine_name']
        ) {

            $slug = Str::slug(
                $validated['medicine_name']
            );


            $originalSlug = $slug;

            $counter = 1;


            while (
                Medicine::where(
                    'slug',
                    $slug
                )
                    ->where(
                        'id',
                        '!=',
                        $medicine->id
                    )
                    ->exists()
            ) {

                $slug =
                    $originalSlug .
                    '-' .
                    $counter++;

            }


            $validated['slug'] =
                $slug;
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $medicine->update(
            $validated
        );


        return redirect()
            ->route(
                'hospital.medicines.show',
                $medicine->id
            )
            ->with(
                'success',
                'Medicine updated successfully.'
            );
    }


    /**
     * Delete Medicine
     */
    public function destroy($id)
    {
        $hospital = $this->hospital();


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $id
            )
            ->firstOrFail();


        $medicine->delete();


        return redirect()
            ->route(
                'hospital.medicines.index'
            )
            ->with(
                'success',
                'Medicine deleted successfully.'
            );
    }


    /**
     * Update Stock
     */
    public function updateStock(
        Request $request
    ) {
        $hospital = $this->hospital();


        $validated = $request->validate([

            'id' => [
                'required',
                'integer'
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0'
            ],

        ]);


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $validated['id']
            )
            ->firstOrFail();


        $medicine->update([

            'stock_quantity' =>
                $validated['stock_quantity']

        ]);


        return back()
            ->with(
                'success',
                'Medicine stock updated successfully.'
            );
    }


    /**
     * Update Status
     */
    public function updateStatus(
        Request $request
    ) {
        $hospital = $this->hospital();


        $validated = $request->validate([

            'id' => [
                'required',
                'integer'
            ],

            'status' => [
                'required',
                'in:0,1'
            ],

        ]);


        $medicine = Medicine::where(
            'hospital_id',
            $hospital->id
        )
            ->where(
                'id',
                $validated['id']
            )
            ->firstOrFail();


        $medicine->update([

            'status' =>
                $validated['status']

        ]);


        return back()
            ->with(
                'success',
                'Medicine status updated successfully.'
            );
    }
}