<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{

    use HasFactory;
    protected $fillable = ['name', 'created_id', 'assign_id', 'abbreviation', 'since', 'moa_file' ,'description', 'logo', 'created_by'];

    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_id');
    }

    public function assign()
    {
        return $this->belongsTo(User::class, 'assign_id');
    }

    public function moa()
    {
        return $this->hasMany(SponsorMoa::class);
    }
}
