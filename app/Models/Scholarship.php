<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Scholarship extends Model
{

    use HasFactory, Notifiable;
    protected $fillable = ['name', 'sponsor_id', 'scholarshipType', 'status', 'date_start', 'date_end', 'read'];

    public function scholars()
    {
        return $this->hasMany(Scholar::class);
    }

    public function disbursement()
    {
        return $this->hasMany(Disbursement::class);
    }
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function eligibility()
    {
        return $this->hasMany(Eligibility::class);
    }

    public function eligible()
    {
        return $this->hasMany(Eligible::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirements::class);
    }

    public function criteria()
    {
        return $this->hasMany(Criteria::class);
    }

    public function campusRecipients()
    {
        return $this->hasMany(CampusRecipients::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function grantees()
    {
        return $this->hasMany(Grantees::class);
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
}
