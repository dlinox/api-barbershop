<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\ServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            DB::beginTransaction();
            $this->serviceRepository->createOrUpdate($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            DB::beginTransaction();
            $this->serviceRepository->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
