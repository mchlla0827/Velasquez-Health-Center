<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
    'medicine_id',
    'dispensing_record_id',
    'batch_number',
    'expiry',
    'quantity',
    'patients_affected',
    'duration',
    'type',
    'remarks',
    'user_id',
    'notes',
    'created_at',
];

public function dispensingRecord()
{
    return $this->belongsTo(
        DispensingRecord::class,
        'dispensing_record_id'
    );
}

public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}