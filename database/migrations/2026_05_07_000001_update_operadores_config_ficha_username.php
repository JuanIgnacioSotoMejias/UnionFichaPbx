<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esta migración repara la tabla operadores_config que quedó incompleta
 * (solo tenía id + timestamps) y reemplaza external_user_id (bigint)
 * por ficha_username (string) — identificador que usa el sistema Ficha.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operadores_config', function (Blueprint $table) {

            // Eliminar external_user_id si existe (columna original del diseño anterior)
            if (Schema::hasColumn('operadores_config', 'external_user_id')) {
                // Primero eliminar el índice unique si existe
                try {
                    $table->dropUnique(['external_user_id']);
                } catch (\Throwable) {
                    // El índice no existía, continuar
                }
                $table->dropColumn('external_user_id');
            }

            // Agregar ficha_username si no existe
            if (!Schema::hasColumn('operadores_config', 'ficha_username')) {
                $table->string('ficha_username', 50)->unique()->after('id');
            }

            // Agregar nombre_operador si no existe
            if (!Schema::hasColumn('operadores_config', 'nombre_operador')) {
                $table->string('nombre_operador', 120)->after('ficha_username');
            }

            // Agregar extension si no existe
            if (!Schema::hasColumn('operadores_config', 'extension')) {
                $table->string('extension', 10)->after('nombre_operador');
            }

            // Agregar queue_name si no existe
            if (!Schema::hasColumn('operadores_config', 'queue_name')) {
                $table->string('queue_name', 20)->default('0911')->after('extension');
            }

            // Agregar is_active si no existe
            if (!Schema::hasColumn('operadores_config', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('queue_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('operadores_config', function (Blueprint $table) {
            // Revertir: quitar las columnas nuevas y restaurar external_user_id
            foreach (['ficha_username', 'nombre_operador', 'extension', 'queue_name', 'is_active'] as $col) {
                if (Schema::hasColumn('operadores_config', $col)) {
                    if ($col === 'ficha_username') {
                        try { $table->dropUnique(['ficha_username']); } catch (\Throwable) {}
                    }
                    $table->dropColumn($col);
                }
            }
            if (!Schema::hasColumn('operadores_config', 'external_user_id')) {
                $table->unsignedBigInteger('external_user_id')->unique()->after('id');
            }
        });
    }
};
