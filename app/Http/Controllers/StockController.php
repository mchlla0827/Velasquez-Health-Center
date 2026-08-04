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
        // ==================================================
        // ✅ 1. ROLE & PERMISSION CHECK
        // ==================================================
        $role = strtolower(session('admin_role') ?? auth()->user()->role ?? 'bhw');
        $canView = in_array($role, ['admin', 'nurse']);

        if (!$canView) {
            if ($role === 'bhw') return redirect()->route('bhw.inventory')->with('error', 'Access Denied.');
            if ($role === 'doctor') return redirect()->route('doctor.inventory')->with('error', 'Access Denied.');
            return redirect()->route('login');
        }

        // ==================================================
        // ✅ 2. DYNAMIC ROLE COLOR ASSIGNMENT
        // ==================================================
        $roleColor = match($role) {
            'admin' => '#9333EA', // Purple
            'nurse' => '#10B981', // Emerald Green
            default => '#6B7280'  // Gray
        };

        // Correlated subquery condition for zero stock
        $zeroStockCondition = "
            (
                (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                 WHERE medicine_id = stock_transactions.medicine_id AND type = 'IN' AND created_at <= stock_transactions.created_at)
                -
                (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                 WHERE medicine_id = stock_transactions.medicine_id AND type = 'OUT' AND created_at <= stock_transactions.created_at)
            ) = 0
        ";

        // ==================================================
        // ✅ 3. METRIC CALCULATIONS
        // ==================================================
        $totalStockOut = StockTransaction::where('type', 'OUT')
            ->whereRaw($zeroStockCondition)
            ->count();

        $patientsAffected = 0; 
        $avgDuration = 0;

        // ==================================================
        // ✅ 4. MOST FREQUENT SHORTAGES (TOP 3)
        // ==================================================
        $mostFrequent = Medicine::selectRaw(
                'medicines.id, medicines.name, 
                 COUNT(DISTINCT DATE(stock_transactions.created_at)) as count, 
                 MAX(stock_transactions.created_at) as raw_last_date'
            )
            ->join('stock_transactions', 'medicines.id', '=', 'stock_transactions.medicine_id')
            ->where('stock_transactions.type', 'OUT')
            ->whereRaw($zeroStockCondition)
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('count')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->last_date = \Carbon\Carbon::parse($item->raw_last_date)->format('M d, Y');
                return $item;
            });

        // ==================================================
        // ✅ 5. MONTHLY TREND (PAST 6 MONTHS)
        // ==================================================
        $monthlyTrend = DB::table('stock_transactions')
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count")
            ->where('type', 'OUT')
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereRaw($zeroStockCondition)
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) ASC')
            ->get();

        // ==================================================
        // ✅ 6. DETAILED RECORDS LIST
        // ==================================================
        $stockOutRecords = StockTransaction::with('medicine')
            ->where('type', 'OUT')
            ->whereRaw($zeroStockCondition)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($record) {
                return (object)[
                    'id' => $record->id,
                    'date' => \Carbon\Carbon::parse($record->created_at)->format('Y-m-d'),
                    'medicine_name' => $record->medicine->name ?? 'Unknown Medicine',
                    'quantity_needed' => $record->quantity,
                    'patients_affected' => 0,
                    'duration' => 1,
                ];
            });

        // Fetch medicines list for Modal dropdown
        $medicinesList = Medicine::orderBy('name', 'asc')->get(['id', 'name']);

        // User Metadata
        $userName = Auth::user()->name ?? session('admin_name') ?? session('user_name') ?? ucfirst($role);

        // ==================================================
        // ✅ 7. RETURN SHARED VIEW ('admin.stockout') FOR BOTH ADMIN & NURSE
        // ==================================================
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
        // Validation
        $validated = $request->validate([
            'medicine_id'       => 'required|exists:medicines,id',
            'quantity_needed'   => 'required|integer|min:1',
            'patients_affected' => 'required|integer|min:0',
            'duration'          => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Find medicine record
            $medicine = Medicine::findOrFail($validated['medicine_id']);

            // Create stock-out transaction
            StockTransaction::create([
                'medicine_id' => $medicine->id,
                'type'        => 'OUT',
                'quantity'    => $validated['quantity_needed'],
                'user_id'     => Auth::id() ?? session('user_id'),
                'notes'       => "Stock-Out Logged. Duration: {$validated['duration']} days. Patients Affected: {$validated['patients_affected']}",
                'created_at'  => now(),
            ]);

            // Update medicine stock if column exists
            if (isset($medicine->quantity)) {
                $medicine->quantity = max(0, $medicine->quantity - $validated['quantity_needed']);
                $medicine->save();
            }

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