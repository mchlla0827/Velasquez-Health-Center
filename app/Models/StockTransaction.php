<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
    'medicine_id',
    'batch_number',
    'expiry',
    'quantity',
    'type',
    'remarks',
    'user_id',      // ← Added
    'notes',        // ← Added
    'created_at',   // ← Added
    ];
}