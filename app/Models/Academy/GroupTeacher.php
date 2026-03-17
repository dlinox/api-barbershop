<?php

namespace App\Models\Academy;

use App\Models\Profile\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupTeacher extends Model
{
    protected $table = 'academy_group_teachers';

    protected $fillable = [
        'group_id',
        'teacher_id',
        'hourly_rate',
        'holiday_hourly_rate',
        'status',
        'start_date',
        'end_date',
        'observation',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'holiday_hourly_rate' => 'decimal:2',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'core_person_id');
    }
}
