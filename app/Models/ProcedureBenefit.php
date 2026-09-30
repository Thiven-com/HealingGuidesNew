<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcedureBenefit extends Model
{
    protected $fillable = [

        'procedure_id',

        'title',

        'description',

        'icon',

        'display_order',

        'status',

    ];


    protected $casts = [

        'status' => 'boolean',

    ];


    /*
    |--------------------------------------------------------------------------
    | Procedure
    |--------------------------------------------------------------------------
    */

    public function procedure()
    {
        return $this->belongsTo(
            Procedure::class,
            'procedure_id'
        );
    }
}