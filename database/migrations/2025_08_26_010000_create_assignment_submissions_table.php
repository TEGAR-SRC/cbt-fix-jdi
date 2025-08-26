<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('total_correct')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->decimal('score',5,2)->default(0);
            $table->timestamps();
            $table->unique(['assignment_id','student_id'],'asgmt_sub_unique');
        });

        Schema::create('assignment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->cascadeOnDelete();
            $table->foreignId('assignment_question_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->unique(['submission_id','assignment_question_id'],'asgmt_ans_unique');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('assignment_answers');
        Schema::dropIfExists('assignment_submissions');
    }
};
