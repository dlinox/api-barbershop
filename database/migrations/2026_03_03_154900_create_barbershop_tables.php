<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('profile_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // core_person_id
            $table->timestamps();

            $table->foreign('id')->references('id')->on('core_persons')->onDelete('restrict');
            $table->primary('id');
        });

        Schema::create('profile_workers', function (Blueprint $table) {
            $table->unsignedBigInteger('id'); // core_person_id
            $table->enum('position', ['barber', 'administrative', 'cashier'])->default('barber');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('core_persons')->onDelete('restrict');
            $table->primary('id');
        });

        Schema::create('barbershop_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('ubication')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('barbershop_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->index('name');
        });

        Schema::create('barbershop_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('barbershop_categories')->onDelete('cascade');
            $table->index('name');
        });

        Schema::create('barbershop_service_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('service_id');

            $table->integer('price')->default(0);
            $table->integer('duration')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('barbershop_branches')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('barbershop_services')->onDelete('cascade');
            $table->unique(['branch_id', 'service_id'], 'branch_service_unique');
        });

        Schema::create('barbershop_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('profile_client_id');
            $table->unsignedBigInteger('service_branch_id')->nullable();
            $table->date('date');
            $table->time('time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('service_branch_id')->references('id')->on('barbershop_service_branches')->onDelete('cascade');
            $table->foreign('profile_client_id')->references('id')->on('profile_clients')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('barbershop_branches')->onDelete('cascade');
        });

        //ticket de atencion
        Schema::create('barbershop_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('cash_session_id')->nullable(); // sesión de caja
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('profile_worker_id')->nullable();
            $table->unsignedBigInteger('profile_client_id')->nullable();

            //el monto que se pago
            $table->decimal('amount', 12, 2)->default(0); //el monto que se pago
            $table->decimal('discount', 12, 2)->default(0); //descuento
            $table->decimal('total', 12, 2)->default(0); //total
            $table->timestamp('ticket_date')->default(now());

            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();

            $table->foreign('cash_session_id')->references('id')->on('treasury_cash_sessions')->restrictOnDelete();
            $table->foreign('reservation_id')->references('id')->on('barbershop_reservations')->onDelete('cascade');
            $table->foreign('profile_worker_id')->references('id')->on('profile_workers')->onDelete('cascade');
            $table->foreign('profile_client_id')->references('id')->on('profile_clients')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('barbershop_branches')->onDelete('cascade');

            $table->index('cash_session_id');
            $table->index('profile_worker_id');
            $table->index('profile_client_id');
            $table->index('status');
        });

        ////ticket de atencion detalle
        Schema::create('barbershop_ticket_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('service_branch_id');
            $table->integer('quantity')->default(1);
            $table->decimal('amount', 12, 2)->default(0); //el monto que se pago
            $table->decimal('discount', 12, 2)->default(0); //descuento
            $table->timestamps();

            $table->foreign('ticket_id')->references('id')->on('barbershop_tickets')->onDelete('cascade');
            $table->foreign('service_branch_id')->references('id')->on('barbershop_service_branches')->onDelete('cascade');
        });
    }

    public function down(): void
    {

        Schema::dropIfExists('barbershop_ticket_services');
        Schema::dropIfExists('barbershop_tickets');
        Schema::dropIfExists('barbershop_service_branches');
        Schema::dropIfExists('barbershop_services');
        Schema::dropIfExists('barbershop_categories');
        Schema::dropIfExists('barbershop_reservations');
        Schema::dropIfExists('barbershop_branches');
        Schema::dropIfExists('profile_workers');
        Schema::dropIfExists('profile_clients');
    }
};
