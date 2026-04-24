<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Behavior\Permission;

class PermissionSeeder extends Seeder
{
    private array $seededNames = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = config_path('permissions');

        if (!File::exists($path)) {
            $this->command->warn("El directorio $path no existe.");
            return;
        }

        $files = File::allFiles($path);

        foreach ($files as $file) {
            $permissions = require $file->getPathname();

            if (is_array($permissions)) {
                if (isset($permissions['name'])) {
                    $permissions = [$permissions];
                }
                $this->seedPermissions($permissions);
            }
        }

        $this->deleteStalePermissions();
    }

    private function seedPermissions(array $permissions, $parentId = null, $parentLevel = null): void
    {
        if (isset($permissions['name'])) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permissionData) {
            $children = $permissionData['children'] ?? [];
            unset($permissionData['children']);

            $permissionData['parent_id'] = $parentId;

            $currentLevel = $permissionData['level'] ?? $parentLevel ?? '1';
            $permissionData['level'] = $currentLevel;

            $permission = Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );

            $this->seededNames[] = $permissionData['name'];

            if (!empty($children)) {
                $this->seedPermissions($children, $permission->id, $currentLevel);
            }
        }
    }

    private function deleteStalePermissions(): void
    {
        $stale = Permission::whereNotIn('name', $this->seededNames)->get();

        if ($stale->isEmpty()) {
            return;
        }

        $staleIds = $stale->pluck('id')->toArray();

        // Remove role associations before deleting
        \DB::table('behavior_role_permissions')->whereIn('behavior_permission_id', $staleIds)->delete();

        Permission::whereIn('id', $staleIds)->delete();

        $this->command->info("Eliminados {$stale->count()} permisos obsoletos.");
    }
}
