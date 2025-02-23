<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationRecord extends Model
{

    protected $fillable = [
        'elementary',
        'junior',
        'senior',
        'college',
        'vocational',
        'postgrad',
    ];

    protected $casts = [
        'elementary' => 'array',
        'junior' => 'array',
        'senior' => 'array',
        'college' => 'array',
        'vocational' => 'array',
        'postgrad' => 'array',
    ];
    
    public function studentrecord()
    {
        return $this->belongsTo(StudentRecord::class);
    }
}
