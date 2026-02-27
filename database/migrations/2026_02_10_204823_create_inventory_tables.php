<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        // ─── CATEGORÍAS (jerárquicas, para productos) ───
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->enum('type', ['product'])->default('product');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('inventory_categories')->nullOnDelete();

            $table->unique(['name', 'type', 'parent_id']);
            $table->index('parent_id');
            $table->index('type');
            $table->index('is_active');
        });

        // ─── MARCAS ───
        Schema::create('inventory_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('name');
        });

        // ─── PROVEEDORES ───
        Schema::create('inventory_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_name')->nullable(); // nombre de contacto principal
            $table->string('phone', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('name');
        });

        // ─── PRODUCTOS ───
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->boolean('is_for_sale')->default(false);       // se vende al cliente
            $table->boolean('is_for_internal')->default(true);    // uso interno en servicios
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('inventory_categories')->restrictOnDelete();
            $table->foreign('brand_id')->references('id')->on('inventory_brands')->nullOnDelete();

            $table->index('name');
            $table->index('category_id');
            $table->index('brand_id');
            $table->index('is_for_sale');
            $table->index('is_for_internal');
            $table->index('is_active');
        });

        // ─── PRESENTACIONES DE PRODUCTO ───
        Schema::create('inventory_product_presentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('sku', 50)->unique();                     // SKU por presentación
            $table->string('name');                                  // "20 ml", "50 ml", "Caja x12"
            $table->enum('unit_type', [
                'unit',    // unidad individual
                'box',     // caja
                'pack',    // paquete
                'bottle',  // botella
                'tube',    // tubo
                'blister', // blíster
                'bag',     // bolsa
                'display', // display / exhibidor
                'dozen',   // docena
            ])->default('unit');
            $table->integer('quantity')->default(1);                 // unidades base por presentación (caja x12 = 12)
            $table->string('barcode', 100)->nullable()->unique();
            $table->integer('min_stock')->default(0);                // stock mínimo por presentación
            $table->integer('max_stock')->default(0);                // stock máximo por presentación
            $table->decimal('cost_price', 10, 2)->default(0);        // precio de compra por esta presentación
            $table->decimal('sale_price', 10, 2)->default(0);        // precio de venta por esta presentación
            $table->boolean('is_default')->default(false);           // presentación predeterminada
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('inventory_products')->cascadeOnDelete();

            $table->unique(['product_id', 'name']);
            $table->index('product_id');
            $table->index('sku');
            $table->index('unit_type');
            $table->index('is_default');
            $table->index('is_active');
        });

        // ─── STOCK POR SUCURSAL ───
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('presentation_id');
            $table->unsignedBigInteger('infrastructure_id');
            $table->integer('current_stock')->default(0);
            $table->timestamp('last_movement_at')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('inventory_products')->restrictOnDelete();
            $table->foreign('presentation_id')->references('id')->on('inventory_product_presentations')->restrictOnDelete();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();

            $table->unique(['presentation_id', 'infrastructure_id']);
            $table->index('product_id');
            $table->index('presentation_id');
            $table->index('infrastructure_id');
            $table->index('current_stock');
        });

        // ─── KARDEX (movimientos + saldos + costos) ───
        Schema::create('inventory_kardex', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('presentation_id'); // presentación usada en el movimiento
            $table->unsignedBigInteger('infrastructure_id');

            $table->enum('movement_type', ['in', 'out', 'adjustment']);
            $table->enum('reason', [
                'purchase',      // compra a proveedor
                'sale',          // venta al cliente
                'service_use',   // uso en servicio
                'enrollment',    // entrega de productos por matrícula (academia)
                'waste',         // merma / desperdicio
                'return',        // devolución
                'transfer',      // transferencia entre sucursales
                'initial',       // inventario inicial
                'adjustment',    // ajuste manual
            ]);

            // Movimiento
            $table->integer('quantity');                              // cantidad del movimiento (+ o -)
            $table->decimal('unit_cost', 10, 2)->default(0);         // costo unitario del movimiento
            $table->decimal('total_cost', 10, 2)->default(0);        // quantity × unit_cost

            // Saldo acumulado
            $table->integer('balance_quantity')->default(0);         // stock acumulado tras el movimiento
            $table->decimal('balance_unit_cost', 10, 2)->default(0); // costo promedio ponderado
            $table->decimal('balance_total_cost', 10, 2)->default(0);// costo total acumulado

            // Referencia polimórfica (orden de compra, venta, cita, etc.)
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();

            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('inventory_products')->restrictOnDelete();
            $table->foreign('presentation_id')->references('id')->on('inventory_product_presentations')->restrictOnDelete();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('created_by')->references('id')->on('auth_users')->nullOnDelete();

            $table->index('product_id');
            $table->index('presentation_id');
            $table->index('infrastructure_id');
            $table->index('movement_type');
            $table->index('reason');
            $table->index('created_by');
            $table->index('created_at');
            $table->index(['presentation_id', 'infrastructure_id']);
            $table->index(['reference_id', 'reference_type']);
        });

        // ─── ÓRDENES DE COMPRA ───
        Schema::create('inventory_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('infrastructure_id');
            $table->string('order_number', 50)->unique();
            $table->enum('status', ['draft', 'ordered', 'partial', 'received', 'cancelled'])->default('draft');
            $table->date('order_date');
            $table->date('expected_date')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('inventory_suppliers')->restrictOnDelete();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->restrictOnDelete();
            $table->foreign('created_by')->references('id')->on('auth_users')->nullOnDelete();

            $table->index('supplier_id');
            $table->index('infrastructure_id');
            $table->index('status');
            $table->index('order_date');
            $table->index('created_by');
        });

        // ─── DETALLE DE ÓRDENES DE COMPRA ───
        Schema::create('inventory_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('presentation_id');           // en qué presentación se compra
            $table->integer('quantity_ordered');                      // cantidad de presentaciones pedidas
            $table->integer('quantity_received')->default(0);        // cantidad de presentaciones recibidas
            $table->decimal('unit_price', 10, 2);                    // precio por presentación
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('purchase_order_id')->references('id')->on('inventory_purchase_orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('inventory_products')->restrictOnDelete();
            $table->foreign('presentation_id')->references('id')->on('inventory_product_presentations')->restrictOnDelete();

            $table->index('purchase_order_id');
            $table->index('product_id');
            $table->index('presentation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_purchase_order_items');
        Schema::dropIfExists('inventory_purchase_orders');
        Schema::dropIfExists('inventory_kardex');
        Schema::dropIfExists('inventory_stocks');
        Schema::dropIfExists('inventory_product_presentations');
        Schema::dropIfExists('inventory_products');
        Schema::dropIfExists('inventory_suppliers');
        Schema::dropIfExists('inventory_brands');
        Schema::dropIfExists('inventory_categories');
    }
};
