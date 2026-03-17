<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use App\Models\Profile\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    use HasDataTable;

    protected $table = 'academy_teacher_attendances';

    protected $fillable = [
        'teacher_id',
        'group_id',
        'date',
        'check_in',
        'check_out',
        'check_token',
        'check_type',
        'status',
        'observation',
    ];

    protected $casts = [
        'teacher_id' => 'integer',
        'group_id' => 'integer',
        'date' => 'date',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'academy_groups.name',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'core_person_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
