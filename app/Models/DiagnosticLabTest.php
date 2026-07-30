<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticLabTest extends Model
{
    //
    protected $fillable = [
        'diagnostic_id',
        'lab_test_id',
        'price',
        'offer_price',
        'report_time',
        'report_time_type',
        'home_collection',
        'status',
    ];

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
