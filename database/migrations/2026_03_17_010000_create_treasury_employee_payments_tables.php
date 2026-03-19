<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('treasury_employee_payments', function (Blueprint $table) {
            $table->id();

            // Empleado polimórfico (worker, barber, teacher)
            $table->string('employee_type');                                      // 'worker', 'barber', 'teacher'
            $table->unsignedBigInteger('employee_id');                            // ID del profile correspondiente

            $table->unsignedBigInteger('infrastructure_id')->nullable();                      // sucursal
            $table->unsignedBigInteger('cash_session_id')->nullable();            // sesión de caja (si es efectivo)
            $table->unsignedBigInteger('payment_method_id');                      // método de pago
            $table->unsignedBigInteger('paid_by');                               // usuario que pagó

            // Período del pago
            $table->string('period');                                            // "Marzo 2026"
            $table->date('period_start');                                        // inicio del período
            $table->date('period_end');                                          // fin del período

            // Montos
            $table->decimal('base_amount', 10, 2)->default(0);                   // monto base calculado
            $table->decimal('bonus', 10, 2)->default(0);                         // bonos
            $table->decimal('deductions', 10, 2)->default(0);                    // descuentos
            $table->decimal('total_amount', 10, 2);                              // total neto pagado

            // Detalles del cálculo en JSON
            $table->json('calculation_details')->nullable();

            $table->date('payment_date');
            $table->string('payment_reference', 100)->nullable();                // referencia bancaria
            $table->enum('status', ['paid', 'cancelled'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('core_payment_methods')->restrictOnDelete();
            $table->foreign('paid_by')->references('id')->on('auth_users')->restrictOnDelete();

            // Indexes
            $table->index(['employee_type', 'employee_id'], 'employee_index');
            $table->index('infrastructure_id');
            $table->index('period_start');
            $table->index('period_end');
            $table->index('payment_date');
            $table->index('status');

            // No duplicar pagos del mismo período para el mismo empleado
            $table->unique(['employee_type', 'employee_id', 'period_start', 'period_end'], 'unique_employee_payment');
        });

        // ─── ADELANTOS DE SUELDO (polimórfico) ───
        Schema::create('treasury_employee_advances', function (Blueprint $table) {
            $table->id();

            // Empleado polimórfico (worker, barber, teacher)
            $table->string('employee_type');                                     // 'worker', 'barber', 'teacher'
            $table->unsignedBigInteger('employee_id');                           // ID del profile correspondiente

            $table->unsignedBigInteger('infrastructure_id')->nullable();                     // sucursal
            $table->unsignedBigInteger('cash_session_id')->nullable();           // sesión de caja
            $table->unsignedBigInteger('payment_method_id');                     // método de pago
            $table->unsignedBigInteger('authorized_by');                         // quien autorizó
            $table->unsignedBigInteger('paid_by');                              // quien pagó

            $table->decimal('amount', 10, 2);                                    // monto del adelanto
            $table->date('advance_date');                                        // fecha del adelanto
            $table->string('payment_reference', 100)->nullable();

            // Control del descuento
            $table->unsignedBigInteger('discounted_in_payment_id')->nullable();  // pago donde se descontó
            $table->enum('status', ['pending', 'discounted'])->default('pending');

            $table->text('reason')->nullable();                                  // razón del adelanto
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('core_payment_methods')->restrictOnDelete();
            $table->foreign('authorized_by')->references('id')->on('auth_users')->restrictOnDelete();
            $table->foreign('paid_by')->references('id')->on('auth_users')->restrictOnDelete();
            $table->foreign('discounted_in_payment_id')->references('id')->on('treasury_employee_payments')->nullOnDelete();

            // Indexes
            $table->index(['employee_type', 'employee_id'], 'employee_index');
            $table->index('advance_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_employee_advances');
        Schema::dropIfExists('treasury_employee_payments');
    }
};
