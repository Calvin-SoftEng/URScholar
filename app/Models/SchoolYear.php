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

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function grantees()
    {
        return $this->hasMany(Grantees::class);
    }

    public function academic_year()
    {
        return $this->hasMany(AcademicYear::class);
    }
}
