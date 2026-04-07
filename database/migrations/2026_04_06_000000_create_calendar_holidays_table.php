<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_calendar_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('date');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_calendar_holidays');
    }
};
