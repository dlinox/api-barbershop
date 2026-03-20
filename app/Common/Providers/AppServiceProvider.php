<?php

namespace App\Common\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Models\Profile\Admin;
use App\Models\Profile\Worker;
use App\Models\Profile\Barber;
use App\Models\Profile\Teacher;
use App\Models\Academy\Branch;
use App\Models\Barbershop\Branch as BarbershopBranch;
use App\Models\Treasury\CashRegister;
use App\Models\Treasury\CashSession;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureMorphMap();
    }

    /**
     * Morph map for polymorphic relationships (table names for portability)
     */
    private function configureMorphMap(): void
    {
        Relation::enforceMorphMap([
            'profile_admins' => Admin::class,
            'profile_workers' => Worker::class,
            'profile_barbers' => Barber::class,
            // 'profile_students' => Student::class,
            'profile_teachers' => Teacher::class,
            'academy_branches' => Branch::class,
            'barbershop_branches' => BarbershopBranch::class,
            'treasury_cash_registers' => CashRegister::class,
            'treasury_cash_sessions' => CashSession::class,
        ]);
    }
}
