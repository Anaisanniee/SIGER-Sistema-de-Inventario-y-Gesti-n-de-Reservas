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
        Schema::create('activos', function (Blueprint $table) {
            $table->id('act_id');
            $table->string('act_foto', 255)->nullable();
            $table->string('act_nombre', 50);
            $table->string('act_serial', 255)->unique();
            $table->string('act_marca', 50)->nullable();
            $table->string('act_estado_fisico', 50);
            $table->boolean('act_reservable');
            $table->date('act_fecha_ingreso');
            $table->unsignedBigInteger('aula_id');
            $table->unsignedBigInteger('cate_id');
            $table->foreign('aula_id')->references('aula_id')->on('aulas');
            $table->foreign('cate_id')->references('cate_id')->on('categorias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
