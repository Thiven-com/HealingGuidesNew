<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbulancePrice extends Model
{
    use HasFactory;

    protected $table = 'ambulance_prices';

    protected $fillable = [
        'ambulance_id',
        'trip_type',
        'max_distance_km',
        'amount',
        'status',
    ];

    protected $casts = [
        'max_distance_km' => 'decimal:2',
        'amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function ambulance()
    {
        return $this->belongsTo(
            Ambulance::class,
            'ambulance_id'
        );
    }
}