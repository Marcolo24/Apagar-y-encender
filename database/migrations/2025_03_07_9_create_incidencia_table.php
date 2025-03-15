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
        Schema::disableForeignKeyConstraints();

        Schema::create('incidencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('users');
            $table->unsignedBigInteger('id_tecnico')->nullable();
            $table->foreign('id_tecnico')->references('id')->on('users');
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estado_incidencia');
            $table->unsignedBigInteger('id_subcategoria');
            $table->foreign('id_subcategoria')->references('id')->on('subcategoria');
            $table->unsignedBigInteger('id_prioridad');
            $table->foreign('id_prioridad')->references('id')->on('prioridad');
            $table->string('titulo');
            $table->string('descripcion');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_final')->nullable();
            $table->string('img')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencia');
    }
};
