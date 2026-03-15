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

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

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

        // Obtener todos los permisos solicitados por ID
        $requestedPermissions = Permission::whereIn('id', $permissions)
            ->where('level', $role->level)
            ->get();

        $allPermissionIds = [];

        // Función recursiva para obtener todos los padres
        $collectParentIds = function ($permission) use (&$collectParentIds, &$allPermissionIds) {
            if ($permission) {
                $allPermissionIds[] = $permission->id;
                // Cargar explícitamente el padre
                if ($permission->parent_id && !in_array($permission->parent_id, $allPermissionIds)) {
                    $parent = Permission::find($permission->parent_id);
                    $collectParentIds($parent);
                }
            }
        };

        foreach ($requestedPermissions as $permission) {
            $collectParentIds($permission);
        }

        // Eliminar valores duplicados
        $uniquePermissionIds = array_unique($allPermissionIds);

        //asignar los nuevos permisos
        foreach ($uniquePermissionIds as $permissionId) {
            RolePermission::create([
                'behavior_role_id' => $role->id,
                'behavior_permission_id' => $permissionId,
            ]);
        }
    }

    public function getRolesForAdmins()
    {
        return Role::where('level', '1')->where('is_active', true)->get();
    }

    public function getAllPermissions(string $level)
    {
        return Permission::with('children')
            ->where('level', $level)
            ->whereNull('parent_id')
            ->get();
    }
}
