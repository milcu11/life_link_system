<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'location',
        'latitude',
        'longitude',
        'password_reset_code',
        'password_reset_code_expires_at',
        'password_reset_last_sent_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'password_reset_code_expires_at' => 'datetime',
        'password_reset_last_sent_at' => 'datetime',
    ];

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }

    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class, 'hospital_id');
    }

    public function bloodInventory(): HasMany
    {
        return $this->hasMany(BloodInventory::class, 'hospital_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function bloodDrives(): HasMany
    {
        return $this->hasMany(BloodDrive::class, 'hospital_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'donor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHospital(): bool
    {
        return $this->role === 'hospital';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }
}