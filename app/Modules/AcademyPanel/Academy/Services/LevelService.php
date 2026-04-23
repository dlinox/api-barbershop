<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\LevelRepository;
class LevelService {
    public function __construct(private LevelRepository $levelRepository) {}
    public function dataTable($request) { return $this->levelRepository->dataTable($request); }
    public function save(array $data) { return $this->levelRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->levelRepository->delete($id); }
    public function getActiveLevels() { return $this->levelRepository->getActiveLevels(); }
}