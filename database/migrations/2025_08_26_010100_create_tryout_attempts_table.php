<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tryout_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('total_correct')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->decimal('score',5,2)->default(0);
            $table->string('status',20)->nullable();
            $table->timestamps();
            $table->unique(['tryout_id','student_id'],'tryout_attempt_unique');
        });

        Schema::create('tryout_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('tryout_attempts')->cascadeOnDelete();
            $table->foreignId('tryout_question_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->unique(['attempt_id','tryout_question_id'],'tryout_ans_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tryout_answers');
        Schema::dropIfExists('tryout_attempts');
    }
};
