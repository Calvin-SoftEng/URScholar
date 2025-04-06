<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifier extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
