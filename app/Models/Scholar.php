<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Scholar extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'course'];
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function submittedRequirements()
    {
        return $this->hasMany(SubmittedRequirements::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
