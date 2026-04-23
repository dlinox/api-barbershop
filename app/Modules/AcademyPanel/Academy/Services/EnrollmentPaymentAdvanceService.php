<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\EnrollmentPaymentAdvanceRepository;
class EnrollmentPaymentAdvanceService {
    public function __construct(private EnrollmentPaymentAdvanceRepository $advanceRepository) {}
    public function getAvailableByStudentId(int $studentId) { return $this->advanceRepository->getAvailableByStudentId($studentId); }
    public function save(array $data) { return $this->advanceRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->advanceRepository->delete($id); }
}