<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birthdate',
        'placebirth',
        'age',
        'gender',
        'civil',
        'religion',
        'guardian',
        'relationship',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educationrecord()
    {
        return $this->hasMany(EducationRecord::class);
    }

    public function familyrecord()
    {
        return $this->hasMany(FamilyRecord::class);
    }

    public function orgrecord()
    {
        return $this->hasMany(OrgRecord::class);
    }
}
