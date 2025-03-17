<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['campus_id', 'name', 'abbreviation'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
