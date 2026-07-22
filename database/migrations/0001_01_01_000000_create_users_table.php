<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // 🌟 Importamos la fachada DB para desactivar las restricciones

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Apagamos la revisión de llaves foráneas para evitar errores de ordenamiento
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // TABLA: users
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); 
            $table->string('USU_CEDULA')->unique();
            $table->string('USU_PRIMER_NOMBRE');
            $table->string('USU_SEGUNDO_NOMBRE')->nullable(); 
            $table->string('USU_PRIMER_APELLIDO');
            $table->string('USU_SEGUNDO_APELLIDO')->nullable(); 
            $table->string('USU_CORREO')->unique();
            $table->string('USU_CONTRASEÑA');
            $table->string('USU_ESTADO')->default('Activo'); 
            
            
            $table->unsignedBigInteger('ROL_ID'); 
            
            $table->rememberToken();
            $table->timestamps();
        });

        
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); 
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            
            
            $table->unsignedBigInteger('user_id')->nullable()->index();
            
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};