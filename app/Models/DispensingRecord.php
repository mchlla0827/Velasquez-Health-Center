<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Medicine;

class DispensingRecord extends Model
{
    protected $fillable = [
        'patient_ptn',
        'family_no',
        'barangay',
        'dispense_date',
        'patient_name',
        'age',
        'sex',
        'address',
        'philhealth_no',
        'diagnosis',
        'medicine_id',
        'quantity_dispensed',
        'unit',
        'dispensed_by'
    ];

    public function patient()
    {
        return $this->belongsTo(
            Patient::class,
            'patient_ptn',
            'patient_id'
        );
    }

    public function medicine()
    {
        return $this->belongsTo(
            Medicine::class,
            'medicine_id',
            'id'
        );
    }
}