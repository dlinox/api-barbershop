<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasDataTable;

    protected $table = 'academy_groups';

    protected $fillable = [
        'branch_id',
        'level_id',
        'schedule_id',
        'room_id',
        'name',
        'start_date',
        'end_date',
        'enrollment_price',
        'monthly_price',
        'days_of_week',
        'attendance_tolerance_minutes',
        'status',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        // 'start_date' => 'date',
        // 'end_date' => 'date',
        'enrollment_price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'attendance_tolerance_minutes' => 'integer',
    ];

    protected static $searchColumns = [
        'academy_groups.name',
        'academy_levels.name',
        'academy_branches.name'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function groupTeachers(): HasMany
    {
        return $this->hasMany(GroupTeacher::class, 'group_id');
    }

    public function paymentPlans()
    {
        return $this->hasMany(GroupPaymentPlan::class, 'group_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'group_id');
    }

    public function attendanceDeadlines()
    {
        return $this->hasMany(AttendanceDeadline::class, 'group_id');
    }
}
