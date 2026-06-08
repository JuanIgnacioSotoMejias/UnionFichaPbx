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
    Schema::create('bitacora_ami', function (Blueprint $table) {
        $table->id();
        $table->string('comando_enviado'); // QueueAdd o QueueRemove
        $table->string('extension', 10);
        $table->text('respuesta_asterisk'); // Respuesta cruda del socket
        $table->enum('status', ['SUCCESS', 'ERROR']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_ami');
    }
};
