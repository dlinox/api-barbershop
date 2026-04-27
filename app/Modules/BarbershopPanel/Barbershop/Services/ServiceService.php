<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\ServiceRepository;

class ServiceService
{
    public function __construct(
        private ServiceRepository $serviceRepository
    ) {}

    public function dataTable($request)
    {
        return $this->serviceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->serviceRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->serviceRepository->delete($id);
    }

    public function getActiveServices()
    {
        return $this->serviceRepository->getActiveServices();
    }

    public function getActiveServicesByInfrastructure(int $infrastructureId)
    {
        return $this->serviceRepository->getActiveServicesByInfrastructure($infrastructureId);
    }
}
