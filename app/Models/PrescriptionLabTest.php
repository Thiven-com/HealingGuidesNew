<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionLabTest extends Model
{
    protected $fillable = [

        'prescription_id',

        'lab_test_id',

        'instructions',

    ];

    public function prescription()
    {
        return $this->belongsTo(
            Prescription::class,
            'prescription_id'
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