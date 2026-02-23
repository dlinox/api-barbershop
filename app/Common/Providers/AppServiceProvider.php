<?php

namespace App\Common\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\Profile\Admin;
use App\Models\Academy\Branch;

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
            // 'profile_students' => Student::class,
            // 'profile_teachers' => Teacher::class,
            'academy_branches' => Branch::class,
        ]);
    }
}
