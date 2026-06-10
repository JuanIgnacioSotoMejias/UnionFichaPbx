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
    Schema::create('logs_api_receptor', function (Blueprint $table) {
        $table->id();
        $table->json('payload_recibido'); // El JSON crudo de la Ficha
        $table->integer('codigo_respuesta'); // 200, 400, 500
        $table->text('mensaje_error')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_api_receptor');
    }
};
