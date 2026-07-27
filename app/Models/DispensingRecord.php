<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispensingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
    'patient_ptn', // ✅ NEW — very important
    'family_no','barangay','dispense_date','patient_name','age','sex',
    'address','philhealth_no','diagnosis','medicine_id','quantity_dispensed',
    'unit','dispensed_by'
];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}