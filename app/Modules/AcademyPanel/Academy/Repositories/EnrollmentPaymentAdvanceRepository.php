<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\EnrollmentPaymentAdvance;
use App\Models\Academy\Enrollment;

class EnrollmentPaymentAdvanceRepository
{
    public function getAvailableByStudentId(int $studentId)
    {
        return EnrollmentPaymentAdvance::where('profile_student_id', $studentId)
            ->where('is_available', true)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data) { return EnrollmentPaymentAdvance::updateOrCreate(['id' => $data['id'] ?? null], $data); }
    public function delete(int $id) { return EnrollmentPaymentAdvance::findOrFail($id)->delete(); }
}