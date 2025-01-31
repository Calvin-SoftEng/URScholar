<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = ['name', 'sponsor_id', 'scholarshipType', 'status', 'created_by' ];

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

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
