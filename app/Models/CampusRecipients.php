<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusRecipients extends Model
{
    protected $fillable = ['scholarship_id', 'campus_id', 'selected_campus', 'slots', 'remaining_slots'];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
