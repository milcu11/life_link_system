<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'blood_drive_id',
        'slot_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'slot_time' => 'datetime',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function bloodDrive(): BelongsTo
    {
        return $this->belongsTo(BloodDrive::class, 'blood_drive_id');
    }
}
