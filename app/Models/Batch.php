<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['scholarship_id', 'scholar_id', 'batch_no', 'school_year_id', 'semester', 'campus_id', 'semester', 'total_scholars', 'sub_total', 'validated', 'read', 'status'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function disbursement()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function grantees()
    {
        return $this->hasMany(Grantees::class);
    }

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    //chat
    public function users()
    {
        return $this->belongsToMany(User::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Get the latest message for the scholarship group
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }


    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function scholarshipGroups()
    {
        return $this->hasMany(ScholarshipGroup::class);
    }
}
