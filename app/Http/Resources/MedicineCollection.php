<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MedicineCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'medicines' => $this->collection->map(function ($medicine) {

                return [

                    'id' => $medicine->id,

                    'medicine_name' =>
                        $medicine->medicine_name,

                    'medicine_code' =>
                        $medicine->medicine_code,

                    'slug' =>
                        $medicine->slug,

                    /*
                    |--------------------------------------------------------------------------
                    | Category
                    |--------------------------------------------------------------------------
                    */

                    'category' => $medicine->category ? [

                        'id' =>
                            $medicine->category->id,

                        'category_name' =>
                            $medicine->category->category_name,

                        'image' =>
                            $medicine->category->image,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Hospital
                    |--------------------------------------------------------------------------
                    */

                    'hospital' => $medicine->hospital ? [

                        'id' =>
                            $medicine->hospital->id,

                        'hospital_name' =>
                            $medicine->hospital->hospital_name,

                        'logo' =>
                            $medicine->hospital->logo,

                        'address' =>
                            $medicine->hospital->address,

                        'city' =>
                            $medicine->hospital->city,

                        'state' =>
                            $medicine->hospital->state,

                        'pincode' =>
                            $medicine->hospital->pincode,

                    ] : null,

                    /*
                    |--------------------------------------------------------------------------
                    | Medicine
                    |--------------------------------------------------------------------------
                    */

                    'generic_name' =>
                        $medicine->generic_name,

                    'brand_name' =>
                        $medicine->brand_name,

                    'manufacturer' =>
                        $medicine->manufacturer,

                    'medicine_type' =>
                        $medicine->medicine_type,

                    'strength' =>
                        $medicine->strength,

                    'pack_size' =>
                        $medicine->pack_size,

                    'image' =>
                        $medicine->image,

                    'description' =>
                        $medicine->description,

                    'composition' =>
                        $medicine->composition,

                    'usage_instructions' =>
                        $medicine->usage_instructions,

                    'side_effects' =>
                        $medicine->side_effects,

                    'storage_instructions' =>
                        $medicine->storage_instructions,

                    /*
                    |--------------------------------------------------------------------------
                    | Price
                    |--------------------------------------------------------------------------
                    */

                    'mrp' =>
                        $medicine->mrp,

                    'selling_price' =>
                        $medicine->selling_price,

                    'discount' =>
                        $medicine->mrp > 0
                        ? round(
                            (($medicine->mrp - $medicine->selling_price)
                                / $medicine->mrp) * 100
                        )
                        : 0,

                    /*
                    |--------------------------------------------------------------------------
                    | Stock
                    |--------------------------------------------------------------------------
                    */

                    'stock_quantity' =>
                        $medicine->stock_quantity,

                    'in_stock' =>
                        $medicine->stock_quantity > 0,

                    /*
                    |--------------------------------------------------------------------------
                    | Prescription
                    |--------------------------------------------------------------------------
                    */

                    'prescription_required' =>
                        (bool) $medicine->prescription_required,

                ];
            }),
        ];
    }
}