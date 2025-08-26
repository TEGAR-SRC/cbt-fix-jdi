<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'classroom_id',
        'nisn',
        'name',
        'password',
        'gender'
    ];

    /**
     * classroom
     *
     * @return void
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function assignment_enrollments()
    {
        return $this->hasMany(AssignmentEnrollment::class);
    }

    public function tryout_enrollments()
    {
        return $this->hasMany(TryoutEnrollment::class);
    }

    /**
     * guardians (orang tua)
     */
    public function guardians()
    {
        return $this->belongsToMany(\App\Models\Guardian::class, 'guardian_student');
    }
}
