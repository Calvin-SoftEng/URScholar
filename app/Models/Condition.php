<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = [
        'eligibility_id',
        'name',
    ];

    public function elibility()
    {
        return $this->belongsTo(Eligibility::class);
    }

    public function eligible()
    {
        return $this->hasMany(Eligible::class);
    }
}
