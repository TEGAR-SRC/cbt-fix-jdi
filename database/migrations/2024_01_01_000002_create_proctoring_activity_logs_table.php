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
        Schema::create('proctoring_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proctoring_session_id')->constrained('proctoring_sessions')->onDelete('cascade');
            $table->string('activity_type');
            $table->string('activity_subtype')->nullable();
            $table->timestamp('timestamp');
            $table->json('metadata')->nullable();
            $table->text('description')->nullable();
            $table->enum('severity', ['info', 'warning', 'error', 'critical'])->default('info');
            $table->boolean('is_violation')->default(false);
            $table->timestamps();
            
            // Use shorter explicit index names to avoid MySQL 64-char identifier limit
            $table->index(['proctoring_session_id', 'activity_type'], 'pal_session_activity_idx');
            $table->index(['timestamp', 'severity'], 'pal_time_severity_idx');
            $table->index('is_violation', 'pal_is_violation_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proctoring_activity_logs');
    }
}; 