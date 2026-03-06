<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorAppeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'message',
        'attachment_path',
        'status',
        'admin_note',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
