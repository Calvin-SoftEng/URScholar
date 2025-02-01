<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = ['name', 'location'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
