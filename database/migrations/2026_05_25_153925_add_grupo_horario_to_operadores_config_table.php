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
        Schema::table('operadores_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('grupo_horario')->nullable()->after('queue_name')
                  ->comment('1 = Comida 12pm/7pm, Sueño 10pm-2am | 2 = Comida 1pm/8pm, Sueño 2am-6am');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operadores_config', function (Blueprint $table) {
            $table->dropColumn('grupo_horario');
        });
    }
};
