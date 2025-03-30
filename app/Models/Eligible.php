<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eligible extends Model
{
    protected $fillable = [
        'scholarship_id',
        'condition_id',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
}
