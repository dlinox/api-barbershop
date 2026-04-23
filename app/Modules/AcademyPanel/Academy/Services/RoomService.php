<?php
namespace App\Modules\AcademyPanel\Academy\Services;
use App\Modules\AcademyPanel\Academy\Repositories\RoomRepository;
class RoomService {
    public function __construct(private RoomRepository $roomRepository) {}
    public function dataTable($request) { return $this->roomRepository->dataTable($request); }
    public function save(array $data) { return $this->roomRepository->createOrUpdate($data); }
    public function delete(int $id) { return $this->roomRepository->delete($id); }
    public function getActiveRooms() { return $this->roomRepository->getActiveRooms(); }
}