<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Academy\Branch;
use App\Models\Academy\Room;
use App\Models\Academy\Level;
use App\Models\Academy\Schedule;

class DataDemoSeeder extends Seeder
{

    public function run(): void
    {
        //branch
        $branch = Branch::firstOrCreate(
            ['name' => 'Sede Principal'],
            [
                'address' => 'Av. Siempre Viva 123',
                'ubication' => 'Centro',
                'is_active' => true,
            ]
        );

        //room
        Room::firstOrCreate(
            ['number' => '101', 'branch_id' => $branch->id],
            [
                'capacity' => 20,
                'floor' => 1,
                'is_active' => true,
            ]
        );

        //level
        Level::firstOrCreate(
            ['name' => 'Básico 1'],
            [
                'description' => 'Nivel inicial',
                'duration_months' => 1,
                'order' => 1,
                'is_active' => true,
            ]
        );

        //schedule
        Schedule::firstOrCreate(
            ['start_time' => '08:00', 'end_time' => '10:00'],
            [
                'shift' => 'morning',
                'is_active' => true,
            ]
        );
    }
}
