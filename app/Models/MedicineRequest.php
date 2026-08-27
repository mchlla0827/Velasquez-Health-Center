<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicineRequest extends Model
{
    // âœ… GAMITIN NATIN ANG EXISTING TABLE MO
    protected $table = 'restock_requests';

    protected $fillable = [
        'medicine_id',
        'supplier_id',
        'quantity_requested', // <-- Pangalan ng column mo
        'requested_by',
        'approved_by',
        'approved_at',
        'status',
        'reason',             // <-- Pangalan ng column mo (notes = reason)
        'physician_notes',
        'responsibility_center_code',
        'ris_number',
        'date_prepared',
        'unit',
        'batch',
        'expiry',
    ];

    // âœ… Koneksyon: Gamot
    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    // âœ… Koneksyon: Sino nag-request
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // âœ… Koneksyon: Sino nag-approve
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}