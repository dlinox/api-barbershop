<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\MaterialRepository;
class MaterialService {
    public function __construct(private MaterialRepository $materialRepository) {}
    public function dataTable($request) { return $this->materialRepository->dataTable($request); }
    public function save(array $data) { return $this->materialRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->materialRepository->delete($id); }
    public function getActiveMaterials() { return $this->materialRepository->getActiveMaterials(); }
}