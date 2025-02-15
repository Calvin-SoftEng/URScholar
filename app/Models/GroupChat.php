<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupChat extends Model
{
    protected $fillable = ['name', 'scholarship_id', 'batch_id'];

    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
