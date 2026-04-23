<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\EnrollmentPaymentRepository;
class EnrollmentPaymentService {
    public function __construct(private EnrollmentPaymentRepository $enrollmentPaymentRepository) {}
    public function dataTable($request) { return $this->enrollmentPaymentRepository->dataTable($request); }
    public function historyByEnrollmentId(int $enrollmentId) { return $this->enrollmentPaymentRepository->historyByEnrollmentId($enrollmentId); }
    public function save(array $data) { return $this->enrollmentPaymentRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->enrollmentPaymentRepository->delete($id); }
}