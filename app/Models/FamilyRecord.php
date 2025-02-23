<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyRecord extends Model
{
    protected $fillable = [
        'student_record_id',
        'mother',
        'father',
        'marital_status',
        'monthly_income',
        'other_income',
        'family_housing',
    ];

    public function studentRecord()
    {
        return $this->belongsTo(StudentRecord::class);
    }
}
