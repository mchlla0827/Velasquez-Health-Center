<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\StockTransaction;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Edit/update an existing batch (correct quantity, batch number, expiry, or remarks).
     * The 'quantity' field represents the EXACT remaining amount, not a delta -
     * this method calculates the difference and logs it as a StockTransaction
     * (IN if corrected upward, OUT if corrected downward) for audit purposes.
     */
    public function updateStock(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? '');
        if (!in_array($role, ['admin', 'nurse'])) {
            abort(403, 'Unauthorized: Admin or Nurse only');
        }

        $validated = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'batch_id' => 'required|exists:batches,id',
            'batch_number' => 'required|string|max:255',
            'expiry' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $batch = Batch::where('id', $validated['batch_id'])
                ->where('medicine_id', $validated['medicine_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $oldQuantity = (int) $batch->quantity;
            $newQuantity = (int) $validated['quantity'];
            $diff = $newQuantity - $oldQuantity;

            $batch->update([
                'batch_number' => $validated['batch_number'],
                'expiry_date' => $validated['expiry'],
                'quantity' => $newQuantity,
                'remarks' => $validated['remarks'] ?? $batch->remarks,
            ]);

            if ($diff !== 0) {
                StockTransaction::create([
                    'medicine_id' => $validated['medicine_id'],
                    'batch_number' => $validated['batch_number'],
                    'expiry' => $validated['expiry'],
                    'quantity' => abs($diff),
                    'type' => $diff > 0 ? 'IN' : 'OUT',
                    'remarks' => 'Manual stock correction'
                        . (!empty($validated['remarks']) ? ': ' . $validated['remarks'] : ''),
                    'user_id' => $user->id,
                ]);
            }

            app(\App\Services\InventoryStockService::class)->syncLegacyColumns($batch->medicine);
        });

        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    /**
     * Store new incoming stock (Stock-In) and sync batches/inventory totals.
     */
    public function storeIn(Request $request)
    {
        // ROLE CHECK: Admin & Nurse only
        $role = strtolower(session('admin_role') ?? auth()->user()->role ?? 'bhw');
        if (!in_array($role, ['admin', 'nurse'])) {
            abort(403, 'Access Denied: You are not allowed to add stock.');
        }

        $request->validate([
            'medicine_id'  => 'required|exists:medicines,id',
            'batch_number' => 'required',
            'expiry'       => 'required|date',
            'quantity'     => 'required|numeric|min:1',
            'remarks'      => 'nullable|string',
        ]);

        $medicine = Medicine::find($request->medicine_id);

        if (!$medicine) {
            return response()->json([
                'success' => false,
                'message' => 'Medicine records could not be resolved.'
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $medicine) {
                
                StockTransaction::create([
                    'medicine_id'  => $medicine->id,
                    'batch_number' => $request->batch_number,
                    'expiry'       => $request->expiry,
                    'quantity'     => $request->quantity,
                    'type'         => 'IN',
                    'remarks'      => $request->remarks,
                ]);

                $existingBatch = Batch::where('medicine_id', $medicine->id)
                    ->where('batch_number', $request->batch_number)
                    ->first();

                if ($existingBatch) {
                    $existingBatch->increment('quantity', $request->quantity);
                    $existingBatch->expiry_date = $request->expiry;
                    $existingBatch->remarks = $request->remarks;
                    $existingBatch->save();
                } else {
                    Batch::create([
                        'medicine_id'  => $medicine->id,
                        'batch_number' => $request->batch_number,
                        'expiry_date'  => $request->expiry,
                        'quantity'     => $request->quantity,
                        'remarks'      => $request->remarks,
                    ]);
                }

                app(\App\Services\InventoryStockService::class)->syncLegacyColumns($medicine);

                return response()->json([
                    'success' => true,
                    'message' => 'Stock added successfully! Inventory updated.'
                ]);
            });

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['errors' => ['batch_number' => ['This batch number already exists.']]], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Render the Stock-Out Dashboard View (Shared between Admin & Nurse).
     */
    public function stockout()
    {
        // ==================================================
        // ROLE & PERMISSION CHECK
        // ==================================================
        $role = strtolower(session('admin_role') ?? auth()->user()->role ?? 'bhw');
        $canView = in_array($role, ['admin', 'nurse']);

        if (!$canView) {
            if ($role === 'bhw') return redirect()->route('bhw.inventory')->with('error', 'Access Denied.');
            if ($role === 'doctor') return redirect()->route('doctor.inventory')->with('error', 'Access Denied.');
            return redirect()->route('login');
        }

        // ==================================================
        // DYNAMIC ROLE COLOR ASSIGNMENT
        // ==================================================
        $roleColor = match($role) {
            'admin' => '#9333EA', // Purple
            'nurse' => '#10B981', // Emerald Green
            default => '#6B7280'  // Gray
        };
        $userName = Auth::user()->name ?? session('admin_name') ?? session('user_name') ?? ucfirst($role);

        // ==================================================
        // ACTIVE STOCK-OUTS (usable stock currently = 0, auto-detected)
        // ==================================================
        $activeStockOuts = \App\Models\StockOutLog::with('medicine')
            ->where('status', \App\Models\StockOutLog::STATUS_ACTIVE)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($log) {
                $medicine = $log->medicine;
                $affected = $this->affectedPatientsForStockOut($log);

                return (object) [
                    'id' => $log->id,
                    'medicine_name' => $medicine->name ?? 'Unknown Medicine',
                    'usable_stock' => $medicine ? app(\App\Services\InventoryStockService::class)->usableStock($medicine) : 0,
                    'stockout_date' => $log->created_at,
                    'days_out' => (int) floor($log->created_at->diffInDays(now())),
                    'affected_count' => $affected->count(),
                ];
            });

        // ==================================================
        // STOCK-OUT HISTORY (resolved, auto-detected)
        // ==================================================
        $stockOutHistory = \App\Models\StockOutLog::with('medicine')
            ->where('status', \App\Models\StockOutLog::STATUS_RESOLVED)
            ->orderByDesc('resolved_at')
            ->get()
            ->map(function ($log) {
                $affected = $this->affectedPatientsForStockOut($log);

                return (object) [
                    'id' => $log->id,
                    'medicine_name' => $log->medicine->name ?? 'Unknown Medicine',
                    'stockout_date' => $log->created_at,
                    'resolved_date' => $log->resolved_at,
                    'duration_days' => $log->resolved_at ? (int) floor($log->created_at->diffInDays($log->resolved_at)) : null,
                    'affected_count' => $affected->count(),
                ];
            });

        // ==================================================
        // SUMMARY METRICS
        // ==================================================
        $totalStockOut = \App\Models\StockOutLog::count();
        $activeCount = $activeStockOuts->count();
        $patientsAffected = (int) \App\Models\StockOutLog::get()
            ->sum(fn($log) => $this->affectedPatientsForStockOut($log)->count());
        $avgDuration = $stockOutHistory->whereNotNull('duration_days')->avg('duration_days');
        $avgDuration = $avgDuration ? round($avgDuration, 1) : 0;

        return view('admin.stockout', compact(
            'role', 'roleColor', 'userName',
            'activeStockOuts', 'stockOutHistory',
            'totalStockOut', 'activeCount', 'patientsAffected', 'avgDuration'
        ));
    }

    /**
     * Full details of one stock-out event, including the real
     * per-patient impact breakdown computed from dispensing records.
     */
    public function stockoutDetails($id)
    {
        $log = \App\Models\StockOutLog::with('medicine')->findOrFail($id);
        $affected = $this->affectedPatientsForStockOut($log);

        return response()->json([
            'medicine_name' => $log->medicine->name ?? 'Unknown Medicine',
            'status' => $log->status,
            'stockout_date' => $log->created_at->format('M d, Y h:i A'),
            'resolved_date' => $log->resolved_at ? $log->resolved_at->format('M d, Y h:i A') : null,
            'duration_days' => $log->resolved_at
                ? (int) floor($log->created_at->diffInDays($log->resolved_at)) : (int) floor($log->created_at->diffInDays(now())),
            'affected_patients' => $affected->map(fn($d) => [
                'patient_name' => $d->patient_name,
                'prescribed' => $d->prescription->quantity ?? null,
                'dispensed' => $d->quantity_dispensed,
                'unfulfilled' => $d->unfulfilled_quantity,
                'status' => $d->status,
                'date' => $d->dispense_date,
            ]),
        ]);
    }

    /**
     * Patients affected by a specific stock-out event: dispensing
     * attempts for that medicine during the stock-out window where
     * the prescription could not be fully fulfilled.
     */
    private function affectedPatientsForStockOut(\App\Models\StockOutLog $log)
    {
        $query = \App\Models\DispensingRecord::with('prescription')
            ->where('medicine_id', $log->medicine_id)
            ->whereIn('status', ['partially_dispensed', 'not_dispensed_stockout'])
            ->where('dispense_date', '>=', $log->created_at->toDateString());

        if ($log->resolved_at) {
            $query->where('dispense_date', '<=', $log->resolved_at->toDateString());
        }

        return $query->get();
    }
}