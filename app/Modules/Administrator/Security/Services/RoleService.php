<?php

namespace App\Modules\Administrator\Security\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Common\Exceptions\ApiException;

use App\Models\Behavior\Role;
use App\Modules\Administrator\Security\Repositories\RoleRepository;

class RoleService
{
    public function __construct(
        private readonly RoleRepository $roleRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->roleRepository->dataTable($request);
    }

    public function createOrUpdate(array $data): Role
    {

        $id = $data['id'];
        $name = Str::slug($data['display_name']);
        $nameExists = $this->roleRepository->nameExists($name, $id);

        if ($nameExists) throw new ApiException('El nombre del rol ya existe', 422);

        $data['name'] = $name;

        try {
            DB::beginTransaction();
            $role = $this->roleRepository->createOrUpdate($data);
            // $this->roleRepository->assignPermissions($role, $data['permissions']);
            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }

    public function getRolesForAdmins()
    {
        return $this->roleRepository->getRolesForAdmins();
    }

    public function getAllPermissions(string $level)
    {
        return $this->roleRepository->getAllPermissions($level);
    }

    public function syncPermissions(array $data): void
    {
        try {
            DB::beginTransaction();
            $role = Role::findOrFail($data['roleId']);
            $this->roleRepository->assignPermissions($role, $data['permissions']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }
}
