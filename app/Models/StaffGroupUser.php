<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class StaffGroupUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_id', 'staff_group_id'];

    public function staffGroup()
    {
        return $this->belongsToMany(StaffGroup::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
