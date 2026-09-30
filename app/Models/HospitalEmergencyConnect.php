<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HospitalEmergencyConnect extends Model
{
    use HasFactory;

    protected $table = 'hospital_emergency_connects';

    protected $fillable = [
        'hospital_id',
        'slug',
        'name',
        'image',
        'designation',
        'department',
        'whatsapp_number',
        'contact_number',
    ];

    /**
     * Hospital relationship
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
