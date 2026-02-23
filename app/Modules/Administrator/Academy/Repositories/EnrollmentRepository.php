<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Enrollment;

class EnrollmentRepository
{

    protected function query()
    {
        return Enrollment::select(
            'academy_enrollments.id',
            'academy_enrollments.status',
            'academy_enrollments.created_at',

            'academy_enrollments.profile_student_id as student_id',
            'core_persons.name as student_person_name',
            'core_persons.paternal_surname as student_person_paternal_surname',
            'core_persons.maternal_surname as student_person_maternal_surname',
            'core_persons.phone as student_person_phone',

            'academy_enrollments.group_id',
            'academy_groups.name as group_name',
            'academy_groups.start_date as group_start_date',
            'academy_groups.end_date as group_end_date',
            'academy_groups.days_of_week as group_days_of_week',
            'academy_groups.is_active as group_is_active',

            'academy_groups.level_id as group_level_id',
            'academy_levels.name as group_level_name',

        )->join('core_persons', 'academy_enrollments.profile_student_id', '=', 'core_persons.id')
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id');
    }

    public function dataTable($request)
    {
        $query = $this->query();

        $items = $query->dataTable($request);

        return $items;
    }

    public function getEnrollment($id)
    {
        return $this->query()->where('academy_enrollments.id', $id)->first();
    }

    public function save($data)
    {
        $enrollment = $this->findByStudentIdAndGroupId($data['profile_student_id'], $data['group_id']);

        $data['id'] = $enrollment->id ?? null;

        $enrollment = Enrollment::updateOrCreate([
            'id' => $data['id'] ?? null,
        ], $data);
        return $enrollment;
    }

    public function findByStudentIdAndGroupId($studentId, $groupId)
    {
        return Enrollment::where('profile_student_id', $studentId)->where('group_id', $groupId)->first();
    }
}
