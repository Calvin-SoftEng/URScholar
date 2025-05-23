<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grantees extends Model
{
    protected $fillable = ['scholarship_id', 'batch_id', 'school_year_id', 'scholar_id', 'school_year', 'semester', 'student_status', 'status'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
