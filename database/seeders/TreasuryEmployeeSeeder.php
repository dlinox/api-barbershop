<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreasuryEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('💰 Configurando pagos de empleados...');
        $this->command->info('');

        // ─── TRABAJADORES ───
        $workers = DB::table('profile_workers')->where('is_active', true)->get();
        $this->command->info("👷 {$workers->count()} trabajadores encontrados");
        if ($workers->count() > 0) {
            $this->command->info("   📝 Puedes configurar su salario en profile_workers:");
            $this->command->info("      - monthly_salary: monto del salario mensual");
            $this->command->info("      - payment_frequency: 'monthly' o 'biweekly'");
        }
        $this->command->info('');

        // ─── BARBEROS ───
        $barbers = DB::table('profile_barbers')->where('is_active', true)->get();
        $this->command->info("✂️  {$barbers->count()} barberos encontrados");
        foreach ($barbers as $barber) {
            $this->command->info("   💰 Barber ID {$barber->id} → {$barber->commission_percentage}% comisión");
        }
        $this->command->info('');

        // ─── DOCENTES ───
        $teachers = DB::table('profile_teachers')->where('is_active', true)->get();
        $this->command->info("👨‍🏫 {$teachers->count()} docentes encontrados");
        if ($teachers->count() > 0) {
            $this->command->info("   📝 Tarifas definidas en academy_group_teachers:");
            $this->command->info("      - hourly_rate: tarifa por hora normal");
            $this->command->info("      - holiday_hourly_rate: tarifa por hora feriado");
        }
        $this->command->info('');

        $this->command->info('✅ CONFIGURACIÓN:');
        $this->command->info('');
        $this->command->info('📋 FUENTES DE DATOS:');
        $this->command->info('   👷 Workers → profile_workers (monthly_salary, payment_frequency)');
        $this->command->info('   ✂️  Barbers → profile_barbers (commission_percentage)');
        $this->command->info('   👨‍🏫 Teachers → academy_group_teachers (hourly_rate, holiday_hourly_rate)');
        $this->command->info('');
        $this->command->info('💸 PAGOS:');
        $this->command->info('   - treasury_employee_payments: registrar pagos realizados');
        $this->command->info('   - treasury_employee_advances: registrar adelantos de sueldo');
        $this->command->info('');
        $this->command->info('🧮 CÁLCULOS:');
        $this->command->info('   - Workers: monthly_salary × (días_trabajados/total_días)');
        $this->command->info('   - Barbers: SUM(barbershop_tickets.total) × commission_percentage');
        $this->command->info('   - Teachers: Σ(horas × hourly_rate) por grupo asignado');
    }
}