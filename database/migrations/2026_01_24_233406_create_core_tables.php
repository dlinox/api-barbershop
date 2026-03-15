<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('core_countries', function (Blueprint $table) {
            $table->char('code', 2)->unique();
            $table->string('name', 100)->unique();

            $table->primary('code');
            $table->index('name');
        });

        Schema::create('core_document_types', function (Blueprint $table) {
            $table->char('code', 2)->unique();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);

            $table->primary('code');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('core_genders', function (Blueprint $table) {
            $table->char('code', 1)->unique();
            $table->string('name', 100)->unique();

            $table->primary('code');
            $table->index('name');
        });

        Schema::create('core_cities', function (Blueprint $table) {
            $table->char('code', 6);
            $table->string('department', 100);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->char('country', 2)->default('PE');

            $table->primary('code');
            $table->index('department');
            $table->index('province');
            $table->index('district');
            $table->index('country');
        });

        Schema::create('core_persons', function (Blueprint $table) {
            $table->id();
            $table->char('document_type', 2);
            $table->string('document_number', 20);
            $table->string('name', 100);
            $table->string('paternal_surname', 80)->nullable();
            $table->string('maternal_surname', 80)->nullable();
            $table->date('date_birth')->nullable();
            $table->string('phone', 15)->nullable()->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->char('gender', 1)->nullable();
            $table->string('address', 255)->nullable();
            $table->char('city', 6)->nullable();
            $table->char('country', 2)->nullable();

            $table->timestamps();

            $table->foreign('document_type')->references('code')->on('core_document_types')->onDelete('restrict');
            $table->foreign('gender')->references('code')->on('core_genders')->nullOnDelete();
            $table->foreign('country')->references('code')->on('core_countries')->nullOnDelete();
            $table->foreign('city')->references('code')->on('core_cities')->nullOnDelete();

            $table->unique(['document_type', 'document_number']);
            $table->index('name');
            $table->index('paternal_surname');
            $table->index('maternal_surname');
            $table->index('gender');
            $table->index('phone');
            $table->index('email');
            $table->index(['name', 'paternal_surname', 'maternal_surname']);
        });

        Schema::create('core_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['cash', 'bank'])->default('cash');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('name');
        });


        // ─── INFRAESTRUCTURAS (polimórfica: sedes de academia, barbería, oficina, etc.) ───
        Schema::create('core_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('infrastructurable_type'); // 'academy_branches', 'barbershop_branches', etc.
            $table->unsignedBigInteger('infrastructurable_id');
            $table->timestamps();

            $table->unique(['infrastructurable_type', 'infrastructurable_id'], 'core_infra_type_id_unique');
            $table->index('infrastructurable_type');
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('core_payment_methods');
        Schema::dropIfExists('core_infrastructures');
        Schema::dropIfExists('core_persons');
        Schema::dropIfExists('core_cities');
        Schema::dropIfExists('core_genders');
        Schema::dropIfExists('core_document_types');
        Schema::dropIfExists('core_countries');
    }
};
