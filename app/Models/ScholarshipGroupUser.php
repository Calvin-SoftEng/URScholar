<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class ScholarshipGroupUser extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'scholarshscholarship_group_idip_id'];

    public function scholarshipGroups()
    {
        return $this->belongsToMany(ScholarshipGroup::class);
    }

}
