<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\ScheduleRepository;
class ScheduleService {
    public function __construct(private ScheduleRepository $scheduleRepository) {}
    public function dataTable($request) { return $this->scheduleRepository->dataTable($request); }
    public function save(array $data) { return $this->scheduleRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->scheduleRepository->delete($id); }
    public function getActiveSchedules() { return $this->scheduleRepository->getActiveSchedules(); }
}