<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class AcademicYear extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'school_year_id',
        'semester',
    ];

    public function school_year()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
