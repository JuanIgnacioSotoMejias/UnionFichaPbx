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
        Schema::create('operador_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operador_config_id')->constrained('operadores_config')->cascadeOnDelete();
            $table->string('extension', 10);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->time('hora_inicio_esperada')->nullable();
            $table->time('hora_fin_esperada')->nullable();
            $table->string('estado_actual')->default('LOGIN'); // LOGIN, PAUSE, TALKING, LOGOUT
            $table->integer('total_active_seconds')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operador_sessions');
    }
};
