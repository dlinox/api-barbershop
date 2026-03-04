<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\ReservationRepository;
use Illuminate\Http\Request;

class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->reservationRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->reservationRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->reservationRepository->delete($id);
    }
}
