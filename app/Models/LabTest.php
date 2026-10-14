<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabTest extends Model
{
    protected $table = 'lab_tests';
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
        'image',
        'diagnostic_category_id',
    ];

    public function diagnosticCategory(): BelongsTo
    {
        return $this->belongsTo(
            DiagnosticCategory::class,
            'diagnostic_category_id',
            'id'
        );
    }
}
