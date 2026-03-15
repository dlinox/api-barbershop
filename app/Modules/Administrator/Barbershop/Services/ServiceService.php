<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\ServiceRepository;
use Illuminate\Http\Request;

class ServiceService
{
    public function __construct(
        private ServiceRepository $serviceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->serviceRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->serviceRepository->createOrUpdate($data);
    }

    public function delete(int $id, int $branchId)
    {
        return $this->serviceRepository->delete($id, $branchId);
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
