<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodInventory extends Model
{
    protected $table = 'blood_inventory';

    protected $fillable = [
        'hospital_id',
        'blood_type',
        'quantity',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function hospital()
    {
        return $this->belongsTo(User::class, 'hospital_id');
    }

    // Scope for low stock (less than 10 units)
    public function scopeLowStock($query)
    {
        return $query->where('quantity', '<', 10);
    }

    // Scope for expiring soon (within 7 days)
    public function scopeExpiringSoon($query)
    {
        return $query->where('expiration_date', '<=', now()->addDays(7));
    }
}
