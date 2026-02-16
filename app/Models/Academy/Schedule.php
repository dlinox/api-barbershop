<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasDataTable;
    protected $table = 'academy_schedules';

    protected $fillable = [
        'shift', // morning, afternoon, night
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'schedule_id');
    }
}
