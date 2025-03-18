<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class ScholarshipForm extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name'];

    public function scholarshipformdata()
    {
        return $this->hasMany(ScholarshipFormData::class);
    }
}
