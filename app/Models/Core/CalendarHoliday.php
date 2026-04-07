<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class CalendarHoliday extends Model
{
    protected $table = 'core_calendar_holidays';

    protected $fillable = [
        'date',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];
}
