<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class StaffGroupUser extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['user_id', 'staff_group_id'];
}
