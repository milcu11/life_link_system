<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodDrive extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'capacity',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function confirmedAppointments(): HasMany
    {
        return $this->appointments()->where('status', 'confirmed');
    }

    public function pendingAppointments(): HasMany
    {
        return $this->appointments()->where('status', 'pending');
    }
}
