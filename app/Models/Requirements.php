<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirements extends Model
{
    protected $fillable = ['scholarship_id', 'requirements', 'application_start', 'deadline'];

    protected $casts = [
        'requirements' => 'array',
    ];

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }
    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function submittedRequirements()
    {
        return $this->hasMany(SubmittedRequirements::class);
    }
}
