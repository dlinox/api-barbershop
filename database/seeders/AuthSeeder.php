<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void {}

    private function seedSuperAdmin(): void
    {

        $superAdmin = \App\Models\Auth\User::create(
            [
                'id' => 1,
                'username' => 'linox',
                'password' => 'linox',
                'is_active' => true,
            ]
        );

        $admin = \App\Models\Profile\Admin::create(
            [
                'core_person_id' => 1,
            ]
        );

        \App\Models\Behavior\Profile::create(
            [
                'id' => 1,
                'auth_user_id' => $superAdmin->id,
                'profileable_type' => 'profile_admins',  // Nombre de tabla, no clase PHP
                'profileable_id' => $admin->core_person_id,
                'behavior_role_id' => 1,
                'is_active' => true,
            ]
        );
    }

    private function seedAdmin(): void
    {
        $admin = \App\Models\Auth\User::create(
            [
                'id' => 2,
                'username' => '00000001',
                'password' => 'admin',
                'is_active' => true,
            ]
        );

        $admin = \App\Models\Profile\Admin::create(
            [
                'core_person_id' => 2,
            ]
        );

        \App\Models\Behavior\Profile::create(
            [
                'id' => 2,
                'auth_user_id' => $admin->id,
                'profileable_type' => 'profile_admins',  // Nombre de tabla, no clase PHP
                'profileable_id' => $admin->core_person_id,
                'behavior_role_id' => 1,
                'is_active' => true,
            ]
        );
    }
}
