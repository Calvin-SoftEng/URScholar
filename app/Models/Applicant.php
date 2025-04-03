<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{

    protected $fillable = [
        'scholarship_id', 'batch_id', 'scholar_id', 'school_year_id', 'semester', 'status',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
