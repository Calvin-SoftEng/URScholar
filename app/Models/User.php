<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'picture',
        'email',
        'first_name',
        'last_name',
        'password',
        'usertype',
        'campus_id',
        'age',
        'address',
        'contact',
        'status',
    ];

    public function studentrecord()
    {
        return $this->hasMany(StudentRecord::class);
    }

    public function posting()
    {
        return $this->hasMany(Posting::class);
    }

    public function scholar()
    {
        return $this->hasMany(Scholar::class);
    }

    public function message()
    {
        return $this->hasMany(Message::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function batch()
    {
        return $this->belongsToMany(Batch::class, 'scholarship_groups')
            ->withTimestamps();
    }

    public function scholarshipGroups()
    {
        return $this->belongsToMany(ScholarshipGroup::class, 'scholarship_group_users');
    }

    public function staffGroups()
    {
        return $this->belongsToMany(StaffGroup::class, 'staff_group_users');
    }

    
    public function notifier()
    {
        return $this->belongsTo(Notifier::class);
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class)
            ->withPivot('read')
            ->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
