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
    $table->id('user_id'); /*lo cambie de USU_ID por user_id*/
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
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); /*lo cambie de USU_ID por user_id*/
            
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