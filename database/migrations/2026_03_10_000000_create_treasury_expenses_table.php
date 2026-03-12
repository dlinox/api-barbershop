<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('treasury_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_session_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('auth_users')->restrictOnDelete();

            $table->index('cash_session_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_expenses');
    }
};
