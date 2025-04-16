<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $fillable = ['scholarship_id', 'batch_id', 'scholar_id', 'reasons_of_not_claimed', 'claimed_at', 'claimed_by', 'status'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function claimedBy()
{
    return $this->belongsTo(User::class, 'claimed_by');
}
}
