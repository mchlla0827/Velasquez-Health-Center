<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriageRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'service_type',
        'risk_level',
        'triage_level',   // Added to match controller
        'status',         // Added to match controller ('waiting')
        'registered_by',  // Added to match controller (Auth::id())
        'temp',
        'bp',
        'weight',
        'height',
        'symptoms'
    ];

    // This creates the relationship back to the patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }


    public static function generateQueueNumber()
    {
        // Get today's date (YYYY-MM-DD)
        $today = now()->format('Y-m-d');

        // Count how many records we have TODAY
        $countToday = self::whereDate('created_at', $today)->count();

        // Next number = count + 1
        return $countToday + 1;
    }

}