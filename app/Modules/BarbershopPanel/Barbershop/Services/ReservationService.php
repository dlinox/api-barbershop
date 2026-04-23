<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\ReservationRepository;

class ReservationService
{
    public function __construct(
        private ReservationRepository $reservationRepository
    ) {}

    public function dataTable($request)
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