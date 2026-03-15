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
        Schema::create('profile_admin_infrastructures', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_admin_id');
            $table->unsignedBigInteger('core_infrastructure_id');
            
            $table->foreign('profile_admin_id')
                ->references('core_person_id')
                ->on('profile_admins')
                ->onDelete('cascade');
                
            $table->foreign('core_infrastructure_id')
                ->references('id')
                ->on('core_infrastructures')
                ->onDelete('cascade');

            $table->primary(['profile_admin_id', 'core_infrastructure_id'], 'admin_infra_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_admin_infrastructures');
    }
};
