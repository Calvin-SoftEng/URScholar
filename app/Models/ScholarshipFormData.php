<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipFormData extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'scholarship_form_id'];

    public function scholarshipform()
    {
        return $this->belongsTo(ScholarshipForm::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }
}
