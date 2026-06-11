<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('operador_sessions', function (Blueprint $table) {
            $table->timestamp('last_ping_at')->nullable()->after('estado_actual');
        });
    }

    public function down(): void
    {
        Schema::table('operador_sessions', function (Blueprint $table) {
            $table->dropColumn('last_ping_at');
        });
    }
};
