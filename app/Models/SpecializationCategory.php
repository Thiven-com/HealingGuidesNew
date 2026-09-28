<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecializationCategory extends Model
{
    use HasFactory;

    protected $table = 'specialization_categories';

    protected $fillable = [
        'category_name',
        'slug',
        'icon',
        'image',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get specializations under this category.
     */
    public function specializations()
    {
        return $this->hasMany(Specialization::class, 'specialization_category_id');
    }
}
