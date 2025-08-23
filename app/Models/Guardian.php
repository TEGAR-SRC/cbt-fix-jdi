<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'name', 'user_id',
        'phone',
        'email',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'guardian_student');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
