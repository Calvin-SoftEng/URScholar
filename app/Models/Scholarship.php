<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = ['name', 'sponsor_id', 'scholarshipType', 'status', 'date_start', 'date_end'];

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function requirements()
    {
        return $this->belongsTo(Requirements::class);
    }

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function campusRecipients()
    {
        return $this->hasMany(CampusRecipients::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
