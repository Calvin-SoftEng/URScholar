<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipGroupUser extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'scholarship_group_id', 'status'];

    public function staffGroup()
    {
        return $this->belongsToMany(StaffGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
