<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'email',
        'campus_id',
        'course_id',
        'year_level',
        'semester',
        'age',
        'religion',
        'birthplace',
        'birthdate',
        'civil_status',
        'permanent_address',
        'facebook_account',
        'contact_no',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
