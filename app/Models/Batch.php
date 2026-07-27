<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Medicine;

class Batch extends Model
{
    protected $fillable = [
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity',
        'remarks',
    ];

    /**
     * A Batch belongs to a Medicine
     */
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    /**
     * OPTIONAL: Check if batch is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expiry_date < now()->toDateString();
    }

    /**
     * OPTIONAL: Status helper (for UI badges)
     */
    public function getStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'OUT_OF_STOCK';
        }

        if ($this->expiry_date < now()->addDays(30)->toDateString()) {
            return 'NEAR_EXPIRY';
        }

        return 'NORMAL';
    }
}