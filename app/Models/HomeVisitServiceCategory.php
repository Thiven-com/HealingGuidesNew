<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeVisitServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'home_visit_service_categories';

    protected $fillable = [
        'name',
        'home_visit_category_id',
        'slug',
        'image',
        'category_type',
    ];

    public function homeVisitCategory()
    {
        return $this->belongsTo(
            HomeVisitCategory::class,
            'home_visit_category_id'
        );
    }
}
