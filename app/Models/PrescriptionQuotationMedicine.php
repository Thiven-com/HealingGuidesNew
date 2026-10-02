<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionQuotationMedicine extends Model
{
    protected $fillable = [

        'prescription_quotation_id',

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

    public function quotation()
    {
        return $this->belongsTo(
            PrescriptionQuotation::class,
            'prescription_quotation_id'
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