<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVisitCategory extends Model
{
    protected $table = 'home_visit_categories';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    public function serviceCategories()
    {
        return $this->hasMany(
            HomeVisitServiceCategory::class,
            'home_visit_category_id'
        );
    }
}
