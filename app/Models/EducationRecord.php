<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationRecord extends Model
{
    protected $fillable = [
        'student_record_id',
        'elem',
        'junior',
        'senior',
        'college',
        'vocal',
        'postg',
    ];

    protected $casts = [
        'elem' => 'array',
        'junior' => 'array',
        'senior' => 'array',
        'college' => 'array',
        'vocal' => 'array',
        'postg' => 'array',
    ];
    
    public function studentrecord()
    {
        return $this->belongsTo(StudentRecord::class);
    }
}
