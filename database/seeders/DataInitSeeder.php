<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Behavior\Role;

class DataInitSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
        ]);
        Role::firstOrCreate(
            ['name' => 'estudiante'],
            [
                'display_name' => 'Estudiante',
                'redirect_to' => '/admin',
                'level' => '3',
                'is_active' => true,
            ]
        );
    }
}
