<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipGroup extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'scholarship_id'];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
