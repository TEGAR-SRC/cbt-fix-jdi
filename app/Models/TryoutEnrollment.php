<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; use Illuminate\Database\Eloquent\Factories\HasFactory; class TryoutEnrollment extends Model { use HasFactory; protected $fillable=['tryout_id','student_id']; public function tryout(){ return $this->belongsTo(Tryout::class);} public function student(){ return $this->belongsTo(Student::class);} }
