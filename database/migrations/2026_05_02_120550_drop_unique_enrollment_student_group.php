<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The composite unique index (profile_student_id, group_id) is also used by MySQL
        // as the supporting index for the profile_student_id foreign key.
        // We must create individual indexes first, then drop the unique constraint.
        Schema::table('academy_enrollments', function (Blueprint $table) {
            $table->index('profile_student_id', 'enrollments_student_id_index');
            $table->index('group_id', 'enrollments_group_id_index');
            $table->dropUnique(['profile_student_id', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academy_enrollments', function (Blueprint $table) {
            $table->unique(['profile_student_id', 'group_id']);
            $table->dropIndex('enrollments_student_id_index');
            $table->dropIndex('enrollments_group_id_index');
        });
    }
};
