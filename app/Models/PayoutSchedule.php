<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutSchedule extends Model
{
    protected $fillable = ['payout_id','scheduled_date', 'scheduled_time', 'reminders'];

    public function payouts()
    {
        return $this->belongsTo(Payout::class);
    }
}
