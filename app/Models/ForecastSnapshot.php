<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastSnapshot extends Model
{
    protected $fillable = [
        'medicine_id',
        'forecast_date',
        'target_start',
        'target_end',
        'forecasted_demand',
        'suggested_reorder_qty',
        'usable_stock',
        'forecast_method',
        'confidence',
        'moving_average',
        'trend_adjustment',
        'actual_demand',
        'error',
        'absolute_error',
        'absolute_percentage_error',
        'evaluated_at',
        'review_decision',
        'reviewed_quantity',
        'review_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'target_start' => 'date',
        'target_end' => 'date',
        'evaluated_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'trend_adjustment' => 'float',
        'absolute_percentage_error' => 'float',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}