<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Scholar extends Model
{
    protected $fillable = [
        'student_number',
        'user_id',
        'hei_name',
        'campus_id',
        'course_id',
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
        'year_level',
        'total_units',
        'street',
        'municipality',
        'province',
        'pwd_classification',
        'email',
        'status',
        'student_status',
    ];
    protected $attributes = [
        'status' => 'Unverified',
        'student_status ' => 'Unenrolled'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function submittedRequirements()
    {
        return $this->hasMany(SubmittedRequirements::class);
    }

    public function studentrecord()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function grantees()
    {
        return $this->hasMany(Grantees::class);
    }
}
