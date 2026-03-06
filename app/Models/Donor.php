<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_type',
        'phone',
        'address',
        'latitude',
        'longitude',
        'is_available',
        'last_donation_date',
        'date_of_birth',
        'gender',
        'emergency_contact',
        'medical_conditions',
        'verification_document_path',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'last_donation_date' => 'date',
        'date_of_birth' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function matchings(): HasMany
    {
        return $this->hasMany(Matching::class);
    }

    public function canDonate(): bool
    {
        if (!$this->is_available) {
            return false;
        }

        if (!$this->last_donation_date) {
            return true;
        }

        // Donors must wait at least 56 days (8 weeks) between donations
        return $this->last_donation_date->addDays(56)->isPast();
    }

    public function getCompatibleBloodTypes(): array
    {
        $compatibility = [
            'A+' => ['A+', 'A-', 'O+', 'O-'],
            'A-' => ['A-', 'O-'],
            'B+' => ['B+', 'B-', 'O+', 'O-'],
            'B-' => ['B-', 'O-'],
            'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'AB-' => ['A-', 'B-', 'AB-', 'O-'],
            'O+' => ['O+', 'O-'],
            'O-' => ['O-'],
        ];

        return $compatibility[$this->blood_type] ?? [];
    }
}