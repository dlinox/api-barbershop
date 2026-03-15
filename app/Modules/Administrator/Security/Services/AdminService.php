<?php

namespace App\Modules\Administrator\Security\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Profile\Admin;
use App\Modules\Administrator\Security\Repositories\Actions\CreateOrUpdateAdminAction;
use App\Modules\Administrator\Security\Repositories\AdminRepository;
use Illuminate\Support\Facades\DB;

class AdminService
{
    public function __construct(
        private CreateOrUpdateAdminAction $createOrUpdateAdminAction,
        private AdminRepository $adminRepository,
    ) {}

    public function dataTable($request)
    {
        return $this->adminRepository->dataTable($request);
    }
    public function create(array $data): void
    {
        $this->createOrUpdateAdminAction->execute($data);
    }

    public function syncInfrastructures(array $data): void
    {
        try {
            DB::beginTransaction();

            $admin = Admin::findOrFail($data['admin_id']);
            $admin->infrastructures()->sync($data['infrastructure_ids']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }
}
