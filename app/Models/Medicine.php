<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [

        'hospital_id',

        'medicine_category_id',

        'medicine_name',

        'medicine_code',

        'slug',

        'generic_name',

        'brand_name',

        'manufacturer',

        'medicine_type',

        'strength',

        'pack_size',

        'image',

        'description',

        'composition',

        'usage_instructions',

        'side_effects',

        'storage_instructions',

        'mrp',

        'selling_price',

        'stock_quantity',

        'prescription_required',

        'status',

    ];

    protected $casts = [

        'mrp' => 'decimal:2',

        'selling_price' => 'decimal:2',

        'stock_quantity' => 'integer',

        'prescription_required' => 'boolean',

        'status' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Hospital
    |--------------------------------------------------------------------------
    */

    public function hospital()
    {
        return $this->belongsTo(
            Hospital::class,
            'hospital_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            MedicineCategory::class,
            'medicine_category_id'
        );
    }
}