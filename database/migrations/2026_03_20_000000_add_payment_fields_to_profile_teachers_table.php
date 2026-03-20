<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_teachers', function (Blueprint $table) {
            $table->enum('payment_type', ['hourly', 'monthly'])->nullable()->after('branch_id');
            $table->decimal('monthly_salary', 10, 2)->nullable()->after('payment_type');
            $table->index('payment_type', 'profile_teachers_payment_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('profile_teachers', function (Blueprint $table) {
            $table->dropIndex('profile_teachers_payment_type_index');
            $table->dropColumn(['payment_type', 'monthly_salary']);
        });
    }
};
