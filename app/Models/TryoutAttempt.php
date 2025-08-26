<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TryoutAttempt extends Model { use HasFactory; protected $fillable=['tryout_id','student_id','started_at','finished_at','total_correct','total_questions','score']; protected $casts=['started_at'=>'datetime','finished_at'=>'datetime']; public function tryout(){ return $this->belongsTo(Tryout::class);} public function student(){ return $this->belongsTo(Student::class);} public function answers(){ return $this->hasMany(TryoutAnswer::class,'attempt_id'); }}
