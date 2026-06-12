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
        Schema::create('ext_operador', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extension_id');
            $table->unsignedBigInteger('operador_config_id');
            $table->timestamps();
            
            // Un operador no debería estar dos veces en la misma extensión
            $table->unique(['extension_id', 'operador_config_id']);
        });

        // Migrar datos existentes
        $extensions = \Illuminate\Support\Facades\DB::table('extensions')->whereNotNull('operador_config_id')->get();
        foreach ($extensions as $ext) {
            \Illuminate\Support\Facades\DB::table('ext_operador')->insert([
                'extension_id' => $ext->id,
                'operador_config_id' => $ext->operador_config_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Eliminar columna vieja de extensions
        Schema::table('extensions', function (Blueprint $table) {
            // No dropeamos foreign si da error por FK faltante, usamos try-catch
            try {
                $table->dropForeign(['operador_config_id']); 
            } catch (\Exception $e) {}
            $table->dropColumn('operador_config_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extensions', function (Blueprint $table) {
            $table->foreignId('operador_config_id')->nullable()->constrained('operadores_config')->nullOnDelete();
        });
        Schema::dropIfExists('ext_operador');
    }
};
