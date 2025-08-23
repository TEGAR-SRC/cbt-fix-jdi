<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'current_question')) {
                $table->unsignedInteger('current_question')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('grades', 'last_activity_at')) {
                $table->timestamp('last_activity_at')->nullable()->after('current_question');
            }
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'last_activity_at')) {
                $table->dropColumn('last_activity_at');
            }
            if (Schema::hasColumn('grades', 'current_question')) {
                $table->dropColumn('current_question');
            }
        });
    }
};
