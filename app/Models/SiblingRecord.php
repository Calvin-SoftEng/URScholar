<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiblingRecord extends Model
{
    protected $fillable = [
        'family_record_id',
        'first_name',
        'last_name',
        'middle_name',
        'age',
        'occupation',
    ];

    public function familyRecord()
    {
        return $this->belongsTo(FamilyRecord::class);
    }
}
