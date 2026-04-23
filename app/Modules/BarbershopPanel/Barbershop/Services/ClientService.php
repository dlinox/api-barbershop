<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\ClientRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateClientAction;

class ClientService
{
    public function __construct(
        private ClientRepository $clientRepository,
        private CreateOrUpdateClientAction $createOrUpdateClientAction,
    ) {}

    public function dataTable($request)
    {
        return $this->clientRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateClientAction->execute($data);
    }

    public function selectAsyncItems($request)
    {
        return $this->clientRepository->selectAsyncItems($request->search);
    }
}