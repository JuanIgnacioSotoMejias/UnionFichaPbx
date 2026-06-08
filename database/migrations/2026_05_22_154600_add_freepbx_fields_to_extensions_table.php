<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega campos para sincronizar las extensiones con FreePBX:
     * - nombre_freepbx: nombre del Display Name en la central (ej. "Operador 1")
     * - tipo_tecnologia: pjsip, sip, iax2, etc.
     * - operador_config_id: FK al operador asignado (asignación automática)
     * - sincronizado_at: última vez que se sincronizó con FreePBX
     */
    public function up(): void
    {
        Schema::table('extensions', function (Blueprint $table) {
            $table->string('nombre_freepbx')->nullable()->after('descripcion');
            $table->string('tipo_tecnologia')->default('pjsip')->after('nombre_freepbx');
            $table->foreignId('operador_config_id')->nullable()->after('estado')
                  ->constrained('operadores_config')->nullOnDelete();
            $table->timestamp('sincronizado_at')->nullable()->after('operador_config_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extensions', function (Blueprint $table) {
            $table->dropForeign(['operador_config_id']);
            $table->dropColumn(['nombre_freepbx', 'tipo_tecnologia', 'operador_config_id', 'sincronizado_at']);
        });
    }
};
