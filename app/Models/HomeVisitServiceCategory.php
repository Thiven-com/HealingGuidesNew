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
        'slug',
        'image',
        'category_type',
    ];
}
