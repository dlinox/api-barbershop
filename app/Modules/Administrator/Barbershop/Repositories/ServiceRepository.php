<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Barbershop\Service;
use App\Models\Barbershop\ServiceBranch;

class ServiceRepository
{
    public function dataTable($request)
    {
        $items = Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_services.description',
            'barbershop_services.category_id',
            'barbershop_categories.name as category_name',
            'barbershop_service_branches.price',
            'barbershop_service_branches.duration',
            'barbershop_service_branches.is_active',
        )
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->join('barbershop_service_branches', 'barbershop_services.id', '=', 'barbershop_service_branches.service_id')
            ->where('barbershop_service_branches.branch_id', $request->branchId);

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
            ]
        );

        ServiceBranch::updateOrCreate(
            [
                'branch_id' => $data['branch_id'],
                'service_id' => $service->id,
            ],
            [
                'price' => $data['price'],
                'duration' => $data['duration'],
                'is_active' => $data['is_active'],
            ]
        );

        return $service;
    }

    public function delete(int $id, int $branchId)
    {
        $serviceBranch = ServiceBranch::where('service_id', $id)
            ->where('branch_id', $branchId)
            ->first();

        if ($serviceBranch) {
            $serviceBranch->delete();
        }

        // Si el servicio ya no tiene sucursales, eliminarlo
        $remainingBranches = ServiceBranch::where('service_id', $id)->count();
        if ($remainingBranches === 0) {
            $service = Service::find($id);
            if ($service) {
                $service->delete();
            }
        }
    }

    public function getActiveServices()
    {
        return Service::all();
    }

    public function getActiveServicesByInfrastructure(int $infrastructureId)
    {
        return Service::select(
            'barbershop_services.id',
            'barbershop_services.name',
            'barbershop_services.description',

            'barbershop_services.category_id',
            'barbershop_categories.name as category_name',

            'barbershop_service_branches.id as service_branch_id',
            'barbershop_service_branches.price',
            'barbershop_service_branches.duration',
            'barbershop_service_branches.is_active',
        )
            ->join('barbershop_service_branches', 'barbershop_services.id', '=', 'barbershop_service_branches.service_id')
            ->join('barbershop_categories', 'barbershop_services.category_id', '=', 'barbershop_categories.id')
            ->join('core_infrastructures', function ($join) use ($infrastructureId) {
                $join->on('barbershop_service_branches.branch_id', '=', 'core_infrastructures.infrastructurable_id')
                    ->where('core_infrastructures.infrastructurable_type', 'barbershop_branches')
                    ->where('core_infrastructures.id', $infrastructureId);
            })
            ->where('barbershop_service_branches.is_active', true)
            ->get();
    }
}
