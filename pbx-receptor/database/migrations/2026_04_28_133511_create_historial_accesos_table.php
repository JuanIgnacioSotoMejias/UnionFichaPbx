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
    Schema::create('historial_accesos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('operador_config_id')->constrained('operadores_config')->onDelete('cascade');
        $table->enum('evento', ['LOGIN', 'LOGOUT', 'FORCE_DISCONNECT']);
        $table->string('origen_ip', 45)->nullable();
        $table->timestamp('created_at')->useCurrent();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_accesos');
    }
};
