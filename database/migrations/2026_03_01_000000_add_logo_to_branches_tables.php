<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academy_branches', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('address');
        });

        Schema::table('barbershop_branches', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('academy_branches', function (Blueprint $table) {
            $table->dropColumn('logo');
        });

        Schema::table('barbershop_branches', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
