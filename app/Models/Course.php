<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['campus_id', 'name', 'abbreviation'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function scholar()
    {
        return $this->hasMany(Scholar::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
