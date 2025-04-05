<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentNotifier extends Model
{
    protected $fillable = [
        'scholar_id',
        'title',
        'message',
        'type',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }
}
