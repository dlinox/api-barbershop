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
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->primary('core_person_id');
            $table->index('is_active');
        });

        Schema::create('profile_teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('core_person_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->primary('core_person_id');
            $table->index('is_active');
        });

        Schema::create('profile_students', function (Blueprint $table) {
            $table->unsignedBigInteger('core_person_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->primary('core_person_id');
            $table->index('is_active');
        });

        Schema::create('profile_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // core_person_id
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id')->references('id')->on('core_persons')->onDelete('restrict');
            $table->primary('id');
            $table->index('is_active');
        });

        Schema::create('profile_workers', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // core_person_id
            $table->string('position')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id')->references('id')->on('core_persons')->onDelete('restrict');
            $table->primary('id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_admins');
        Schema::dropIfExists('profile_teachers');
        Schema::dropIfExists('profile_students');
        Schema::dropIfExists('profile_clients');
        Schema::dropIfExists('profile_workers');
    }
};
