<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('check_token')->nullable();
            $table->enum('check_type', ['manual', 'qr'])->default('manual');
            $table->enum('status', ['present', 'absent', 'late', 'absent_justified', 'late_justified'])->default('present');
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('core_person_id')->on('profile_teachers')->restrictOnDelete();
            $table->foreign('group_id')->references('id')->on('academy_groups')->restrictOnDelete();

            $table->unique(['teacher_id', 'group_id', 'date']);
            $table->index(['teacher_id', 'date']);
            $table->index('teacher_id');
            $table->index('group_id');
            $table->index('date');
            $table->index('check_type');
            $table->index('check_token');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_teacher_attendances');
    }
};
