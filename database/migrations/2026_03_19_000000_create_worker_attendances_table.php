<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worker_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_token')->nullable();
            $table->enum('check_type', ['manual', 'qr'])->default('manual');
            $table->enum('status', ['present', 'absent', 'late', 'absent_justified', 'late_justified'])->default('present');
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('profile_workers')->restrictOnDelete();

            $table->unique(['worker_id', 'date']);
            $table->index(['worker_id', 'date']);
            $table->index('worker_id');
            $table->index('date');
            $table->index('check_type');
            $table->index('check_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worker_attendances');
    }
};
