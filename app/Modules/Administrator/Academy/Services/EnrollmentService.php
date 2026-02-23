<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\EnrollmentMaterial;
use Illuminate\Support\Facades\DB;
use App\Modules\Administrator\Academy\Repositories\EnrollmentRepository;
use App\Modules\Administrator\Academy\Repositories\EnrollmentPaymentRepository;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private EnrollmentPaymentRepository $enrollmentPaymentRepository,
    ) {}

    public function dataTable($request)
    {
        return $this->enrollmentRepository->dataTable($request);
    }

    public function save($data)
    {

        try {
            DB::beginTransaction();

            $enrollmentData = [
                'id' => $data['id'],
                'profile_student_id' => $data['student_id'],
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ];

            $enrollment = $this->enrollmentRepository->save($enrollmentData);

            if ($data['materials']) {
                EnrollmentMaterial::where('enrollment_id', $enrollment->id)->delete();
                foreach ($data['materials'] as $material) {
                    EnrollmentMaterial::create([
                        'enrollment_id' => $enrollment->id,
                        'material_id' => $material,
                    ]);
                }
            }

            foreach ($data['payments'] as $payment) {
                $enrollment->payments()->updateOrCreate(
                    [
                        'id' => $payment['id'],
                    ],
                    [
                        // 'enrollment_id' => $enrollment->id,
                        'group_payment_plan_id' => $payment['plan_id'],
                        'type' => $payment['type'],
                        'subtotal' => $payment['subtotal'],
                        'discount' => $payment['discount'],
                        'total' => $payment['total'],
                    ]
                );
            }

            DB::commit();

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function registerPayment($data)
    {
        try {
            DB::beginTransaction();

            foreach ($data['payments'] as $payment) {
                $this->enrollmentPaymentRepository->create(
                    [
                        'enrollment_id' => $data['enrollment_id'],
                        'group_payment_plan_id' => $payment['plan_id'],
                        'type'  => $payment['type'],
                        'subtotal' => $payment['subtotal'],
                        'discount' => $payment['discount'],
                        'total' => $payment['total'],
                    ]
                );
            }

            DB::commit();

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getEnrollment($id)
    {
        return $this->enrollmentRepository->getEnrollment($id);
    }
}
