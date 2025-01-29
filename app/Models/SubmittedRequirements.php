<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedRequirements extends Model
{
    protected $fillable = ['scholar_id', 'requirement_id','submitted_requirements'];

    protected $casts = [
        'submitted_requirements' => 'array',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirements::class);
    }
}
