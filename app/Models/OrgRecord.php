<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgRecord extends Model
{
    protected $fillable = [
        'student_record_id',
        'name',
        'year',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
