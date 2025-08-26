<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tryout_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->string('image_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('video_path')->nullable();
            $table->text('option_1')->nullable();
            $table->text('option_2')->nullable();
            $table->text('option_3')->nullable();
            $table->text('option_4')->nullable();
            $table->text('option_5')->nullable();
            $table->string('answer', 5)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index(['tryout_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_questions');
    }
};