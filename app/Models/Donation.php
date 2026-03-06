<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'request_id',
        'donation_date',
        'quantity',
        'status',
        'notes',
        'donation_center',
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class, 'request_id');
    }
}