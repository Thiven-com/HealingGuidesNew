<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMemberHealthCheckup extends Model
{

    protected $fillable = [
        'family_member_id',
        'health_checkup_id',
        'report_value',
        'percentage',
        'status',
        'checked_at',
        'remarks',
    ];

    protected $casts = [
        'percentage' => 'integer',
        'checked_at' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Family Member
    |--------------------------------------------------------------------------
    */

    public function familyMember()
    {
        return $this->belongsTo(
            FamilyMember::class,
            'family_member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Health Checkup
    |--------------------------------------------------------------------------
    */

    public function healthCheckup()
    {
        return $this->belongsTo(
            HealthCheckup::class,
            'health_checkup_id'
        );
    }
}