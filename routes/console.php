<?php

use App\Console\Commands\UpdateGroupStatusCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Actualiza automáticamente el estado de los grupos académicos cada día a medianoche
Schedule::command(UpdateGroupStatusCommand::class)->dailyAt('00:00');
