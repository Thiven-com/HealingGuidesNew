<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeVisitService extends Model
{
    use HasFactory;

    protected $table = 'home_visit_services';

    protected $fillable = [
        'home_visit_service_categories_id',
        'name',
        'slug',
        'image',
        'price_per',
        'price',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(
            HomeVisitServiceCategory::class,
            'home_visit_service_categories_id', 'id'
        );
    }
}
