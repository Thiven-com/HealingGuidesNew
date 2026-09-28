<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tieup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tieup) {
            $tieup->slug = self::generateUniqueSlug($tieup->name);
        });

        static::updating(function ($tieup) {
            if ($tieup->isDirty('name')) {
                $tieup->slug = self::generateUniqueSlug(
                    $tieup->name,
                    $tieup->id
                );
            }
        });
    }

    private static function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);

        $originalSlug = $slug;
        $count = 1;

        while (
            self::where('slug', $slug)
                ->when($id, function ($query) use ($id) {
                    $query->where('id', '!=', $id);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'hospital_tieup');
    }
}
