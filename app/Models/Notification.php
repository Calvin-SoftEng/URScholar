<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'creator_id',
        'title',
        'message',
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('read')
            ->withTimestamps();
    }

}
