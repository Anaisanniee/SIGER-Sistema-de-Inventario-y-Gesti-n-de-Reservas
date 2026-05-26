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
      Schema::create('users', function (Blueprint $table) {
    $table->id('USU_ID'); 
    $table->string('USU_CEDULA')->unique();
    $table->string('USU_PRIMER_NOMBRE');
    $table->string('USU_SEGUNDO_NOMBRE')->nullable(); 
    $table->string('USU_PRIMER_APELLIDO');
    $table->string('USU_SEGUNDO_APELLIDO')->nullable(); 
    $table->string('USU_CORREO')->unique();
    $table->string('USU_CONTRASEÑA');
    $table->string('USU_ESTADO')->default('Activo'); 
    
    // Dejamos solo la columna, SIN la restricción de llave foránea por ahora
    $table->unsignedBigInteger('ROL_ID'); 
    
    $table->rememberToken();
    $table->timestamps();
});

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Laravel busca 'email' por defecto para recuperar clave
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('USU_ID')->nullable()->index();
            $table->foreign('USU_ID')->references('USU_ID')->on('users')->onDelete('cascade');
            
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};