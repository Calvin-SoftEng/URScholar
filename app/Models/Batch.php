<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['scholarship_id', 'scholar_id', 'batch_no', 'school_year', 'semester'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
