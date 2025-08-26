<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory; class AssignmentEnrollment extends Model { use HasFactory; protected $fillable=['assignment_id','student_id']; public function assignment(){ return $this->belongsTo(Assignment::class);} public function student(){ return $this->belongsTo(Student::class);} }
