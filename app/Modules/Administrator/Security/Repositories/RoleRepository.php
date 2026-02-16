<?php

namespace App\Modules\Administrator\Security\Repositories;

use App\Models\Behavior\Permission;
use App\Models\Behavior\Role;
use App\Models\Behavior\RolePermission;
use Illuminate\Http\Request;

class RoleRepository
{

    public function dataTable(Request $request)
    {
        $query = Role::select()->where('level', '=', '1');
        return $query->dataTable($request);
    }

    public function nameExists(string $name, ?int $id = null): bool
    {
        return Role::where('name', $name)->where('id', '!=', $id)->exists();
    }

    public function createOrUpdate(array $data): Role
    {
        $role = Role::updateOrCreate(
            ['id' => $data['id']],
            [
                'name' => $data['name'],
                'display_name' => $data['display_name'],
                'redirect_to' => '/admin', //temp default
                'level' => '1', //temp default
                'is_active' => $data['is_active'],
            ]
        );

        return $role;
    }

    public function assignPermissions(Role $role, array $permissions): void
    {

        //eliminar todos los permisos del rol
        RolePermission::where('behavior_role_id', $role->id)->delete();

        //validar que los permisos existan y sean del mismo nivel
        $newPermissions = Permission::whereIn('id', $permissions)->where('level', $role->level)->get();

        //asignar los nuevos permisos
        foreach ($newPermissions as $permission) {
            RolePermission::create([
                'behavior_role_id' => $role->id,
                'behavior_permission_id' => $permission->id,
            ]);
        }
    }

    public function getRolesForAdmins()
    {
        return Role::where('level', '1')->where('is_active', true)->get();
    }
}
