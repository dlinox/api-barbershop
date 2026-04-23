<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\BarberRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateBarberAction;

class BarberService
{
    public function __construct(
        private BarberRepository $barberRepository,
        private CreateOrUpdateBarberAction $createOrUpdateBarberAction,
    ) {}

    public function dataTable($request)
    {
        return $this->barberRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateBarberAction->execute($data);
    }

    public function selectAsyncItems($request)
    {
        return $this->barberRepository->selectAsyncItems($request->search);
    }
}