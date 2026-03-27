<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageList extends Model
{
    use HasFactory;

    protected $fillable = [
        'package', 'price', 'description', 'merchant_company',
        'plan_label', 'speed', 'features', 'is_featured', 'show_on_site', 'sort_order',
    ];

    protected $casts = [
        'features'    => 'array',
        'is_featured' => 'boolean',
        'show_on_site' => 'boolean',
    ];
}
