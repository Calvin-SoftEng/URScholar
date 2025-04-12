<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{

    protected $fillable = [
        'scholarship_id', 'applicant_track_id', 'scholar_id', 'school_year_id', 'semester', 'status', 'validated_date','essay',
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
