<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Auth\User;
use App\Models\Behavior\Profile;
use App\Models\Behavior\Role;
use App\Models\Core\Person;
use App\Models\Profile\Admin;

class AssignSuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $username = '70498731';
        $roles    = ['super_admin', 'gerente'];

        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->command->error("Usuario '{$username}' no encontrado.");
            return;
        }

        $person = Person::where('document_number', $username)->first();

        if (!$person) {
            $this->command->error("Persona con document_number '{$username}' no encontrada.");
            return;
        }

        $admin = Admin::firstOrCreate(['core_person_id' => $person->id]);

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                $this->command->error("Rol '{$roleName}' no encontrado.");
                continue;
            }

            Profile::updateOrCreate(
                [
                    'auth_user_id'     => $user->id,
                    'profileable_type' => 'profile_admins',
                    'profileable_id'   => $admin->core_person_id,
                    'behavior_role_id' => $role->id,
                ],
                [
                    'is_active' => true,
                ]
            );

            $this->command->info("Rol '{$roleName}' asignado al usuario '{$username}'.");
        }
    }
}
