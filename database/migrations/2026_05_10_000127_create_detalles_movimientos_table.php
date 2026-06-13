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
        Schema::create('detalles_movimientos', function (Blueprint $table) {
            $table->id('det_mov_id');
            $table->string('det_mov_estado_activo', 255);
            $table->unsignedBigInteger('act_id');
            $table->unsignedBigInteger('mov_id');
            $table->foreign('act_id')->references('act_id')->on('activos');
            $table->foreign('mov_id')->references('mov_id')->on('movimientos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_movimientos');
    }
};
