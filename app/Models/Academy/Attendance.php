<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Common\Traits\HasDataTable;

class Attendance extends Model
{
    use HasDataTable;

    protected $table = 'academy_attendances';

    protected $fillable = [
        'enrollment_id',
        'attendance_deadline_id',
        'check_in',
        'check_out',
        'observation',
        'status',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'attendance_deadline_id' => 'integer',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'academy_groups.name',
        'academy_levels.name',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function attendanceDeadline(): BelongsTo
    {
        return $this->belongsTo(AttendanceDeadline::class, 'attendance_deadline_id');
    }
}
