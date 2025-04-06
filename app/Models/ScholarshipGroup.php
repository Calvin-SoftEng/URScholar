<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipGroup extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'scholarship_id','batch_id', 'campus_id'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
    
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
