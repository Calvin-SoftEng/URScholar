<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Scholar extends Model
{
    protected $fillable = [
        'scholarship_id',
        'hei_name',
        'campus',
        'grant',
        'batch_no',
        'app_no',
        'award_no',
        'last_name',
        'first_name',
        'extname',
        'middle_name',
        'sex',
        'birthdate',
        'course',
        'year_level',
        'total_units',
        'street',
        'municipality',
        'province',
        'pwd_classification',
    ];
    
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
