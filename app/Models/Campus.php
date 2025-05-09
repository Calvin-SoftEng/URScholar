<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = ['name', 'location', 'coordinator_id', 'cashier_id'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function scholar()
    {
        return $this->hasMany(Scholar::class);
    }



    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function campusRecipients()
    {
        return $this->hasMany(CampusRecipients::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    //chat
    public function scholarships()
    {
        return $this->belongsToMany(Scholarship::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function batch()
    {
        return $this->belongsToMany(Batch::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function scholarshipGroups()
    {
        return $this->hasMany(ScholarshipGroup::class);
    }
}
