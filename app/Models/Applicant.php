<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{

    protected $fillable = [
        'first_name', 'last_name', 'email', 'course', 'file_path', 'scholarship_id',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
