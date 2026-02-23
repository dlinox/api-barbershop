<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataBUSeeder extends Seeder
{

    public function run(): void
    {
        $path = database_path('sql/bu_22_02_2026.sql');
        
        if (file_exists($path)) {
            DB::unprepared(file_get_contents($path));
            $this->command->info('Archivo SQL ejecutado correctamente.');
        } else {
            $this->command->error('No se encontró el archivo SQL: ' . $path);
        }
    }
}