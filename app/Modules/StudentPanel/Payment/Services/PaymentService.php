<?php

namespace App\Modules\StudentPanel\Payment\Services;

use App\Common\Helpers\FileHelper;
use App\Models\Treasury\Income;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateIncomePdfAction;
use App\Modules\StudentPanel\Payment\Repositories\PaymentRepository;
use App\Modules\StudentPanel\Shared\StudentContext;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $repository,
        private readonly GenerateIncomePdfAction $generateIncomePdfAction,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, StudentContext::studentId());
    }

    private const PDF_TYPE = 'payment_receipt';

    public function generatePdf(int $incomeId)
    {
        $income = Income::findOrFail($incomeId);

        // Verify this income belongs to the current student
        $studentId = StudentContext::studentId();
        if ($income->transactionable_type === 'academy_enrollment_payments') {
            $payment = \App\Models\Academy\EnrollmentPayment::find($income->transactionable_id);
            if ($payment) {
                $enrollment = $payment->enrollment;
                if (!$enrollment || $enrollment->profile_student_id !== $studentId) {
                    throw new \App\Common\Exceptions\ApiException('No autorizado', 403);
                }
            }
        }

        $existingFile = $income->files()
            ->where('type', self::PDF_TYPE)
            ->first();

        if (!$existingFile || !FileHelper::fileExists($existingFile->disk, $existingFile->path)) {
            $this->generateIncomePdfAction->execute($incomeId);
            $existingFile = $income->files()->where('type', self::PDF_TYPE)->first();
        }

        $content = file_get_contents(FileHelper::getFilePath($existingFile->disk, $existingFile->path));

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$existingFile->name}\"",
        ]);
    }
}
