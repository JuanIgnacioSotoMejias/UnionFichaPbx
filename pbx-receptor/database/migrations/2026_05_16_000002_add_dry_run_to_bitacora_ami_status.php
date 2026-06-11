<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sólo ejecutar ALTER en bases que soporten ENUM (MySQL/MariaDB).
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `bitacora_ami` MODIFY COLUMN `status` ENUM('SUCCESS', 'ERROR', 'DRY_RUN') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `bitacora_ami` MODIFY COLUMN `status` ENUM('SUCCESS', 'ERROR') NOT NULL");
        }
    }
};
