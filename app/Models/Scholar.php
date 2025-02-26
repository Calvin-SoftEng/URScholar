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
        'batch_id',
        'urscholar_id',
        'qr_code',
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
        'email',
        'status',
    ];
    protected $attributes = [
        'status' => 'Unverified'
    ];

    public function scopeMatchPotentialScholars($query, $course, $yearLevel)
    {
        return $query->where('status', 'Unverified')
            ->where('course', $course)
            ->where('year_level', $yearLevel);
    }
    
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function submittedRequirements()
    {
        return $this->hasMany(SubmittedRequirements::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }
}
