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
        return $this->belongsToMany(
            Diagnostic::class,
            'diagnostic_center_category',
            'diagnostic_category_id',
            'diagnostic_id'
        )->withTimestamps();
    }
}