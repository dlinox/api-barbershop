<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Campos de comprobante en la orden ───
        Schema::table('inventory_purchase_orders', function (Blueprint $table) {
            $table->string('receipt_type', 50)->nullable()->after('order_number');
            $table->char('receipt_serie', 4)->nullable()->after('receipt_type');
            $table->unsignedInteger('receipt_number')->nullable()->after('receipt_serie');
        });

        // ─── Simplificar status ───
        DB::statement("ALTER TABLE inventory_purchase_orders MODIFY COLUMN status ENUM('pending', 'received', 'cancelled') DEFAULT 'pending'");

        // ─── Quitar product_id y quantity_received de los items ───
        Schema::table('inventory_purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex(['product_id']);
            $table->dropColumn(['product_id', 'quantity_received']);
        });
    }

    public function down(): void
    {
        Schema::table('inventory_purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['receipt_type', 'receipt_serie', 'receipt_number']);
        });

        DB::statement("ALTER TABLE inventory_purchase_orders MODIFY COLUMN status ENUM('draft', 'ordered', 'partial', 'received', 'cancelled') DEFAULT 'draft'");

        Schema::table('inventory_purchase_order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('purchase_order_id');
            $table->integer('quantity_received')->default(0)->after('quantity_ordered');

            $table->foreign('product_id')->references('id')->on('inventory_products')->restrictOnDelete();
            $table->index('product_id');
        });
    }
};
