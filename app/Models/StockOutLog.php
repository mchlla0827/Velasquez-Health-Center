<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOutLog extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_RESOLVED = 'resolved';

    protected $fillable = [
        'medicine_id',
        'status',
        'resolved_at',
        'usable_stock_at_detection',
        'quantity_needed',
        'patients_affected',
        'duration_days',
        'remarks',
        'recorded_by',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}