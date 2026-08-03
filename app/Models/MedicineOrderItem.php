<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineOrderItem extends Model
{
    protected $fillable = [

        'medicine_order_id',

        'medicine_id',

        'medicine_name',

        'medicine_code',

        'strength',

        'pack_size',

        'quantity',

        'mrp',

        'price',

        'total',
    ];

    protected $casts = [

        'quantity' => 'integer',

        'mrp' => 'decimal:2',

        'price' => 'decimal:2',

        'total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(
            MedicineOrder::class,
            'medicine_order_id'
        );
    }

    public function medicine()
    {
        return $this->belongsTo(
            Medicine::class,
            'medicine_id'
        );
    }
}