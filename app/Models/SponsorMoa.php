<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SponsorMoa extends Model
{
    use HasFactory;
    protected $fillable = ['sponsor_id','moa' ,'moa_path', 'status'];

    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
