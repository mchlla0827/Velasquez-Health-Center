<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Batch;
use App\Models\DispensingRecord; // ✅ Import the DispensingRecord model

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'dosage_form',
        'dosage_strength',
        'unit',
    ];

    /**
     * One Medicine has many Batches
     */
    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * ✅ FIXED: One Medicine has many Dispensing Records
     * This fixes the BadMethodCallException in the ForecastController
     */
    public function dispensingRecords(): HasMany
    {
        return $this->hasMany(DispensingRecord::class);
    }

    /**
     * TOTAL STOCK (GLOBAL ACCESSOR)
     * - Automatically usable as: $medicine->total_stock
     * - Safe & consistent across system
     */
    public function getTotalStockAttribute()
    {
        return $this->batches()->sum('quantity');
    }

    /**
     * Latest batch (for display / modal / stock card)
     */
    public function latestBatch()
    {
        return $this->hasOne(Batch::class)->latestOfMany();
    }

    /**
     * OPTIONAL (IMPROVED): Cached relationship version for performance
     * Use this only if you eager load batches
     */
    public function totalStockFromRelation()
    {
        return $this->batches->sum('quantity');
    }
}