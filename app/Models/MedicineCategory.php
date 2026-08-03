<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    protected $fillable = [
        'category_name',
        'slug',
        'image',
        'description',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Medicines
    |--------------------------------------------------------------------------
    */

    public function medicines()
    {
        return $this->hasMany(
            Medicine::class,
            'medicine_category_id'
        );
    }
}