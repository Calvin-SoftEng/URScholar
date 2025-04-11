<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTrack extends Model
{

    protected $fillable = [
        'scholarship_id',
        'campus_id',
        'school_year_id',
        'semester',
        'school_year_id',
        'status',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
