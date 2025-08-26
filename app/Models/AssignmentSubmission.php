<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssignmentSubmission extends Model {
    use HasFactory; protected $fillable=['assignment_id','student_id','started_at','finished_at','total_correct','total_questions','score','status']; protected $casts=['started_at'=>'datetime','finished_at'=>'datetime'];
    public function assignment(){ return $this->belongsTo(Assignment::class);}    
    public function student(){ return $this->belongsTo(Student::class);}    
    public function answers(){ return $this->hasMany(AssignmentAnswer::class,'submission_id'); }
}
