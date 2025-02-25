<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = ['scholarship_id', 'batch_id', 'scholar_id', 'status'];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
