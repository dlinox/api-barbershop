<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasDataTable;

    protected $table = 'treasury_employee_schedules';

    protected $fillable = [
        'type',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
