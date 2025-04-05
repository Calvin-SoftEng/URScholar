<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_id',
        'requirement_id',
        'filename',
        'path',
        'mime_type',
    ];

    /**
     * Get the scholarship that owns the template.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    /**
     * Get the requirement this template is associated with.
     */
    public function requirement()
    {
        return $this->belongsTo(Requirements::class, 'requirement_id');
    }
}