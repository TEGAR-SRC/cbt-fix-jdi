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
        Schema::create('proctoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('participant_id');
            $table->string('device_id')->nullable();
            $table->string('device_model')->nullable();
            $table->string('android_version')->nullable();
            $table->string('app_version')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->enum('status', ['active', 'completed', 'terminated', 'error'])->default('active');
            $table->integer('violation_count')->default(0);
            $table->integer('photo_count')->default(0);
            $table->json('device_info')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['participant_id', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proctoring_sessions');
    }
}; 