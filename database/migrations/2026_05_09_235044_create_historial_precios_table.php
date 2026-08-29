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
        Schema::create('historial_precios', function (Blueprint $table) {
            $table->id('his_pre_id');
            $table->dateTime('his_pre_fecha_cambio');
            $table->decimal('his_pre_valor', 12, 2);
            $table->string('his_pre_motivo', 255);
            $table->unsignedBigInteger('act_id');
            $table->foreign('act_id')->references('act_id')->on('activos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_precios');
    }
};
