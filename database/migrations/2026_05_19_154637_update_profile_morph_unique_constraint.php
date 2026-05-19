<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('behavior_profiles', function (Blueprint $table) {
            $table->dropUnique('profile_morph_unique');
            $table->unique(['profileable_type', 'profileable_id', 'behavior_role_id'], 'profile_morph_role_unique');
        });
    }

    public function down(): void
    {
        Schema::table('behavior_profiles', function (Blueprint $table) {
            $table->dropUnique('profile_morph_role_unique');
            $table->unique(['profileable_type', 'profileable_id'], 'profile_morph_unique');
        });
    }
};
