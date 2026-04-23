<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\EnrollmentPaymentAdvance;
use App\Models\Academy\Enrollment;

class EnrollmentPaymentAdvanceRepository
{
    public function getAvailableByStudentId(int $studentId)
    {
        return EnrollmentPaymentAdvance::where('student_id', $studentId)
            ->whereNull('used_at')
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data) { return EnrollmentPaymentAdvance::updateOrCreate(['id' => $data['id'] ?? null], $data); }
    public function delete(int $id) { return EnrollmentPaymentAdvance::findOrFail($id)->delete(); }
}