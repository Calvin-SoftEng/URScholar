<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmittedRequirements extends Model
{
    protected $fillable = ['scholar_id', 'requirement_id', 'requirement','submitted_requirements', 'path'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirements::class);
    }
}
