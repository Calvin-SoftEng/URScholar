<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = [
        'eligibility_id',
        'name',
        'status',
        'user_id'
    ];

    public function elibility()
    {
        return $this->belongsTo(Eligibility::class);
    }

    public function eligible()
    {
        return $this->hasMany(Eligible::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
