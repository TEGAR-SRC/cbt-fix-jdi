<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'current_session_id')) {
                $table->string('current_session_id', 100)->nullable()->after('gender');
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'status')) {
                $table->string('status', 20)->nullable()->after('grade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'current_session_id')) {
                $table->dropColumn('current_session_id');
            }
        });
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
