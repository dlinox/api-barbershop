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

        $user = User::where('username', $username)->first();

        if (!$user) {
            $this->command->error("Usuario '{$username}' no encontrado.");
            return;
        }

        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            $this->command->error("Rol 'super_admin' no encontrado.");
            return;
        }

        $person = Person::where('document_number', $username)->first();

        if (!$person) {
            $this->command->error("Persona con document_number '{$username}' no encontrada.");
            return;
        }

        $admin = Admin::firstOrCreate(['core_person_id' => $person->id]);

        Profile::updateOrCreate(
            [
                'auth_user_id'     => $user->id,
                'profileable_type' => 'profile_admins',
                'profileable_id'   => $admin->core_person_id,
            ],
            [
                'behavior_role_id' => $role->id,
                'is_active'        => true,
            ]
        );

        $this->command->info("Rol 'super_admin' asignado al usuario '{$username}'.");
    }
}
