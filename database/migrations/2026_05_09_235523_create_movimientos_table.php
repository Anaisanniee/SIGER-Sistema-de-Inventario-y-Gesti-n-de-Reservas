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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id('mov_id');
            $table->dateTime('mov_fecha');
            $table->unsignedBigInteger('aula_origen_id');
            $table->unsignedBigInteger('aula_destino_id');
            $table->foreign('aula_origen_id')->references('aula_id')->on('aulas');
            $table->foreign('aula_destino_id')->references('aula_id')->on('aulas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
