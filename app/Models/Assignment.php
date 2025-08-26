<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'lesson_id',
        'classroom_id',
        'due_at',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(AssignmentQuestion::class)->orderBy('order')->orderBy('id');
    }
}
