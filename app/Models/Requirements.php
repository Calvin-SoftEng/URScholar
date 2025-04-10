<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirements extends Model
{
    protected $fillable = ['scholarship_id', 'requirements', 'date_start', 'date_end', 'subtotal_scholars', 'total_scholars'];

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function submittedRequirements()
    {
        return $this->hasMany(SubmittedRequirements::class);
    }

    public function scholarshipTemplates()
    {
        return $this->hasMany(ScholarshipTemplate::class);
    }
}
