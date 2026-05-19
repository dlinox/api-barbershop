<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Core\Company;

class AcademyStudentListByGroupQuery
{
    public function __invoke(int $groupId): array
    {
        $group = Group::with(['branch', 'level', 'schedule', 'room'])->findOrFail($groupId);

        $enrollments = Enrollment::where('group_id', $groupId)
            ->where('status', 'active')
            ->with(['student.person', 'student.guardians'])
            ->get()
            ->sortBy(fn ($e) => $e->student?->person?->paternal_surname . ' ' . $e->student?->person?->name);

        $students = [];
        foreach ($enrollments as $enrollment) {
            $person = $enrollment->student?->person;
            $guardians = $enrollment->student?->guardians ?? collect();
            $guardian = $guardians->first();

            $students[] = [
                'document_number'  => $person?->document_number ?? '---',
                'name'             => $person?->name ?? '---',
                'paternal_surname' => $person?->paternal_surname ?? '',
                'maternal_surname' => $person?->maternal_surname ?? '',
                'phone'            => $person?->phone ?? '---',
                'email'            => $person?->email ?? '---',
                'enrollment_date'  => $enrollment->date,
                'guardian_name'    => $guardian?->full_name ?? '---',
                'guardian_phone'   => $guardian?->phone ?? '---',
                'guardian_kinship' => $guardian?->kinship ?? '---',
            ];
        }

        return [
            'group'    => $group,
            'students' => $students,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $group = $queryData['group'];

        return [
            'company'         => Company::first(),
            'branch'          => $group->branch ?? null,
            'report_title'    => 'LISTA DE ALUMNOS POR GRUPO',
            'report_subtitle' => 'ESCUELA',
            'report_date'     => now()->format('d/m/Y'),
            'report_day'      => $group->name,
            'group_name'      => $group->name,
            'branch_name'     => $group->branch->name,
            'level_name'      => $group->level->name,
            'schedule_time'   => $group->schedule
                ? $group->schedule->start_time . ' - ' . $group->schedule->end_time
                : '---',
            'room_name'       => $group->room->name ?? '---',
            'students'        => $queryData['students'],
            'student_count'   => count($queryData['students']),
        ];
    }

    public function toReportData(array $queryData): array
    {
        $group = $queryData['group'];

        return [
            'group_id'      => $group->id,
            'group_name'    => $group->name,
            'branch_name'   => $group->branch->name,
            'student_count' => count($queryData['students']),
        ];
    }
}
