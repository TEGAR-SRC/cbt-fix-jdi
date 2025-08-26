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
        Schema::create('proctoring_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proctoring_session_id')->constrained('proctoring_sessions')->onDelete('cascade');
            $table->string('violation_type');
            $table->string('violation_category')->nullable();
            $table->timestamp('detected_at');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->json('evidence')->nullable();
            $table->string('action_taken')->nullable();
            $table->boolean('auto_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            $table->index(['proctoring_session_id', 'violation_type'], 'pv_session_type_idx');
            $table->index(['detected_at', 'severity'], 'pv_detected_severity_idx');
            $table->index('auto_resolved', 'pv_auto_resolved_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proctoring_violations');
    }
};