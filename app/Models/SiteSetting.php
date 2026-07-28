<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    //
    protected $fillable = [
        'logo',
        'site_logo',
        'favicon',
        'site_name',
        'description',
        'short_description',
        'admin_logo',
        'address',
        'email',
        'phone',
        'customer_care_no',
        'help_line_no',
        'gst',
        'image',
        'company',
        'site_url',
        'facebook',
        'linkedin',
        'youtube',
        'pinterest',
        'instagram',
        'twitter'
    ];
}
