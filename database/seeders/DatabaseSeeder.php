<?php

namespace Database\Seeders;

use App\Models\Behavior\Role;
use Illuminate\Database\Seeder;
use App\Models\Academy\Branch;
use App\Models\Academy\Room;
use App\Models\Academy\Level;
use App\Models\Academy\Schedule;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            // DataInitSeeder::class,
            DataBUSeeder::class,
        ]);
    }
}
