<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_student_guardians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('full_name', 150);
            $table->string('kinship', 50);
            $table->string('phone', 15);
            $table->timestamps();

            $table->foreign('student_id')->references('core_person_id')->on('profile_students')->cascadeOnDelete();
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_student_guardians');
    }
};
