<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('assignment_submissions')) {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                if (!Schema::hasColumn('assignment_submissions', 'status')) {
                    $table->string('status',20)->nullable()->after('score');
                }
            });
        }
        if (Schema::hasTable('tryout_attempts')) {
            Schema::table('tryout_attempts', function (Blueprint $table) {
                if (!Schema::hasColumn('tryout_attempts', 'status')) {
                    $table->string('status',20)->nullable()->after('score');
                }
            });
        }
    }
    public function down(): void
    {
        if (Schema::hasTable('assignment_submissions')) {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                if (Schema::hasColumn('assignment_submissions', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
        if (Schema::hasTable('tryout_attempts')) {
            Schema::table('tryout_attempts', function (Blueprint $table) {
                if (Schema::hasColumn('tryout_attempts', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};