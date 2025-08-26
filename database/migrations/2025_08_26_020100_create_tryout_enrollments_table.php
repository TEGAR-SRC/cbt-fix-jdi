<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('tryout_enrollments', function(Blueprint $table){ $table->id(); $table->foreignId('tryout_id')->constrained()->cascadeOnDelete(); $table->foreignId('student_id')->constrained()->cascadeOnDelete(); $table->timestamps(); $table->unique(['tryout_id','student_id'],'tryout_enroll_unique'); }); } public function down(): void { Schema::dropIfExists('tryout_enrollments'); } };
