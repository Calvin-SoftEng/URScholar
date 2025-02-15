<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Group;

class Message extends Model
{
    protected $fillable = ['user_id','content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groupchat()
    {
        return $this->belongsTo(GroupChat::class);
    }
}
