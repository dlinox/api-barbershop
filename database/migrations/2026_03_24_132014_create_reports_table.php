<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference'); // ej: 'academy_income_per_day,sede_academica_id,2026-02-28'
            $table->unsignedInteger('version')->default(1);
            $table->enum('type', [
                'academy_income_per_day',
                'academy_attendance_by_group',
                'academy_student_list_by_group',
                'barbershop_income_per_day',
                'barbershop_barber_commissions',
                'barbershop_cash_session_summary',
                'treasury_income_per_day',
                'treasury_expense_per_day',
                'treasury_cash_session',
                'treasury_income_vs_expense',
                'treasury_pending_expenses',
                'inventory_kardex',
                'inventory_stock_by_product',
                'inventory_stock_by_infrastructure',
                'inventory_sales_per_day',
                'inventory_low_stock',
            ]);
            $table->json('data');
            $table->string('file_path');
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamps();

            $table->foreign('generated_by')->references('id')->on('auth_users')->onDelete('set null');
            //indexes
            $table->unique(['reference', 'version']);
            $table->index('reference');
            $table->index('type');
            $table->index('generated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
