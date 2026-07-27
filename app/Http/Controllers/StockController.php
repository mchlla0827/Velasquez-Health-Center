<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\StockTransaction;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth; // <-- ITO ANG KULANG

class StockController extends Controller
{
    // ✅ FINAL FIX: Adds stock, syncs columns, works 100%
    public function storeIn(Request $request)
    {
        // ✅ ROLE CHECK: Admin & Nurse lang ang pwedeng magdagdag ng stock
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


    public function stockout()
    {
        // ==================================================
        // ✅ ROLE & PERMISSION CHECK (PAREHO SA FORECAST)
        // ==================================================
        $role = strtolower(session('admin_role') ?? auth()->user()->role ?? 'bhw');

        // ✅ ADMIN & NURSE LANG ANG PWEDENG MAKITA ANG STOCK-OUT PAGE
        $canView = in_array($role, ['admin', 'nurse']);
        
        if (!$canView) {
            // Kung Bawal: Ibalik sa tamang dashboard/inventory
            if ($role === 'bhw') return redirect()->route('bhw.inventory')->with('error', 'Access Denied.');
            if ($role === 'doctor') return redirect()->route('doctor.inventory')->with('error', 'Access Denied.');
            return redirect()->route('login');
        }

        // ==================================================
        // ✅ ORIGINAL LOGIC MO (WALANG BINAGO DITO)
        // ==================================================
        $totalStockOut = StockTransaction::where('type', 'OUT')
            ->whereRaw("
                (
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'IN' AND created_at <= stock_transactions.created_at)
                    -
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'OUT' AND created_at <= stock_transactions.created_at)
                ) = 0
            ")
            ->count();

        $patientsAffected = 0;
        $avgDuration = 0;

        $mostFrequent = Medicine::selectRaw(
                'medicines.id, medicines.name, 
                 COUNT(DISTINCT DATE(stock_transactions.created_at)) as count, 
                 MAX(stock_transactions.created_at) as last_date'
            )
            ->join('stock_transactions', 'medicines.id', '=', 'stock_transactions.medicine_id')
            ->where('stock_transactions.type', 'OUT')
            ->whereRaw("
                (
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'IN' AND created_at <= stock_transactions.created_at)
                    -
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'OUT' AND created_at <= stock_transactions.created_at)
                ) = 0
            ")
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('count')
            ->take(3)
            ->get();

        $monthlyTrend = DB::table('stock_transactions')
            ->selectRaw("DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as count")
            ->where('type', 'OUT')
            ->where('created_at', '>=', now()->subMonths(6))
            ->whereRaw("
                (
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'IN' AND created_at <= stock_transactions.created_at)
                    -
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'OUT' AND created_at <= stock_transactions.created_at)
                ) = 0
            ")
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) ASC')
            ->get();

        $stockOutRecords = StockTransaction::with('medicine')
            ->where('type', 'OUT')
            ->whereRaw("
                (
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'IN' AND created_at <= stock_transactions.created_at)
                    -
                    (SELECT COALESCE(SUM(quantity),0) FROM stock_transactions 
                     WHERE medicine_id = stock_transactions.medicine_id AND type = 'OUT' AND created_at <= stock_transactions.created_at)
                ) = 0
            ")
            ->orderByDesc('created_at')
            ->get();

        // ==================================================
        // ✅ TAMANG VIEW NA IPAPAKITA
        // ==================================================
        if ($role === 'admin') {
            return view('admin.stockout', compact(
                'totalStockOut', 'patientsAffected', 'avgDuration', 'mostFrequent', 'monthlyTrend', 'stockOutRecords'
            ));
        } 
        elseif ($role === 'nurse') {
            // ✅ DAGDAGAN NATIN NG role at userName para gumana ang header mo
            $userName = Auth::user()->name ?? session('user_name') ?? session('admin_name') ?? 'Nurse';

            return view('nurse.stockout', compact(
                'role', 'userName', // <-- IDINAGDAG ITO
                'totalStockOut', 'patientsAffected', 'avgDuration', 'mostFrequent', 'monthlyTrend', 'stockOutRecords'
            ));
        }
    }
}