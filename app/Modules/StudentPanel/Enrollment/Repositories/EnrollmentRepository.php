<?php

namespace App\Modules\StudentPanel\Enrollment\Repositories;

use App\Common\Enums\DayOfWeek;
use App\Models\Academy\Enrollment;
use Illuminate\Support\Collection;

class EnrollmentRepository
{
    public function list(int $studentId): Collection
    {
        return Enrollment::with([
            'group.level',
            'group.schedule',
            'group.room',
            'group.branch',
            'group.groupTeachers' => fn($q) => $q->where('status', 'active'),
            'group.groupTeachers.teacher.person:id,name,paternal_surname',
        ])
            ->where('profile_student_id', $studentId)
            ->orderByDesc('date')
            ->get();
    }
}
