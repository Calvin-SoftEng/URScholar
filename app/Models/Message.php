<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scholarship_group_id',
        'staff_group_id',
        'conversation_id',
        'content',
    ];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function staffGroup()
    {
        return $this->belongsTo(StaffGroup::class);
    }

    public function scholarshipGroup()
    {
        return $this->belongsTo(ScholarshipGroup::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
