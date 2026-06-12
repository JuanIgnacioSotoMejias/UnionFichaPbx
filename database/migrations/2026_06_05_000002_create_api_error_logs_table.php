<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_error_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent();
            $table->smallInteger('codigo_http')->nullable();
            $table->string('endpoint', 500)->nullable();
            $table->text('mensaje_error');
            $table->json('payload')->nullable();
            $table->boolean('resuelto')->default(false);
            $table->timestamps();

            $table->index('timestamp');
            $table->index('resuelto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_error_logs');
    }
};
