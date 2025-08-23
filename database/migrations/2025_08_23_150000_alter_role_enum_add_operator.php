<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Only run for MySQL/MariaDB where enum needs altering
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin','operator','teacher') NOT NULL DEFAULT 'admin'");
        }
        // For SQLite or others, the enum is stored as text and doesn't require changes
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            // Revert to original enum without operator
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin','teacher') NOT NULL DEFAULT 'admin'");
        }
    }
};
