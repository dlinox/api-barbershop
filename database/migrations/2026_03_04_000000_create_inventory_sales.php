<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── VENTAS DE PRODUCTOS (cabecera) ───
        // Representa la venta de productos del inventario desde cualquier módulo
        // (barbería, academia, etc.). El comprobante financiero vive en treasury_incomes
        // apuntando a este registro via transactionable.
        //si existe no se crea
        Schema::hasTable('inventory_sales') ||
        Schema::create('inventory_sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('infrastructure_id');             // local/sucursal donde ocurrió la venta
            $table->unsignedBigInteger('cash_session_id')->nullable();   // sesión de caja activa (nullable para ventas fuera de caja)
            $table->unsignedBigInteger('person_id')->nullable();         // cliente (nullable para ventas rápidas sin identificar)
            $table->unsignedBigInteger('barbershop_ticket_id')->nullable(); // ticket de barbershop (si la venta se originó en ese módulo)

            // Origen de la venta (qué módulo generó la venta)
            $table->enum('context', ['barbershop', 'academy', 'other'])->default('other');

            // Montos (snapshot al momento de la venta)
            $table->decimal('subtotal', 12, 2)->default(0);             // suma de (qty × unit_price) de los ítems
            $table->decimal('discount', 12, 2)->default(0);             // descuento global de la venta
            $table->decimal('total', 12, 2)->default(0);                // subtotal - discount

            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');

            $table->unsignedBigInteger('user_id');                       // quién registró la venta
            $table->timestamps();

            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->restrictOnDelete();
            $table->foreign('person_id')->references('id')->on('core_persons')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('auth_users')->restrictOnDelete();
            $table->foreign('barbershop_ticket_id')->references('id')->on('barbershop_tickets')->nullOnDelete();

            $table->index('infrastructure_id');
            $table->index('cash_session_id');
            $table->index('person_id');
            $table->index('context');
            $table->index('status');
            $table->index('user_id');
        });

        // ─── DETALLE DE VENTAS (ítems vendidos) ───
        // Sirve para dos propósitos: (1) descuento de stock via kardex,
        // (2) referencia cruzada con treasury_income_details via itemable.
        Schema::hasTable('inventory_sale_items') ||
        Schema::create('inventory_sale_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('presentation_id');              // qué presentación de producto

            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);          // precio snapshot al momento de venta
            $table->decimal('discount', 10, 2)->default(0);            // descuento por línea
            $table->decimal('total', 10, 2)->default(0);               // (quantity × unit_price) - discount

            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('inventory_sales')->cascadeOnDelete();
            $table->foreign('presentation_id')->references('id')->on('inventory_product_presentations')->restrictOnDelete();

            $table->index('sale_id');
            $table->index('presentation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_sale_items');
        Schema::dropIfExists('inventory_sales');
    }
};
