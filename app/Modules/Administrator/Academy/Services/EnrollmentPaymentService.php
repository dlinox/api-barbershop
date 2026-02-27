<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;
use Illuminate\Support\Facades\DB;

class EnrollmentPaymentService
{
    public function __construct(
        private EnrollmentPaymentRepository $enrollmentPaymentRepository
    ) {}

    public function dataTable($request)
    {
        return $this->enrollmentPaymentRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return DB::transaction(function () use ($data) {
            $payment = $this->enrollmentPaymentRepository->save($data);

            if (isset($data['details'])) {
                $payment->details()->delete();
                foreach ($data['details'] as $detail) {
                    $payment->details()->create([
                        'group_payment_plan_id' => $detail['group_payment_plan_id'],
                        'type' => $detail['type'],
                        'subtotal' => $detail['subtotal'],
                        'discount' => $detail['discount'],
                        'total' => $detail['total'],
                    ]);
                }
            }

            return $payment;
        });
    }

    public function delete(int $id)
    {
        return $this->enrollmentPaymentRepository->delete($id);
    }
}
