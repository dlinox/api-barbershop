<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Core\Country;
use App\Models\Core\DocumentType;
use App\Models\Core\Gender;
use App\Models\Core\Person;

use App\Models\Auth\User;
use App\Models\Behavior\Role;
use App\Models\Behavior\Profile;
use App\Models\Profile\Admin;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Datos maestros Core
        $this->seedCoreData();

        // 2. Rol Super Admin (sin permisos)
        $role = $this->seedSuperAdminRole();

        // 3. Persona del Super Admin
        $person = $this->seedSuperAdminPerson();

        // 4. Usuario de autenticación
        $user = $this->seedSuperAdminUser();

        // 5. Perfil Admin
        $admin = $this->seedAdminProfile($person);

        // 6. Vincular todo en behavior_profiles
        $this->seedBehaviorProfile($user, $admin, $role);

        // $this->command->info('✅ Super Admin creado exitosamente');

    }

    private function seedCoreData(): void
    {
        // País por defecto
        Country::firstOrCreate(
            ['code' => 'PE'],
            ['name' => 'Perú']
        );

        // Tipos de documento (SUNAT Catálogo 06)
        DocumentType::firstOrCreate(
            ['code' => '1'],
            ['name' => 'DNI']
        );
        DocumentType::firstOrCreate(
            ['code' => '4'],
            ['name' => 'Carnet de Extranjería']
        );
        DocumentType::firstOrCreate(
            ['code' => '6'],
            ['name' => 'RUC']
        );
        DocumentType::firstOrCreate(
            ['code' => '7'],
            ['name' => 'Pasaporte']
        );
        DocumentType::firstOrCreate(
            ['code' => '0'],
            ['name' => 'Otros']
        );

        // Géneros (ISO 5218)
        Gender::firstOrCreate(
            ['code' => '1'],
            ['name' => 'Masculino']
        );
        Gender::firstOrCreate(
            ['code' => '2'],
            ['name' => 'Femenino']
        );
        Gender::firstOrCreate(
            ['code' => '0'],
            ['name' => 'No conocido']
        );
        Gender::firstOrCreate(
            ['code' => '9'],
            ['name' => 'No aplicable']
        );
    }

    private function seedSuperAdminRole(): Role
    {
        return Role::firstOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'redirect_to' => '/admin',
                'level' => '0',  // Nivel más alto
                'is_active' => true,
            ]
        );
    }

    private function seedSuperAdminPerson(): Person
    {
        return Person::firstOrCreate(
            ['document_type' => '1', 'document_number' => '00000000'],
            [
                'name' => 'Lino',
                'paternal_surname' => 'Puma',
                'email' => 'super@admin.com',
                'country' => 'PE',
            ]
        );
    }

    private function seedSuperAdminUser(): User
    {
        return User::firstOrCreate(
            ['username' => 'linox'],
            [
                'email' => 'super@admin.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }

    private function seedAdminProfile(Person $person): Admin
    {
        return Admin::firstOrCreate(
            ['core_person_id' => $person->id]
        );
    }

    private function seedBehaviorProfile(User $user, Admin $admin, Role $role): Profile
    {
        return Profile::firstOrCreate(
            [
                'auth_user_id' => $user->id,
                'profileable_type' => 'profile_admins',  // Nombre de tabla, no clase PHP
                'profileable_id' => $admin->core_person_id,
            ],
            [
                'behavior_role_id' => $role->id,
                'is_active' => true,
            ]
        );
    }
}
