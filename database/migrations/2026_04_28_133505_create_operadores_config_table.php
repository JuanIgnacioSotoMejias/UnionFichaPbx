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
    Schema::create('operadores_config', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('external_user_id')->unique(); // ID que viene de la Ficha
        $table->string('nombre_operador');
        $table->string('extension', 10); // Ej: 8001
        $table->string('queue_name', 20)->default('0911'); // La cola MASTER
        $table->boolean('is_active')->default(false); // Estado en la cola
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operadores_config');
    }
};
