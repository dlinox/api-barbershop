<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('academy_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('address')->nullable();
            $table->string('ubication')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('is_active');
            $table->index('name');
        });


        Schema::create('academy_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->integer('number');
            $table->string('description')->nullable();
            $table->integer('capacity');
            $table->integer('floor');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('academy_branches')->cascadeOnDelete();
            // $table->unique(['branch_id', 'number', 'floor']);
            $table->index('is_active');
            $table->index('number');
            $table->index('floor');
        });

        Schema::create('academy_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('name')->unique();
            $table->string('description');
            $table->integer('duration_months')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('name');
        });


        Schema::create('academy_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('shift', ['morning', 'afternoon', 'night']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['shift', 'start_time', 'end_time']);

            $table->index('is_active');
            $table->index('shift');
            $table->index('start_time');
            $table->index('end_time');
        });

        //
        Schema::create('academy_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('room_id');

            $table->string('name');

            $table->date('start_date');
            $table->date('end_date');

            $table->decimal('enrollment_price', 10, 2);
            $table->decimal('monthly_price', 10, 2);

            $table->string('days_of_week'); //0: domingo, 1: lunes, 2: martes, 3: miercoles, 4: jueves, 5: viernes, 6: sabado ej. "1,2,3,4,5"

            //timpo de tolerancia en minutos
            $table->integer('attendance_tolerance_minutes')->default(0);

            $table->enum('status', ['active', 'coming', 'cancelled', 'finished'])->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('academy_branches')->restrictOnDelete();
            $table->foreign('level_id')->references('id')->on('academy_levels')->restrictOnDelete();
            $table->foreign('schedule_id')->references('id')->on('academy_schedules')->restrictOnDelete();
            $table->foreign('room_id')->references('id')->on('academy_rooms')->restrictOnDelete();

            $table->index('is_active');
            $table->index('status');
            $table->index('name');
        });

        Schema::create('profile_teachers', function (Blueprint $table) {
            $table->unsignedBigInteger('core_person_id');
            $table->unsignedBigInteger('branch_id')->nullable(); // academy_branches
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('core_person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('academy_branches')->nullOnDelete();
            $table->primary('core_person_id');
            $table->index('is_active');
        });

        Schema::create('academy_group_teachers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('teacher_id');

            $table->decimal('hourly_rate', 10, 2);
            $table->decimal('holiday_hourly_rate', 10, 2);

            $table->enum('status', ['active', 'withdrawn', 'replaced'])->default('active');

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('observation')->nullable();

            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('academy_groups')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('core_person_id')->on('profile_teachers')->restrictOnDelete();

            $table->index('group_id');
            $table->index('teacher_id');
            $table->index('status');
        });

        Schema::create('academy_group_payment_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->enum('type', ['enrollment', 'monthly']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('academy_groups')->restrictOnDelete();
            $table->unique(['type', 'group_id', 'start_date', 'end_date'], 'group_payment_plan_unique');
        });

        Schema::create('academy_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_student_id');
            $table->unsignedBigInteger('group_id');
            $table->date('date');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->timestamps();

            $table->foreign('profile_student_id')->references('core_person_id')->on('profile_students')->restrictOnDelete();
            $table->foreign('group_id')->references('id')->on('academy_groups')->restrictOnDelete();

            $table->unique(['profile_student_id', 'group_id']);
        });

        Schema::create('academy_enrollment_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->timestamps();
            $table->foreign('enrollment_id')->references('id')->on('academy_enrollments')->restrictOnDelete();
        });

        Schema::create('academy_enrollment_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_payment_id');
            $table->unsignedBigInteger('group_payment_plan_id');
            $table->enum('type', ['enrollment', 'monthly']);
            $table->decimal('subtotal', 10, 2); // 100
            $table->decimal('discount', 10, 2)->default(0); // 10
            $table->decimal('total', 10, 2); // 90
            $table->timestamps();
            $table->foreign('enrollment_payment_id')->references('id')->on('academy_enrollment_payments')->restrictOnDelete();
            $table->foreign('group_payment_plan_id')->references('id')->on('academy_group_payment_plans')->restrictOnDelete();
            $table->index('enrollment_payment_id');
            $table->index('type');
        });


        Schema::create('academy_enrollment_payment_advances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('enrollment_payment_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('observation')->nullable();
            $table->date('payment_date');
            $table->date('used_at')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('core_person_id')->on('profile_students')->restrictOnDelete();
            $table->foreign('enrollment_payment_id', 'adv_enrollment_payment_id_foreign')->references('id')->on('academy_enrollment_payments')->nullOnDelete();
        });

        Schema::create('academy_attendance_deadlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->date('date');
            $table->dateTime('check_in_deadline')->nullable(); //hora de finalizacion para el check in
            $table->dateTime('check_out_deadline')->nullable(); //hora de finalizacion para el check out
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('academy_groups')->restrictOnDelete();
            $table->unique(['group_id', 'date']);
            $table->index('date');
            $table->index('group_id');
        });

        Schema::create('academy_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('attendance_deadline_id');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'absent_justified', 'late_justified']);
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->foreign('enrollment_id')->references('id')->on('academy_enrollments')->restrictOnDelete();
            $table->foreign('attendance_deadline_id')->references('id')->on('academy_attendance_deadlines')->restrictOnDelete();
            $table->unique(['enrollment_id', 'attendance_deadline_id']);
            $table->index('enrollment_id');
            $table->index('attendance_deadline_id');
            $table->index('status');
        });

        // ─── MATERIALES ───
        Schema::create('academy_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presentation_id');
            $table->integer('quantity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
            $table->foreign('presentation_id')->references('id')->on('inventory_product_presentations')->restrictOnDelete();
            $table->foreign('branch_id')->references('id')->on('academy_branches')->restrictOnDelete();
            $table->index('presentation_id');
            $table->index('is_active');
            $table->index('branch_id');
        });

        // ─── MATERIALES POR MATRÍCULA ───
        Schema::create('academy_enrollment_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('material_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('academy_enrollments')->restrictOnDelete();
            $table->foreign('material_id')->references('id')->on('academy_materials')->restrictOnDelete();

            $table->unique(['enrollment_id', 'material_id']);
            $table->index('enrollment_id');
            $table->index('material_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_enrollment_materials');
        Schema::dropIfExists('academy_materials');
        Schema::dropIfExists('academy_attendances');
        Schema::dropIfExists('academy_attendance_deadlines');
        Schema::dropIfExists('academy_enrollment_payment_advances');
        Schema::dropIfExists('academy_enrollment_payment_details');
        Schema::dropIfExists('academy_enrollment_payments');
        Schema::dropIfExists('academy_enrollments');
        Schema::dropIfExists('academy_group_payment_plans');
        Schema::dropIfExists('academy_group_teachers');
        Schema::dropIfExists('academy_groups');
        Schema::dropIfExists('academy_schedules');
        Schema::dropIfExists('academy_rooms');
        Schema::dropIfExists('academy_levels');
        Schema::dropIfExists('academy_branches');
    }
};
