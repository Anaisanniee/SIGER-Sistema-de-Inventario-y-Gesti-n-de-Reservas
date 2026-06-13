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
            $table->id('act_id'); // ACT_ID INT AUTO_INCREMENT PRIMARY KEY
            $table->string('act_foto', 255)->nullable(); // ACT_FOTO VARCHAR(255)
            $table->string('act_nombre', 50); // ACT_NOMBRE VARCHAR(50) NOT NULL
            $table->string('act_serial', 255)->unique(); // ACT_SERIAL VARCHAR(255) NOT NULL UNIQUE
            $table->string('act_marca', 50)->nullable(); // ACT_MARCA VARCHAR(50)
            $table->string('act_estado_fisico', 50); // ACT_ESTADO_FISICO VARCHAR(50) NOT NULL
            $table->boolean('act_reservable'); // ACT_RESERVABLE BOOLEAN NOT NULL
            $table->date('act_fecha_ingreso'); // ACT_FECHA_INGRESO DATE NOT NULL
            
            $table->softDeletes(); // SOFTDELETES DATE (Crea la columna 'deleted_at')
            $table->string('act_motivo_baja', 255)->nullable(); // ACT_MOTIVO_BAJA VARCHAR(255)

            // Llaves foráneas estructuradas en minúscula para Laravel (BigInteger Unsigned)
            $table->unsignedBigInteger('aula_id'); 
            $table->unsignedBigInteger('cate_id');

            // Restricciones de Integridad Referencial (FOREIGN KEY)
            $table->foreign('aula_id')->references('aula_id')->on('aulas')->onDelete('cascade');
            $table->foreign('cate_id')->references('cate_id')->on('categorias')->onDelete('cascade');
            
            $table->timestamps(); // Crea 'created_at' y 'updated_at'
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
