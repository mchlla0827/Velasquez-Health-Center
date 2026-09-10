<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Services\InventoryStockService;
use App\Services\MedicineForecastService;
use Illuminate\Http\Request;
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

        $canManageForecast = true;

        $roleColor = match ($role) {
            'admin' => '#9333EA',
            'nurse' => '#10B981',
            default => '#6B7280'
        };

        $stockService = new InventoryStockService();
        $forecastService = new MedicineForecastService($stockService);

        $medicines = Medicine::all();

        $forecastData = [];
        $forecastChart = [];

        $shortageCount = 0;
        $lowStockCount = 0;
        $expiringSoonCount = 0;
        $insufficientDataCount = 0;
        $criticalItemGlobal = null;

        // ==================================================
        // EVALUATE EVERY MEDICINE THROUGH THE CENTRAL SERVICE
        // ==================================================
        foreach ($medicines as $med) {
            $result = $forecastService->evaluate($med);
            $expiringSoon = $stockService->expiringSoon($med, 180);
            $stockOutContext = $forecastService->getStockOutContext($med);
            $requestContext = $forecastService->getRequestContext($med);

            $forecastChart[] = [
                'medicine' => $med->name,
                'medicine_id' => $med->id,
                'historical' => [$result['period3'], $result['period2'], $result['period1']],
                'forecast' => $result['forecast_available'] ? $result['estimated_demand'] : null,
            ];

            if ($result['is_shortage']) {
                $shortageCount++;
                $criticalItemGlobal = $criticalItemGlobal ?? $med;
            }
            if ($result['is_low']) {
                $lowStockCount++;
            }
            if ($expiringSoon > 0) {
                $expiringSoonCount++;
            }
            if (!$result['forecast_available']) {
                $insufficientDataCount++;
            }

            $forecastData[] = array_merge($result, [
                'expiring_soon_qty' => $expiringSoon,
                'stock_out_context' => $stockOutContext,
                'request_context' => $requestContext,
            ]);
        }

        // ==================================================
        // AI RECOMMENDATION BAR
        // ==================================================
        if ($shortageCount > 0) {
            $medicineName = $criticalItemGlobal ? $criticalItemGlobal->name : 'one or more medicines';
            $aiTitle = 'Potential Stock Shortage Detected';
            $aiMessage = "The 3-month moving average forecast predicts that {$medicineName} may fall below usable stock within the next 30 days.";
            $aiAction = "Review recommended restock quantities and prioritize replenishment.";
            $aiStatus = 'warning';
        } elseif ($lowStockCount > 0) {
            $aiTitle = 'Low Stock Alert';
            $aiMessage = "Some medicines are approaching or below their configured low-stock threshold.";
            $aiAction = "Continue monitoring inventory and prepare replenishment if demand increases.";
            $aiStatus = 'warning';
        } else {
            $aiTitle = 'Inventory Status is Healthy';
            $aiMessage = "Based on the 3-month moving average forecast, no medicine is expected to experience a stock shortage within the next 30 days.";
            $aiAction = "Maintain current inventory levels and continue routine monitoring.";
            $aiStatus = 'success';
        }

        return view('admin.forecast', compact(
            'role',
            'roleColor',
            'userName',
            'canManageForecast',
            'forecastData',
            'forecastChart',
            'shortageCount',
            'lowStockCount',
            'expiringSoonCount',
            'insufficientDataCount',
            'criticalItemGlobal',
            'aiTitle',
            'aiMessage',
            'aiAction',
            'aiStatus'
        ));
    }

    /**
     * AI Insights - JSON feed of decision-support insights, generated
     * from the same centralized MedicineForecastService used by the
     * Dashboard and Forecast page, so numbers never contradict.
     */
    public function insights(\App\Services\MedicineForecastService $forecastService)
    {
        return response()->json([
            'insights' => $forecastService->generateInsights(),
        ]);
    }
}