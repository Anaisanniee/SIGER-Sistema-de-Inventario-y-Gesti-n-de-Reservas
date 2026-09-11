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
       Schema::create('user_devices', function (Blueprint $table) {
           $table->id();
    
           $table->unsignedBigInteger('user_id');
           $table->foreign('user_id')->references('USU_ID')->on('users')->onDelete('cascade');

           $table->string('ip_address');
           $table->text('user_agent');
           $table->timestamps();
       });
   }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
