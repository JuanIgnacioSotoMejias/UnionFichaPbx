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
        Schema::create('alertas_productividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operador_config_id')->nullable()->constrained('operadores_config')->nullOnDelete();
            $table->string('tipo_alerta'); // SHORT_CALL, IRREGULAR_SESSION, QUEUE_LEAK, LATE_LOGIN, EARLY_LOGOUT
            $table->enum('nivel', ['INFO', 'WARNING', 'CRITICAL'])->default('INFO');
            $table->text('descripcion');
            $table->json('metadatos')->nullable(); // Para guardar detalles técnicos (duración, número, etc)
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_productividad');
    }
};
