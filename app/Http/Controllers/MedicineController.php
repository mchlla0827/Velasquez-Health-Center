<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\DispensingRecord;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function index()
    {
        $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');
        if (!in_array($role, ['admin', 'nurse', 'bhw'])) abort(403);
        
        // ✅ Calculate REAL stock from batches + sync column
        $medicines = Medicine::with('batches')->get()->map(function ($med) {
            $realStock = $med->batches->sum('quantity');
            // Sync BOTH column names so everything works
            $med->stock = $realStock;
            $med->current_stock = $realStock;
            $med->save();
            return $med;
        });
    
        return view('medicine.inventory', [
            'medicines' => $medicines,
            'userName' => Auth::user()->name,
            'role' => Auth::user()->role,
            'canAddMedicine' => (Auth::user()->role == 'admin' || Auth::user()->role == 'nurse'),
        ]);
    }

public function dispenseForm()
{
    $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');
    $userName = session('admin_name') ?? Auth::user()->name ?? 'Staff';

    if (!in_array($role, ['admin', 'nurse'])) {
        abort(403);
    }

    // 1. Fetch Medicines
    $medicines = Medicine::with('batches')
        ->orderBy('name')
        ->get();

    // 2. Fetch Patients
    $patients = Patient::orderBy('patient_id', 'DESC')
        ->get();

    // 3. Fetch Dispensing History
    $dispensingHistory = DispensingRecord::with(['patient', 'medicine'])
        ->orderBy('dispense_date', 'DESC')
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

    // DYNAMIC VIEW: Load admin.dispense for admin, nurse.dispense for nurse
    $viewPath = ($role === 'admin') ? 'admin.dispense' : 'nurse.dispense';

    return view($viewPath, compact(
        'medicines',
        'patients',
        'dispensingHistory',
        'role',
        'userName'
    ));
}

public function dispenseSave(Request $request)
{
    $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');

    if (!in_array($role, ['admin', 'nurse'])) {
        abort(403);
    }

    $validated = $request->validate([
        'patient_id'    => 'required|exists:patients,id',
        'patient_ptn'   => 'required|string',
        'family_no'     => 'required|string',
        'barangay'      => 'required|string',
        'date'          => 'required|date',
        'patient_name'  => 'required|string',
        'age'           => 'required|integer',
        'sex'           => 'required|string',
        'address'       => 'required|string',
        'philhealth_no' => 'nullable|string',
        'diagnosis'     => 'required|string',
        'medicine_id'   => 'required|exists:medicines,id',
        'quantity'      => 'required|integer|min:1',
        'unit'          => 'required|string',
        'dispensed_by'  => 'required|string'
    ]);

    DB::beginTransaction();

    try {
        $medicine = Medicine::findOrFail($validated['medicine_id']);



        // FEFO Batch Retrieval (Ignores expired batches & picks earliest expiring stock first)
        $batches = $medicine->batches()
            ->where('quantity', '>', 0)
            ->where('expiry_date', '>=', now()->toDateString()) // 👈 ADD THIS LINE HERE
            ->orderBy('expiry_date')
            ->get();

        $totalStock = $batches->sum('quantity');

        if ($totalStock < $validated['quantity']) {
            DB::rollBack();

            return back()->with(
                'error',
                '❌ Not enough stock! Available: '.$totalStock
            );
        }

        // SAVE DISPENSING RECORD
$dispenseRecord = DispensingRecord::create([
    'patient_ptn'        => trim($validated['patient_ptn']),
    'family_no'          => $validated['family_no'],
    'barangay'           => $validated['barangay'],
    'dispense_date'      => $validated['date'],
    'patient_name'       => $validated['patient_name'],
    'age'                => $validated['age'],
    'sex'                => $validated['sex'],
    'address'            => $validated['address'],
    'philhealth_no'      => $validated['philhealth_no'],
    'diagnosis'          => $validated['diagnosis'],
    'medicine_id'        => $validated['medicine_id'],
    'quantity_dispensed' => $validated['quantity'],
    'unit'               => $validated['unit'],
    'dispensed_by'       => $validated['dispensed_by']
]);

        // DEDUCT FIFO BATCHES
        $remaining = $validated['quantity'];

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $take = min($batch->quantity, $remaining);

            $batch->quantity -= $take;
            $batch->save();

            StockTransaction::create([
                'medicine_id'  => $medicine->id,
                'batch_number' => $batch->batch_number,
                'expiry'       => $batch->expiry_date,
                'quantity'     => $take,
                'type'         => 'OUT',
                'remarks'      => 'Dispensed to patient: '.$validated['patient_name']
            ]);

            $remaining -= $take;
        }

        // UPDATE TOTAL STOCK
        $realStock = $medicine->batches()->sum('quantity');

        $medicine->stock = $realStock;
        $medicine->current_stock = $realStock;
        $medicine->save();

        DB::commit();

        // DYNAMIC REDIRECT: Send user back to their respective route
        $redirectRoute = ($role === 'admin') ? 'admin.dispense' : 'nurse.dispense';

        return redirect()
            ->route($redirectRoute)
            ->with('success', '✅ Medicine dispensed successfully.');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with(
            'error',
            '❌ '.$e->getMessage()
        );
    }
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'brand' => 'required|string',
            'dosage_form' => 'required|string',
            'dosage_strength' => 'required|string',
            'unit' => 'required|string',
        ]);

        $exists = Medicine::where('name', $request->name)
            ->where('brand', $request->brand)
            ->where('dosage_strength', $request->dosage_strength)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'duplicate' => true,
                'message' => 'Duplicate entry — this medicine already exists'
            ]);
        }

        // ✅ Initialize BOTH columns
        $validated['stock'] = 0;
        $validated['current_stock'] = 0;
        Medicine::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medicine saved successfully'
        ]);
    }
}