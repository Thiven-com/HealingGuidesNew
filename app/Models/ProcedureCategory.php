<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];
    public function category()
    {
        return $this->belongsTo(
            ProcedureCategory::class,
            'specialization_id',
            'id'
        );
    }
}
