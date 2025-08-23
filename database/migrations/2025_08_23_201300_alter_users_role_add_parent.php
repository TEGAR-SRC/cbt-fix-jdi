<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'parent' to users.role enum
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','operator','teacher','parent') NOT NULL DEFAULT 'admin'");
    }

    public function down(): void
    {
        // Revert to original enum (admin, operator, teacher)
        // Note: This will fail if rows with role='parent' still exist. Handle accordingly before rolling back.
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','operator','teacher') NOT NULL DEFAULT 'admin'");
    }
};
