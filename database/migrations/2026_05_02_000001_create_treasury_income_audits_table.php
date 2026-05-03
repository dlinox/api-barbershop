<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Columna is_edited en treasury_incomes
        Schema::table('treasury_incomes', function (Blueprint $table) {
            $table->boolean('is_edited')->default(false)->after('status');
        });

        // Tabla de auditoría de ediciones
        Schema::create('treasury_income_audits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('income_id');
            $table->foreign('income_id')
                ->references('id')
                ->on('treasury_incomes')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('auth_users')
                ->restrictOnDelete();

            $table->string('edit_description');  // descripción obligatoria del motivo de edición

            // Snapshot antes de la edición
            $table->decimal('subtotal_before', 12, 2);
            $table->decimal('discount_before', 12, 2);
            $table->decimal('total_before', 12, 2);
            $table->json('details_snapshot');          // [{id, description, quantity, unit_price, discount, subtotal}]
            $table->json('payment_methods_snapshot');  // [{id, payment_method_id, amount, payment_reference}]

            $table->timestamps();

            $table->index('income_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_income_audits');

        Schema::table('treasury_incomes', function (Blueprint $table) {
            $table->dropColumn('is_edited');
        });
    }
};
