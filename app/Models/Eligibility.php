<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eligibility extends Model
{
    protected $fillable = [
        'name',
        'status',
        'user_id'
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function condition()
    {
        return $this->hasMany(Condition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
