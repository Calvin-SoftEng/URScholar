<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    protected $fillable = [
        'scholarship_id',
        'name',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function condition()
    {
        return $this->hasMany(Condition::class);
    }

}
