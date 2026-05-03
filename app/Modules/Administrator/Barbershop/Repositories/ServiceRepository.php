<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Common\Exceptions\ApiException;
use App\Models\Barbershop\Service;
use App\Common\Traits\HasInfrastructureScope;

class ServiceRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $items = Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_services.description',
            'barbershop_services.category_id',
            'barbershop_categories.name as category_name',
            'barbershop_branches.name as branch_name',
            'barbershop_services.price',
            'barbershop_services.duration',
            'barbershop_services.is_active',
        )
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->join('barbershop_branches', 'barbershop_services.branch_id', '=', 'barbershop_branches.id');

        $this->scopeByBranch($items, 'barbershop_services.branch_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('barbershop_services.id', 'desc');
        }

        $items = $items->dataTable($request);

        return $items;
    }

    public function createOrUpdate(array $data)
    {
        $service = Service::updateOrCreate(
            ['id' => $data['id']],
            [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'branch_id' => $data['branch_id'],
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'],
            ]
        );

        return $service;
    }

    public function delete(int $id)
    {
        $service = Service::find($id);
        if (!$service) {
            throw new ApiException('Servicio no encontrado');
        }
        $service->delete();
    }

    public function getActiveServices()
    {
        $query = Service::query();

        $this->scopeByBranch($query, 'barbershop_services.branch_id');

        return $query->get();
    }

    public function getActiveServicesByInfrastructure(int $infrastructureId)
    {
        $this->validateInfrastructureAccess($infrastructureId);

        return Service::select(
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
            ->join('core_infrastructures', function ($join) use ($infrastructureId) {
                $join->on('barbershop_services.branch_id', '=', 'core_infrastructures.infrastructurable_id')
                    ->where('core_infrastructures.infrastructurable_type', 'barbershop_branches')
                    ->where('core_infrastructures.id', $infrastructureId);
            })
            ->where('barbershop_services.is_active', true)
            ->get();
    }
}
