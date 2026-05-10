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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('usu_id');
            $table->string('usu_cedula', 20)->unique();
            $table->string('usu_primer_nombre', 25);
            $table->string('usu_segundo_nombre', 25)->nullable();
            $table->string('usu_primer_apellido', 25);
            $table->string('usu_segundo_apellido', 25)->nullable();
            $table->string('usu_correo', 255)->unique();
            $table->string('usu_contraseña', 255);
            $table->string('usu_estado', 25);
            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('rol_id')->on('roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
