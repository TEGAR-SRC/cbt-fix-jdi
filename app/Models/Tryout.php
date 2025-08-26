<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tryout extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'lesson_id',
        'classroom_id',
        'duration_minutes',
        'start_at',
        'end_at',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'published_at' => 'datetime',
        'duration_minutes' => 'integer',
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
        return $this->hasMany(TryoutQuestion::class)->orderBy('order')->orderBy('id');
    }

    public function enrollments()
    {
        return $this->hasMany(TryoutEnrollment::class);
    }
}
