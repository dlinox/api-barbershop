<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barber_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id'); //  barbershop_branches
            $table->unsignedBigInteger('barber_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_token')->nullable();
            $table->enum('check_type', ['manual', 'qr'])->default('manual');
            $table->enum('status', ['present', 'absent', 'late', 'absent_justified', 'late_justified'])->default('present');
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('barber_id')->references('id')->on('profile_barbers')->restrictOnDelete();
            $table->foreign('branch_id')->references('id')->on('barbershop_branches')->restrictOnDelete();

            $table->unique(['barber_id', 'date']);
            $table->index(['barber_id', 'date']);
            $table->index('branch_id');
            $table->index('barber_id');
            $table->index('date');
            $table->index('check_type');
            $table->index('check_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barber_attendances');
    }
};
