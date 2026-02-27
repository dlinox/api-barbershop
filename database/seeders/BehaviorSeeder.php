<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Behavior\Role;

class BehaviorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedRoles();
    }

    private function seedRoles(): void
    {

        $roles = [
            [
                'id' => 1,
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'redirect_to' => '/admin',
                'level' => '0',
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'estudiante',
                'display_name' => 'Estudiante',
                'redirect_to' => '/admin',
                'level' => '3',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'administrador',
                'display_name' => 'Administrador',
                'redirect_to' => '/admin',
                'level' => '1',
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'docente',
                'display_name' => 'Docente',
                'redirect_to' => '/admin',
                'level' => '2',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
