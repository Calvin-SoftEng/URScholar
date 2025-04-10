<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTrack extends Model
{

    protected $fillable = [
        'scholarship_id',
        'batch_id',
        'school_year_id',
        'semester',
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
