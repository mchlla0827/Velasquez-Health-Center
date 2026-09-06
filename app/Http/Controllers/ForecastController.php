<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ForecastController extends Controller
{
    public function index()
    {
        // ==================================================
        // ROLE & USER CHECK
        // ==================================================
        $role = strtolower(session('admin_role') ?? Auth::user()->role ?? 'bhw');

        $userName = Auth::user()->name 
            ?? session('admin_name') 
            ?? session('user_name') 
            ?? ucfirst($role);

        // ==================================================
        // FORECAST ACCESS: ADMIN & NURSE ONLY
        // ==================================================
        $canViewForecast = in_array($role, ['admin', 'nurse']);

        if (!$canViewForecast) {
            return redirect()
                ->route('bhw.inventory')
                ->with('error', 'Access Denied: You are not allowed to view Forecast.');
        }

        // Action permissions indicator
        $canManageForecast = true;

        // Dynamic badge accent color based on active role
        $roleColor = match($role) {
            'admin' => '#9333EA', // Purple
            'nurse' => '#10B981', // Emerald Green
            default => '#6B7280'  // Gray
        };

        // ==================================================
        // PREPARE DATE BOUNDARIES
        // ==================================================
        $now = Carbon::now();
        $oneMonthAgo = $now->copy()->subMonth();
        $twoMonthsAgo = $now->copy()->subMonths(2);
        $threeMonthsAgo = $now->copy()->subMonths(3);

        // Fetch medicines along with 3-month dispensing records (Eager Loading)
        $medicines = Medicine::with(['dispensingRecords' => function ($query) use ($threeMonthsAgo) {
            $query->where('dispense_date', '>=', $threeMonthsAgo);
        }])->get();

        $shortageCount = 0;
        $highDemandCount = 0;
        $restockCount = 0;

        $forecastData = [];
        $forecastChart = [];
        $criticalItemGlobal = null;

        // ==================================================
        // MOVING AVERAGE FORECAST CALCULATION
        // ==================================================
        foreach ($medicines as $med) {
            $records = $med->dispensingRecords;

            // Period 1: Last 30 days
            $period1 = $records->where('dispense_date', '>=', $oneMonthAgo)
                ->sum('quantity_dispensed');

            // Period 2: 30–60 days ago
            $period2 = $records->where('dispense_date', '>=', $twoMonthsAgo)
                ->where('dispense_date', '<', $oneMonthAgo)
                ->sum('quantity_dispensed');

            // Period 3: 60–90 days ago
            $period3 = $records->where('dispense_date', '>=', $threeMonthsAgo)
                ->where('dispense_date', '<', $twoMonthsAgo)
                ->sum('quantity_dispensed');

            /*
             * FIX: Always divide by the full 3-month window, not just the
             * number of non-zero periods. A month with zero dispensing is
             * still a real data point (it means demand was zero that month),
             * and should pull the average down — not be excluded from it.
             *
             * Previously, array_filter() stripped out zero-value periods,
             * so a medicine dispensed only once in 90 days (e.g. 30 units
             * in month 3, 0 in months 1 and 2) was averaged as 30 / 1 = 30
             * instead of the correct 30 / 3 = 10, artificially inflating
             * the forecasted demand and triggering false shortage alerts.
             */
            $hasAnyDispensingHistory = ($period1 + $period2 + $period3) > 0;

            if ($hasAnyDispensingHistory) {
                $estDemand = (int) round(($period1 + $period2 + $period3) / 3);
            } else {
                $estDemand = ($med->total_stock < 50) ? 50 : 0;
            }

            // Chart Payload
            $forecastChart[] = [
                'medicine' => $med->name,
                'medicine_id' => $med->id,
                'historical' => [$period3, $period2, $period1],
                'forecast' => $estDemand
            ];

            // Inventory Evaluation
            $currentStock = (int) $med->total_stock;
            $status = 'Stable Inventory';
            $statusClass = 'text-green-600';
            $recommendation = 'Maintain Current Stock';
            $criticalItem = null;

            if ($estDemand > 0 && $currentStock < $estDemand) {
                $status = 'Possible Shortage';
                $statusClass = 'text-red-600 font-bold';
                $suggestedQty = ($estDemand * 2) - $currentStock;
                $recommendation = 'Restock Immediately | Suggested: ' . max(0, $suggestedQty);

                $shortageCount++;
                $restockCount++;
                $criticalItem = $med;
                $criticalItemGlobal = $med;

            } elseif ($currentStock < 50) {
                $status = 'Low Stock Alert';
                $statusClass = 'text-yellow-600';
                $recommendation = 'Monitor Inventory';

                $highDemandCount++;
                $restockCount++;
            }

            $forecastData[] = [
                'medicine' => $med,
                'current_stock' => $currentStock,
                'est_demand' => $estDemand,
                'status' => $status,
                'status_class' => $statusClass,
                'recommendation' => $recommendation,
                'critical_item' => $criticalItem
            ];
        }

        // ==================================================
        // AI RECOMMENDATION BAR LOGIC
        // ==================================================
        if ($shortageCount > 0) {
            $medicineName = $criticalItemGlobal ? $criticalItemGlobal->name : 'one or more medicines';
            $aiTitle = 'Potential Stock Shortage Detected';
            $aiMessage = "The Moving Average Forecast predicts that {$medicineName} may fall below the required stock level within the next 30 days.";
            $aiAction = "Review recommended restock quantities and prioritize replenishment.";
            $aiStatus = 'warning';

        } elseif ($highDemandCount > 0) {
            $aiTitle = 'Low Stock Alert';
            $aiMessage = "Some medicines are approaching the minimum inventory threshold based on current stock levels.";
            $aiAction = "Continue monitoring inventory and prepare replenishment if demand increases.";
            $aiStatus = 'warning';

        } else {
            $aiTitle = 'Inventory Status is Healthy';
            $aiMessage = "Based on the Moving Average Forecast, no medicine is expected to experience stock shortage within the next 30 days.";
            $aiAction = "Maintain current inventory levels and continue routine monitoring.";
            $aiStatus = 'success';
        }

        // Return single shared view template for Admin & Nurse
        return view('admin.forecast', compact(
            'role',
            'roleColor',
            'userName',
            'canManageForecast',
            'forecastData',
            'forecastChart',
            'shortageCount',
            'highDemandCount',
            'restockCount',
            'criticalItemGlobal',
            'aiTitle',
            'aiMessage',
            'aiAction',
            'aiStatus'
        ));
    }
}