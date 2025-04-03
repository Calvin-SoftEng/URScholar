<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = ['scholarship_id','total_scholars', 'sub_total', 'date_start', 'date_end', 'status'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function payout_schedule()
    {
        return $this->hasMany(PayoutSchedule::class);
    }
}
