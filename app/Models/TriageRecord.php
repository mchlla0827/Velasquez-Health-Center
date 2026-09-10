<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriageRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'service_type',
        'risk_level',
        'triage_level',
        'risk_score',
        'risk_factors',
        'registered_by',
        'status',
        'temp',
        'bp',
        'weight',
        'height',
        'symptoms',
        'queue_number'
    ];

    protected $casts = [
        'risk_factors' => 'array',
    ];

    // Patient who received the triage
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    // Healthcare worker who registered the triage
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by', 'id');
    }

    public static function generateQueueNumber()
    {
        $today = now()->format('Y-m-d');

        $countToday = self::whereDate('created_at', $today)->count();

        return $countToday + 1;
    }
}