<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;
    protected $fillable = ['year'];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
