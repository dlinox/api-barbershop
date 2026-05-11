<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->nullable();
            $table->string('trade_name', 200)->nullable();
            $table->string('ruc', 20)->nullable()->unique();
            $table->string('address', 300)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_companies');
    }
};
