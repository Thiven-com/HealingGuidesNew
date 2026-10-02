<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiagnosticCategory extends Model
{
    protected $fillable = [

        'name',

        'slug',

        'image',

        'short_description',

        'description',

        'display_order',

        'status',
    ];

    protected $casts = [

        'display_order' => 'integer',

        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {

            if (empty($category->slug)) {

                $category->slug = Str::slug(
                    $category->name
                );
            }
        });

        static::updating(function ($category) {

            if ($category->isDirty('name')) {

                $category->slug = Str::slug(
                    $category->name
                );
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Diagnostic Relationship
    |--------------------------------------------------------------------------
    */

    public function diagnostics()
    {
        return $this->hasMany(
            Diagnostic::class,
            'diagnostic_category_id'
        );
    }
}