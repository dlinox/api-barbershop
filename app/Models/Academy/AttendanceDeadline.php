<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Common\Traits\HasDataTable;

class AttendanceDeadline extends Model
{
    use HasDataTable;

    protected $table = 'academy_attendance_deadlines';

    protected $fillable = [
        'group_id',
        'date',
        'check_in_deadline',
        'check_out_deadline',
    ];

    protected $casts = [
        'group_id' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'attendance_deadline_id');
    }
}
