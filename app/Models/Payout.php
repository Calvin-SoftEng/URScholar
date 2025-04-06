<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = ['scholarship_id', 'campus_id', 'total_scholars', 'sub_total', 'school_year_id', 'semester' , 'date_start', 'date_end', 'status'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function payout_schedule()
    {
        return $this->hasMany(PayoutSchedule::class);
    }

    public function disbursement()
    {
        return $this->hasMany(Disbursement::class);
    }
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
