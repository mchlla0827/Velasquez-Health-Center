<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationPrescription extends Model
{
    const OUTCOME_AWAITING_STOCK = 'awaiting_stock';
    const OUTCOME_REFERRED = 'referred_other_pharmacy';
    const OUTCOME_CANCELLED = 'cancelled';

    protected $fillable = [
        'consultation_id',
        'medicine_id',
        'medicine_name',
        'dosage',
        'frequency',
        'duration',
        'quantity',
        'status',
        'fulfillment_outcome',
        'quantity_dispensed',
        'unfulfilled_quantity',
        'unit',
        'instructions',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function dispensingRecords()
    {
        return $this->hasMany(DispensingRecord::class, 'prescription_id');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}