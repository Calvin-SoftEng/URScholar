<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipGroup extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'campus_id','name', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'scholarship_group_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    // Get the latest message for the scholarship group
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }
}
