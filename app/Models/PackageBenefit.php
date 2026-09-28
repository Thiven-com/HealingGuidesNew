<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageBenefit extends Model
{
    protected $fillable = [
        'package_id',
        'benefit_type',
        'benefit_name',
        'quantity',
        'description',
        'display_order',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'display_order' => 'integer',
        'status' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(
            Package::class
        );
    }
}