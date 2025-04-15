<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['scholar_id', 'academic_year_id', 'grade', 'cog', 'path' ,'school_year_id', 'semester', 'status'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}

