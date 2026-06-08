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
        Schema::table('bitacora_ami', function (Blueprint $table) {
            $table->time('hora_inicio_esperada')->nullable()->after('status');
            $table->time('hora_fin_esperada')->nullable()->after('hora_inicio_esperada');
            $table->string('estado_actual')->nullable()->after('hora_fin_esperada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitacora_ami', function (Blueprint $table) {
            $table->dropColumn(['hora_inicio_esperada', 'hora_fin_esperada', 'estado_actual']);
        });
    }
};
