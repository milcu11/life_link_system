<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'blood_type',
        'quantity',
        'urgency_level',
        'location',
        'latitude',
        'longitude',
        'status',
        'notes',
        'patient_name',
        'contact_person',
        'contact_phone',
        'needed_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'needed_by' => 'datetime',
    ];

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'request_id');
    }

    public function matchings(): HasMany
    {
        return $this->hasMany(Matching::class, 'request_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'request_id');
    }

    public function getCompatibleDonorBloodTypes(): array
    {
        $compatibility = [
            'A+' => ['A+', 'O+'],
            'A-' => ['A+', 'A-', 'O+', 'O-'],
            'B+' => ['B+', 'O+'],
            'B-' => ['B+', 'B-', 'O+', 'O-'],
            'AB+' => ['AB+', 'O+'],
            'AB-' => ['AB+', 'AB-', 'O+', 'O-'],
            'O+' => ['O+'],
            'O-' => ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'],
        ];

        return $compatibility[$this->blood_type] ?? [];
    }

    public function isUrgent(): bool
    {
        return in_array($this->urgency_level, ['high', 'critical']);
    }
}