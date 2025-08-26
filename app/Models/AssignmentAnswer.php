<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssignmentAnswer extends Model { use HasFactory; protected $fillable=['submission_id','assignment_question_id','answer','is_correct']; public function submission(){ return $this->belongsTo(AssignmentSubmission::class,'submission_id'); } public function question(){ return $this->belongsTo(AssignmentQuestion::class,'assignment_question_id'); }}
