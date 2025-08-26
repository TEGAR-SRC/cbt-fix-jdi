<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('assignment_enrollments', function(Blueprint $table){ $table->id(); $table->foreignId('assignment_id')->constrained()->cascadeOnDelete(); $table->foreignId('student_id')->constrained()->cascadeOnDelete(); $table->timestamps(); $table->unique(['assignment_id','student_id'],'assign_enroll_unique'); }); } public function down(): void { Schema::dropIfExists('assignment_enrollments'); } };
