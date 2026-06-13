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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id('aula_id');
            $table->string('aula_nombre', 25);
            $table->integer('aula_capacidad');
            $table->string('aula_estado', 25);
            $table->boolean('aula_reservable');
            $table->unsignedBigInteger('tip_aula_id');
            $table->foreign('tip_aula_id')->references('tip_aula_id')->on('tipos_aulas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
