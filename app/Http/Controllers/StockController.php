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
                'message' => '❌ Medicine records could not be resolved.'
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

                $realTotal = Batch::where('medicine_id', $medicine->id)->sum('quantity');
                $medicine->stock = $realTotal;
                $medicine->current_stock = $realTotal;
                $medicine->save();

                return response()->json([
                    'success' => true,
                    'message' => '✅ Stock added successfully! Inventory updated.'
                ]);
            });

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return response()->json(['errors' => ['batch_number' => ['This batch number already exists.']]], 422);
            }
            
            return response()->json([
                'success' => false,
                'message' => '❌ Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Render the Stock-Out Dashboard View (Shared between Admin & Nurse).
     */
    public function stockout()
    {
        // 1. Role & Permission Check
        $role = strtolower(session('admin_role') ?? auth()->user()->role ?? 'bhw');
        $canView = in_array($role, ['admin', 'nurse']);

        if (!$canView) {
            if ($role === 'bhw') return redirect()->route('bhw.inventory')->with('error', 'Access Denied.');
            if ($role === 'doctor') return redirect()->route('doctor.inventory')->with('error', 'Access Denied.');
            return redirect()->route('login');
        }

        // 2. Dynamic Role Color Assignment
        $roleColor = match($role) {
            'admin' => '#9333EA',
            'nurse' => '#10B981',
            default => '#6B7280'
        };

        // 3. Fetch Stock-Out Records (Optimized Eager Loading)
        $stockOutRecords = StockTransaction::with('medicine')
            ->where('type', 'STOCKOUT_INCIDENT')
            ->orderByDesc('created_at')
            ->get();

        // 4. Calculate Summary Metrics for Cards
        $totalStockOut    = $stockOutRecords->count();
        $patientsAffected = $stockOutRecords->sum('patients_affected');
        $avgDuration      = $totalStockOut > 0 ? round($stockOutRecords->avg('duration'), 1) : 0;

        // 5. Top 3 Most Frequent Shortages
        $mostFrequent = Medicine::selectRaw(
            'medicines.id, medicines.name,
               COUNT(DISTINCT DATE(stock_transactions.created_at)) as count,
               MAX(stock_transactions.created_at) as last_date'
        )
           ->join('stock_transactions', 'medicines.id', '=', 'stock_transactions.medicine_id')
           ->where('stock_transactions.type', 'STOCKOUT_INCIDENT')
        ->groupBy('medicines.id', 'medicines.name')
        ->orderByDesc('count')
        ->take(3)
        ->get();

        // 6. Monthly Trend for Chart.js (Past 6 Months)
        $monthlyTrend = StockTransaction::selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count")
            ->where('type', 'STOCKOUT_INCIDENT')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) ASC')
            ->get();

        // Modal Dropdown Data & User Info
        $medicinesList = Medicine::orderBy('name', 'asc')->get(['id', 'name']);
        $userName = Auth::user()->name ?? session('admin_name') ?? session('user_name') ?? ucfirst($role);

        // 7. Render Blade View
        return view('admin.stockout', compact(
            'role',
            'roleColor',
            'userName',
            'totalStockOut',
            'patientsAffected',
            'avgDuration',
            'mostFrequent',
            'monthlyTrend',
            'stockOutRecords',
            'medicinesList'
        ));
    }

    /**
     * Store new Stock-Out submission from the modal form.
     */
    public function storeStockOut(Request $request)
    {
        $validated = $request->validate([
            'medicine_id'       => 'required|exists:medicines,id',
            'quantity_needed'   => 'required|integer|min:1',
            'patients_affected' => 'required|integer|min:0',
            'duration'          => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $medicine = Medicine::findOrFail($validated['medicine_id']);

            StockTransaction::create([
                'medicine_id'       => $medicine->id,
                'batch_number'      => 'STOCKOUT',
                'expiry'            => today()->toDateString(),
                'type'              => 'STOCKOUT_INCIDENT',
                'quantity'          => $validated['quantity_needed'],
                'patients_affected' => $validated['patients_affected'],
                'duration'          => $validated['duration'],
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Stock-out incident logged successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to log stock-out: ' . $e->getMessage());
        }
    }
}