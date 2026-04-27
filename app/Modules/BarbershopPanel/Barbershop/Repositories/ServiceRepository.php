<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Barbershop\Service;
use App\Common\Http\Context\AdminContext;
use App\Common\Exceptions\ApiException;

class ServiceRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $items = Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_services.description',
            'barbershop_services.category_id',
            'barbershop_categories.name as category_name',
            'barbershop_services.price',
            'barbershop_services.duration',
            'barbershop_services.is_active',
        )
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->where('barbershop_services.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('barbershop_services.id', 'desc');
        }

        return $items->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $data['branch_id'] = AdminContext::barbershopBranchId();

        return Service::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'branch_id'   => $data['branch_id'],
                'price'       => $data['price'],
                'duration'    => $data['duration'],
                'is_active'   => $data['is_active'],
            ]
        );
    }

    public function delete(int $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
    }

    public function getActiveServices()
    {
        $branchId = AdminContext::barbershopBranchId();
        return Service::where('branch_id', $branchId)->where('is_active', true)->get();
    }

    public function getActiveServicesByInfrastructure(int $infrastructureId)
    {
        if ($infrastructureId !== AdminContext::infrastructureId()) {
            throw new ApiException('La sede solicitada no corresponde a la sede seleccionada', 403);
        }

        $branchId = AdminContext::barbershopBranchId();

        return Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_categories.name as category_name',
            'barbershop_services.price',
            'barbershop_services.duration',
        )
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->where('barbershop_services.branch_id', $branchId)
            ->where('barbershop_services.is_active', true)
            ->orderBy('barbershop_services.name')
            ->get();
    }
}
