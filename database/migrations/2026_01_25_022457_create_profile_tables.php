<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('profile_admins', function (Blueprint $table) {
            $table->unsignedBigInteger('core_person_id');
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->primary('core_person_id');
        });

        Schema::create('profile_students', function (Blueprint $table) {
            $table->unsignedBigInteger('core_person_id');
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->primary('core_person_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_admins');
    }
};
