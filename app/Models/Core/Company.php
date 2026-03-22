<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'core_companies';

    protected $fillable = [
        'name',
        'trade_name',
        'ruc',
        'address',
        'phone',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
