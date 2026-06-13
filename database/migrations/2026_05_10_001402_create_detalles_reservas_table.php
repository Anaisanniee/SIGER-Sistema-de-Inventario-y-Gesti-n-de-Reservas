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
        Schema::create('detalles_reservas', function (Blueprint $table) {
            $table->id('det_re_id');
            $table->string('det_re_motivo', 255);
            $table->dateTime('det_re_fecha_ini');
            $table->date('det_re_fecha_fin');
            $table->unsignedBigInteger('res_id');
            $table->unsignedBigInteger('act_id');
            $table->unsignedBigInteger('det_re_aula_destino_act')->nullable();
            $table->unsignedBigInteger('aula_id');
            $table->foreign('res_id')->references('res_id')->on('reservas');
            $table->foreign('act_id')->references('act_id')->on('activos');
            $table->foreign('det_re_aula_destino_act')->references('aula_id')->on('aulas');
            $table->foreign('aula_id')->references('aula_id')->on('aulas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_reservas');
    }
};
