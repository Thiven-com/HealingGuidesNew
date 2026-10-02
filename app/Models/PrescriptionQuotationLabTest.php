<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionQuotationLabTest extends Model
{
    protected $fillable = [

        'prescription_quotation_id',

        'lab_test_id',

        'test_name',

        'test_code',

        'mrp',

        'price',

        'total',
    ];

    protected $casts = [

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

    public function labTest()
    {
        return $this->belongsTo(
            LabTest::class,
            'lab_test_id'
        );
    }
}