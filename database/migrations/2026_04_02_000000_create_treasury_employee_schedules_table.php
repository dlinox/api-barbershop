<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treasury_employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['barber', 'worker']); 
            $table->time('start_time');                               // hora de entrada
            $table->time('end_time');                                 // hora de salida
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treasury_employee_schedules');
    }
};
