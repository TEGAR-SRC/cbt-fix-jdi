<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TryoutAnswer extends Model { use HasFactory; protected $fillable=['attempt_id','tryout_question_id','answer','is_correct']; public function attempt(){ return $this->belongsTo(TryoutAttempt::class,'attempt_id'); } public function question(){ return $this->belongsTo(TryoutQuestion::class,'tryout_question_id'); }}
