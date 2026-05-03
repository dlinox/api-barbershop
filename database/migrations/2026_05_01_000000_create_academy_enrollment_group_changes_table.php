<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_enrollment_group_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('origin_enrollment_id');
            $table->unsignedBigInteger('destination_enrollment_id');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('changed_by_user_id');
            $table->timestamp('changed_at');
            $table->timestamps();

            $table->foreign('origin_enrollment_id', 'aegc_origin_enrollment_fk')
                ->references('id')->on('academy_enrollments')
                ->onDelete('restrict');

            $table->foreign('destination_enrollment_id', 'aegc_destination_enrollment_fk')
                ->references('id')->on('academy_enrollments')
                ->onDelete('restrict');

            $table->foreign('changed_by_user_id', 'aegc_changed_by_user_fk')
                ->references('id')->on('auth_users')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_enrollment_group_changes');
    }
};
