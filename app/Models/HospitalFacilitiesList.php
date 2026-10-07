<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HospitalFacilitiesList extends Model
{
    use HasFactory;

    protected $table = 'hospital_facilities_lists';

    protected $fillable = [
        'title',
        'image',
        'description',
        'hospital_id',
        'hospital_facilities_id',
        'offer_price',
        'actual_price',
    ];
}
