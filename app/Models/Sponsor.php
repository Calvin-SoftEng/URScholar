<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{

    use HasFactory;
    protected $fillable = ['name', 'abbreviation', 'since', 'moa_file' ,'description', 'logo', 'created_by'];

    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }
}
