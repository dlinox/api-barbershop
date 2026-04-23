<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_workers', function (Blueprint $table) {
            $table->unsignedBigInteger('infrastructure_id')->default(1)->after('id');
            $table->index('infrastructure_id');
        });
    }

    public function down(): void
    {
        Schema::table('profile_workers', function (Blueprint $table) {
            $table->dropIndex(['infrastructure_id']);
            $table->dropColumn('infrastructure_id');
        });
    }
};
