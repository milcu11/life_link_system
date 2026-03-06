<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matching extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'request_id',
        'donor_id',
        'compatibility_score',
        'distance',
        'status',
        'notified_at',
        'responded_at',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class, 'request_id');
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}