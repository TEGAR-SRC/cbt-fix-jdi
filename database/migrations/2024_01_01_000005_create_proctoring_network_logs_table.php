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
        Schema::create('proctoring_network_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proctoring_session_id')->constrained('proctoring_sessions')->onDelete('cascade');
            $table->enum('connection_status', ['connected', 'disconnected', 'poor', 'excellent'])->default('connected');
            $table->string('network_type')->nullable(); // WiFi, Mobile, etc
            $table->timestamp('status_changed_at');
            $table->integer('duration_seconds')->nullable();
            $table->text('additional_info')->nullable();
            $table->boolean('exam_paused')->default(false);
            $table->timestamps();
            
            $table->index(['proctoring_session_id', 'connection_status'], 'pnl_session_status_idx');
            $table->index('status_changed_at', 'pnl_status_changed_at_idx');
            $table->index('exam_paused', 'pnl_exam_paused_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proctoring_network_logs');
    }
};