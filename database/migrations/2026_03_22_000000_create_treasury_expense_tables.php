<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── TIPOS DE GASTOS ───
        Schema::create('treasury_expense_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        // ─── GASTOS (unificado: gastos de caja + gastos generales) ───
        Schema::create('treasury_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_type_id');
            $table->unsignedBigInteger('infrastructure_id')->nullable();        // nullable para gastos generales de empresa
            $table->unsignedBigInteger('cash_session_id')->nullable();          // nullable para gastos fuera de caja
            $table->unsignedBigInteger('payment_method_id')->nullable();        // método de pago (efectivo, tarjeta, etc.)
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->date('transaction_date');

            // ─── COMPROBANTE (opcional) ───
            $table->date('voucher_date')->nullable();                                          // fecha del comprobante
            $table->string('voucher_number')->nullable();                                      // nro. de operación / secuencia
            $table->string('voucher_image_path')->nullable();                                 // ruta de la imagen del comprobante

            $table->enum('status', ['pending', 'approved', 'cancelled'])->default('approved');
            $table->timestamps();

            $table->foreign('expense_type_id')->references('id')->on('treasury_expense_types')->restrictOnDelete();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->nullOnDelete();
            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('core_payment_methods')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('auth_users')->restrictOnDelete();

            $table->index('expense_type_id');
            $table->index('infrastructure_id');
            $table->index('cash_session_id');
            $table->index('payment_method_id');
            $table->index('user_id');
            $table->index('transaction_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_expenses');
        Schema::dropIfExists('treasury_expense_types');
    }
};
