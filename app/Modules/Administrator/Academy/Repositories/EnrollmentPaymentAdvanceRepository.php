<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\EnrollmentPaymentAdvance;

class EnrollmentPaymentAdvanceRepository
{
    public function getAvailableByStudentId(int $studentId)
    {
        return EnrollmentPaymentAdvance::where('student_id', $studentId)
            ->whereNull('used_at')
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data)
    {
        return EnrollmentPaymentAdvance::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'student_id' => $data['student_id'],
                'amount' => $data['amount'],
                'observation' => $data['observation'] ?? null,
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
            ]
        );
    }

    public function delete(int $id)
    {
        $advance = EnrollmentPaymentAdvance::findOrFail($id);

        if ($advance->used_at !== null) {
            throw new \Exception('No se puede eliminar un adelanto que ya fue utilizado');
        }

        return $advance->delete();
    }

    public function markAsUsed(array $advanceIds, int $enrollmentPaymentId)
    {
        return EnrollmentPaymentAdvance::whereIn('id', $advanceIds)
            ->whereNull('used_at')
            ->update([
                'enrollment_payment_id' => $enrollmentPaymentId,
                'used_at' => now()->toDateString(),
            ]);
    }
}
