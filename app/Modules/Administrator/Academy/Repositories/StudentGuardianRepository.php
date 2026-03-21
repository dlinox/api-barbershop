<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\StudentGuardian;

class StudentGuardianRepository
{
    public function findByStudentId(int $studentId)
    {
        return StudentGuardian::where('student_id', $studentId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data)
    {
        return StudentGuardian::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        return StudentGuardian::findOrFail($id)->delete();
    }
}
