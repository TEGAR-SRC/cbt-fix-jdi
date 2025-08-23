<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','operator','teacher','parent','dinas') NOT NULL DEFAULT 'admin'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','operator','teacher','parent') NOT NULL DEFAULT 'admin'");
    }
};
