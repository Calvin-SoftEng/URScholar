<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class StaffGroup extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'name'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
