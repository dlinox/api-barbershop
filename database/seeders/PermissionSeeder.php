<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Behavior\Permission;

class PermissionSeeder extends Seeder
{
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
                // Si retorna un solo nodo asociativo, envuélvelo en un array secuencial
                if (isset($permissions['name'])) {
                    $permissions = [$permissions];
                }
                $this->seedPermissions($permissions);
            }
        }
    }

    private function seedPermissions(array $permissions, $parentId = null, $parentLevel = null): void
    {
        // Verificar si se pasó un solo elemento asociativo en la recursividad, y envolver
        if (isset($permissions['name'])) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permissionData) {
            // Extraer los hijos si existen
            $children = $permissionData['children'] ?? [];
            unset($permissionData['children']);

            // Asignar el parent
            $permissionData['parent_id'] = $parentId;
            
            // Consideración requerida: heredar el level del padre (módulo)
            $currentLevel = $permissionData['level'] ?? $parentLevel ?? '1';
            $permissionData['level'] = $currentLevel;

            // Crear o actualizar el permiso
            $permission = Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );

            // Llamada recursiva para los hijos, enviando el nivel actual
            if (!empty($children)) {
                $this->seedPermissions($children, $permission->id, $currentLevel);
            }
        }
    }
}
