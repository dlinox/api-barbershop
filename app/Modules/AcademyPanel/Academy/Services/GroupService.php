<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Modules\AcademyPanel\Academy\Repositories\GroupRepository;

class GroupService
{
    public function __construct(private GroupRepository $groupRepository) {}

    public function dataTable($request) { return $this->groupRepository->dataTable($request); }
    public function save(array $data) { return $this->groupRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->groupRepository->delete($id); }
    public function assignTeacher(array $data) { return $this->groupRepository->assignTeacher($data); }
    public function checkTeacher(int $groupId, int $teacherId) { return $this->groupRepository->checkTeacher($groupId, $teacherId); }
    public function selectItems() { return $this->groupRepository->selectItems(); }
    public function getAvailableEnrollmentGroups(int $studentId) { return $this->groupRepository->getAvailableEnrollmentGroups($studentId); }
    public function getActiveAndUpcoming() { return $this->groupRepository->getActiveAndUpcoming(); }
}