<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $fillable = ['scholarship_id', 'scholarship_form_data_id', 'grade'];

    protected $table = 'criterias';

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function scholarshipFormData()
    {
        return $this->belongsTo(ScholarshipFormData::class);
    }
}
