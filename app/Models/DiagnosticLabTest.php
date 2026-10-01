<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticLabTest extends Model
{
    //
    protected $table = 'diagnostic_lab_tests';
    protected $fillable = [
        'diagnostic_id',
        'lab_test_id',
        'price',
        'offer_price',
        'report_time',
        'report_time_type',
        'home_collection',
        'status',
        'free_ambulances',
    ];

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class, 'diagnostic_id');
    }

    public function labTest()
    {
        return $this->belongsTo(
            LabTest::class,
            'lab_test_id'
        );
    }
}
