<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Elimina el perfil docente huérfano de RONALDINO CALCINA (person_id=42)
 * que estaba incorrectamente vinculado al usuario de KATHERINE SAMANEZ (user_id=42).
 *
 * Ejecución: php artisan db:seed --class=FixOrphanTeacherProfileSeeder
 */
class FixOrphanTeacherProfileSeeder extends Seeder
{
    // IDs esperados — cambiar SOLO si se verificó manualmente que son distintos
    private const PERSON_ID          = 42;
    private const USER_ID            = 42;
    private const BEHAVIOR_PROFILE_ID = 42;
    private const KATHERINE_PERSON_ID = 110;
    private const KATHERINE_USER_ID   = 42;

    public function run(): void
    {
        $this->command->info('=== FixOrphanTeacherProfileSeeder ===');

        // ── 1. VERIFICACIONES PREVIAS ──────────────────────────────────────────

        // 1a. La persona a eliminar existe y tiene el documento esperado
        $person = DB::table('core_persons')->where('id', self::PERSON_ID)->first();
        abort_if(!$person, 1, "ABORT: core_persons id=" . self::PERSON_ID . " no encontrada.");
        abort_if(
            $person->document_number === '70498731',
            1,
            "ABORT: core_persons id=" . self::PERSON_ID . " tiene document_number=70498731, " .
            "eso es KATHERINE, no RONALDINO. Revisar los IDs."
        );
        $this->command->line("  ✓ Persona a eliminar: [{$person->id}] {$person->name} {$person->paternal_surname} (doc={$person->document_number})");

        // 1b. El behavior_profile huérfano existe con los valores esperados
        $bp = DB::table('behavior_profiles')->where('id', self::BEHAVIOR_PROFILE_ID)->first();
        abort_if(!$bp, 1, "ABORT: behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID . " no encontrado.");
        abort_if(
            $bp->auth_user_id !== self::USER_ID,
            1,
            "ABORT: behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID .
            " tiene auth_user_id={$bp->auth_user_id}, esperado " . self::USER_ID
        );
        abort_if(
            $bp->profileable_type !== 'profile_teachers',
            1,
            "ABORT: behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID .
            " tipo inesperado: {$bp->profileable_type}"
        );
        abort_if(
            $bp->profileable_id !== self::PERSON_ID,
            1,
            "ABORT: behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID .
            " profileable_id={$bp->profileable_id}, esperado " . self::PERSON_ID
        );
        $this->command->line("  ✓ behavior_profile a eliminar: [id={$bp->id}] type={$bp->profileable_type} user_id={$bp->auth_user_id}");

        // 1c. El perfil de docente existe para esa persona
        $teacher = DB::table('profile_teachers')->where('core_person_id', self::PERSON_ID)->first();
        abort_if(!$teacher, 1, "ABORT: profile_teachers core_person_id=" . self::PERSON_ID . " no encontrado.");
        $this->command->line("  ✓ profile_teachers core_person_id=" . self::PERSON_ID . " encontrado.");

        // 1d. No tiene grupos, pagos ni adelantos relacionados
        $groupCount = DB::table('academy_group_teachers')->where('teacher_id', self::PERSON_ID)->count();
        abort_if($groupCount > 0, 1, "ABORT: Tiene {$groupCount} registros en academy_group_teachers. Revisión manual necesaria.");

        $paymentCount = DB::table('treasury_employee_payments')->where('employee_id', self::PERSON_ID)->count();
        abort_if($paymentCount > 0, 1, "ABORT: Tiene {$paymentCount} registros en treasury_employee_payments. Revisión manual necesaria.");

        $advanceCount = DB::table('treasury_employee_advances')->where('employee_id', self::PERSON_ID)->count();
        abort_if($advanceCount > 0, 1, "ABORT: Tiene {$advanceCount} registros en treasury_employee_advances. Revisión manual necesaria.");

        $this->command->line("  ✓ Sin relaciones en grupos, pagos ni adelantos.");

        // 1e. El user_id seguirá existiendo vinculado a KATHERINE (no lo eliminamos)
        $katherineProfile = DB::table('behavior_profiles')
            ->where('auth_user_id', self::KATHERINE_USER_ID)
            ->where('profileable_id', self::KATHERINE_PERSON_ID)
            ->where('profileable_type', 'profile_admins')
            ->first();
        abort_if(!$katherine = DB::table('core_persons')->where('id', self::KATHERINE_PERSON_ID)->first(),
            1, "ABORT: Persona KATHERINE (id=" . self::KATHERINE_PERSON_ID . ") no encontrada.");
        abort_if(!$katherineProfile, 1, "ABORT: Perfil admin de KATHERINE no encontrado. Revisar antes de continuar.");
        $this->command->line("  ✓ Perfil de KATHERINE intacto: [{$katherine->id}] {$katherine->name} {$katherine->paternal_surname}");

        // ── 2. CONFIRMACIÓN ────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->warn("  Se eliminarán:");
        $this->command->line("    - behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID);
        $this->command->line("    - profile_teachers  core_person_id=" . self::PERSON_ID);
        $this->command->line("    - core_persons      id=" . self::PERSON_ID . " ({$person->name} {$person->paternal_surname})");
        $this->command->line("  El usuario id=" . self::USER_ID . " (username=70498731) NO se toca — pertenece a KATHERINE.");
        $this->command->newLine();

        if (!$this->command->confirm('¿Confirmas la eliminación?', false)) {
            $this->command->warn('Operación cancelada.');
            return;
        }

        // ── 3. ELIMINACIÓN EN TRANSACCIÓN ──────────────────────────────────────
        DB::transaction(function () {
            // Orden: primero el perfil de comportamiento (FK → auth_users y profileable)
            DB::table('behavior_profiles')->where('id', self::BEHAVIOR_PROFILE_ID)->delete();
            $this->command->line("  [OK] behavior_profiles id=" . self::BEHAVIOR_PROFILE_ID . " eliminado.");

            // Luego el perfil de docente
            DB::table('profile_teachers')->where('core_person_id', self::PERSON_ID)->delete();
            $this->command->line("  [OK] profile_teachers core_person_id=" . self::PERSON_ID . " eliminado.");

            // Finalmente la persona
            DB::table('core_persons')->where('id', self::PERSON_ID)->delete();
            $this->command->line("  [OK] core_persons id=" . self::PERSON_ID . " eliminado.");
        });

        $this->command->newLine();
        $this->command->info('✅ Limpieza completada exitosamente.');
    }
}
