<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalGallery extends Model
{
    use HasFactory;

    protected $table = 'hospital_galleries';

    protected $fillable = [
        'hospital_id',
        'hospital',
        'facility_id',
        'file_type',
        'file_path',
        'hospital_facility_list_id',
    ];

    /**
     * Hospital relationship
     */
    public function hospitalRelation()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    /**
     * Facility relationship
     */
    public function facility()
    {
        return $this->belongsTo(HospitalFacility::class, 'facility_id');
    }

}
