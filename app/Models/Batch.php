<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['scholarship_id', 'scholar_id', 'batch_no', 'school_year_id', 'campus_id', 'semester' , 'total_scholars', 'read' , 'status'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function disbursement()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function grantees()
    {
        return $this->hasMany(Grantees::class);
    }

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
