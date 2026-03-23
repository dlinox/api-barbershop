<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        // ─── CAJAS REGISTRADORAS (por local/sucursal) ───
        Schema::create('treasury_cash_registers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('infrastructure_id');
            $table->string('name');                                          // "Caja 1", "Caja Principal"
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();

            $table->index('infrastructure_id');
            $table->index('is_active');
        });

        // ─── SESIONES DE CAJA (apertura/cierre/arqueo) ───
        Schema::create('treasury_cash_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id');
            $table->unsignedBigInteger('opened_by');
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->decimal('opening_amount', 12, 2)->default(0);            // monto inicial al abrir
            $table->decimal('expected_closing_amount', 12, 2)->default(0);   // calculado: apertura + ingresos - egresos en efectivo
            $table->decimal('actual_closing_amount', 12, 2)->nullable();     // lo que realmente había al cerrar
            $table->decimal('difference', 12, 2)->nullable();                // actual - expected (sobrante/faltante)
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('cash_register_id')->references('id')->on('treasury_cash_registers')->restrictOnDelete();
            $table->foreign('opened_by')->references('id')->on('auth_users')->restrictOnDelete();
            $table->foreign('closed_by')->references('id')->on('auth_users')->nullOnDelete();

            $table->index('cash_register_id');
            $table->index('opened_by');
            $table->index('closed_by');
            $table->index('status');
            $table->index('opened_at');
            $table->index('closed_at');
        });

        // ─── INGRESOS (cabecera: comprobante de cobro/venta) ───
        Schema::create('treasury_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_session_id')->nullable();       // nullable para cobros fuera de caja

            $table->unsignedBigInteger('infrastructure_id');

            // Comprobante
            //00 : interno (recibo interno), 01 : factura, 03 : boleta, etc. (ver catálogo SUNAT)
            $table->enum('receipt_type', ['00'])->default('00');    // '00' = recibo interno. Escalable a 'facturas', 'boletas', etc.
            $table->char('receipt_serie', 4)->default('R001');      // serie del comprobante (R001, F001, B001, etc.)
            $table->integer('receipt_number');      // nro. comprobante interno generado

            // Cliente (nullable para ventas rápidas sin identificar)
            $table->unsignedBigInteger('person_id')->nullable();

            // Referencia polimórfica al origen (enrollment_payment, futuro sale, etc.)
            $table->string('transactionable_type')->nullable();
            $table->unsignedBigInteger('transactionable_id')->nullable();

            // Montos
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->string('observations')->nullable();

            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->date('transaction_date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->restrictOnDelete();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('person_id')->references('id')->on('core_persons')->nullOnDelete();

            $table->foreign('user_id')->references('id')->on('auth_users')->restrictOnDelete();

            $table->index('cash_session_id');
            $table->index('infrastructure_id');
            $table->index('person_id');
            $table->index('status');
            $table->index('transaction_date');
            $table->index('user_id');
            $table->index(['transactionable_type', 'transactionable_id']);
            $table->unique(['receipt_serie', 'receipt_number']);
        });

        // ─── DETALLE DE INGRESOS (líneas del comprobante) ───
        Schema::create('treasury_income_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id');

            // Referencia polimórfica al ítem (producto/presentación, servicio, matrícula, etc.)
            $table->string('itemable_type')->nullable();
            $table->unsignedBigInteger('itemable_id')->nullable();

            $table->string('description');                                   // "Corte de cabello", "Gel 500ml", "Matrícula Grupo A"
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);                              // (quantity × unit_price) - discount
            $table->timestamps();

            $table->foreign('income_id')->references('id')->on('treasury_incomes')->cascadeOnDelete();

            $table->index('income_id');
            $table->index(['itemable_type', 'itemable_id']);
        });

        Schema::create('treasury_income_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id');
            $table->unsignedBigInteger('payment_method_id'); //ej: efectivo, tarjeta, transferencia, yape, plin, etc.
            $table->decimal('amount', 12, 2);
            $table->string('payment_reference', 100)->nullable(); // nro. transacción, referencia de pago, etc.
            $table->timestamps();

            $table->foreign('income_id')->references('id')->on('treasury_incomes')->cascadeOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('core_payment_methods')->restrictOnDelete();

            $table->index('income_id');
            $table->index('payment_method_id');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_income_payment_methods');
        Schema::dropIfExists('treasury_income_details');
        Schema::dropIfExists('treasury_incomes');
        Schema::dropIfExists('treasury_cash_sessions');
        Schema::dropIfExists('treasury_cash_registers');
    }
};
