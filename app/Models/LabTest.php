<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    //
    protected $fillable = [
        'test_name',
        'test_code',
        'slug',
        'description',
        'sample_type',
        'preparation',
        'report_time',
        'report_time_type',
        'fasting_required',
        'home_collection',
        'status',
    ];
}
