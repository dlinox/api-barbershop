<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── ARCHIVOS (polimórfica: certificados, constancias, documentos, etc.) ───
        Schema::create('core_files', function (Blueprint $table) {
            $table->id();
            $table->string('fileable_type');
            $table->unsignedBigInteger('fileable_id');
            $table->string('type', 80);           // ej: 'registration_certificate', 'constancia'
            $table->string('name', 255);           // nombre descriptivo del archivo
            $table->string('path', 500);           // ruta en storage
            $table->string('disk', 50)->default('public'); // disco: 'public', 's3', etc.
            $table->string('mime_type', 100)->nullable();  // ej: 'application/pdf'
            $table->unsignedBigInteger('size')->nullable(); // tamaño en bytes
            $table->timestamps();

            $table->index(['fileable_type', 'fileable_id'], 'core_files_fileable_index');
            $table->index('type');
            $table->index('disk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_files');
    }
};
