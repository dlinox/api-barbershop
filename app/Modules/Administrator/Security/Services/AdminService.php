<?php

namespace App\Modules\Administrator\Security\Services;

use App\Modules\Administrator\Security\Repositories\Actions\CreateOrUpdateAdminAction;
use App\Modules\Administrator\Security\Repositories\AdminRepository;

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
}
