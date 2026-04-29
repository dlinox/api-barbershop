<?php

namespace App\Console\Commands;

use App\Models\Academy\Group;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateGroupStatusCommand extends Command
{
    protected $signature = 'academy:update-group-status';

    protected $description = 'Actualiza automáticamente el estado de los grupos académicos según sus fechas de inicio y fin.';

    public function handle(): void
    {
        $today = Carbon::today();

        // Grupos que deberían pasar a "finished" (fin de fecha ya pasó y no están cancelados)
        $finished = Group::whereNotIn('status', ['cancelled', 'finished'])
            ->where('end_date', '<', $today)
            ->update(['status' => 'finished', 'is_active' => false]);

        // Grupos que deberían pasar a "active" (fecha de inicio ya llegó y no han terminado ni están cancelados)
        $activated = Group::where('status', 'coming')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->update(['status' => 'active', 'is_active' => true]);

        // Grupos que deberían pasar a "coming" (fecha de inicio aún no llega — caso de ajuste manual de fechas)
        $upcoming = Group::where('status', 'active')
            ->where('start_date', '>', $today)
            ->update(['status' => 'coming', 'is_active' => false]);

        $this->info("Estado de grupos actualizado:");
        $this->line("  → Finalizados:  {$finished}");
        $this->line("  → Activados:    {$activated}");
        $this->line("  → Próximos:     {$upcoming}");
    }
}
