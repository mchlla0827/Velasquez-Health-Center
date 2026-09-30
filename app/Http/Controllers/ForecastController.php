<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Services\InventoryStockService;
use App\Services\MedicineForecastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ForecastHistoryExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $trendAppliedCount = 0;
        $seasonalReadyCount = 0;
        $lowConfidenceCount = 0;
        $criticalItemGlobal = null;

        // ==================================================
        // EVALUATE EVERY MEDICINE THROUGH THE CENTRAL SERVICE
        // ==================================================
        foreach ($medicines as $med) {
            $result = $forecastService->evaluate($med);
            $forecastService->saveForecastSnapshot(
    $med,
    $result
);
            $expiringSoon = $stockService->expiringSoon($med, 180);
            $stockOutContext = $forecastService->getStockOutContext($med);
            $requestContext = $forecastService->getRequestContext($med);

            $forecastChart[] = [
                'medicine' => $med->name,
                'medicine_id' => $med->id,
                'historical' => $result['monthly_history'],
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
            if ($result['trend_applied']) {
                $trendAppliedCount++;
            }
            if ($result['confidence'] === 'Low') {
                $lowConfidenceCount++;
            }
            if ($result['seasonal']['ready']) {
                $seasonalReadyCount++;
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
            $aiMessage = "The trend-adjusted moving average forecast predicts that {$medicineName} may fall below usable stock within the next 30 days.";
            $aiAction = "Review recommended restock quantities and prioritize replenishment.";
            $aiStatus = 'warning';
        } elseif ($lowStockCount > 0) {
            $aiTitle = 'Low Stock Alert';
            $aiMessage = "Some medicines are approaching or below their configured low-stock threshold.";
            $aiAction = "Continue monitoring inventory and prepare replenishment if demand increases.";
            $aiStatus = 'warning';
        } else {
            $aiTitle = 'Inventory Status is Healthy';
            $aiMessage = "Based on the trend-adjusted moving average forecast, no medicine is expected to experience a stock shortage within the next 30 days.";
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
            'trendAppliedCount',
            'seasonalReadyCount',
            'lowConfidenceCount',
            'criticalItemGlobal',
            'aiTitle',
            'aiMessage',
            'aiAction',
            'aiStatus'
        ));
    }

    /**
 * Forecast accuracy tracking using rolling-origin backtesting.
 */
public function accuracy(
    MedicineForecastService $forecastService
) {
    $role = strtolower(
        session('admin_role')
        ?? Auth::user()->role
        ?? 'bhw'
    );

    if (!in_array($role, ['admin', 'nurse'])) {
        return redirect()
            ->route('bhw.inventory')
            ->with(
                'error',
                'Access Denied: You are not allowed to view Forecast Accuracy.'
            );
    }

    $userName =
        Auth::user()->name
        ?? session('admin_name')
        ?? session('user_name')
        ?? ucfirst($role);

    $medicines =
        Medicine::orderBy('name')->get();

    $medicineResults = [];
    $allObservations = [];

    foreach ($medicines as $medicine) {

        $result =
            $forecastService
                ->backtestMedicine(
                    $medicine,
                    12
                );

        $medicineResults[] = [
            'medicine' =>
                $medicine,

            'observations' =>
                $result['observations'],

            'metrics' =>
                $result['metrics'],
        ];

        foreach (
            $result['observations']
            as $observation
        ) {
            $observation['medicine_name'] =
                $medicine->name;

            $allObservations[] =
                $observation;
        }
    }

    $overallMetrics =
        MedicineForecastService::computeAccuracyMetrics(
            $allObservations
        );

    usort(
        $medicineResults,
        function ($a, $b) {

            $aWape =
                $a['metrics']['wape'] ?? INF;

            $bWape =
                $b['metrics']['wape'] ?? INF;

            return $aWape <=> $bWape;
        }
    );

    $recentObservations =
        collect($allObservations)
            ->sortByDesc('target_start')
            ->take(20)
            ->values()
            ->all();

    return view(
        'admin.forecast-accuracy',
        compact(
            'role',
            'userName',
            'medicineResults',
            'overallMetrics',
            'recentObservations'
        )
    );
}

/**
 * Saved forecast history and completed forecast evaluations.
 */
public function history(
    MedicineForecastService $forecastService
) {
    $role = strtolower(
        session('admin_role') ?? Auth::user()->role ?? 'bhw'
    );

    if (!in_array($role, ['admin', 'nurse'], true)) {
        return redirect()
            ->route('bhw.inventory')
            ->with(
                'error',
                'Access Denied: You are not allowed to view Forecast History.'
            );
    }

    $userName = Auth::user()->name
        ?? session('admin_name')
        ?? session('user_name')
        ?? ucfirst($role);

    /*
     * Load all snapshots first.
     * This collection is used for:
     * - Completing pending evaluations
     * - Computing KPI metrics
     */
    $allSnapshots = \App\Models\ForecastSnapshot::with('medicine')
        ->orderByDesc('forecast_date')
        ->orderBy('medicine_id')
        ->get();

    /*
     * Evaluate only completed forecast periods.
     */
    foreach ($allSnapshots as $snapshot) {

        if (
            $snapshot->target_end->lt(today())
            && $snapshot->evaluated_at === null
        ) {
            $forecastService->evaluateSnapshotAccuracy(
                $snapshot
            );
        }
    }

    /*
     * Reload all snapshots after evaluation so that
     * the latest actual demand and error values are used.
     */
    $allSnapshots = \App\Models\ForecastSnapshot::with('medicine')
        ->orderByDesc('forecast_date')
        ->orderBy('medicine_id')
        ->get();

    /*
     * Calculate completed evaluations.
     */
    $completed = $allSnapshots->filter(
        fn ($snapshot) => $snapshot->actual_demand !== null
    );

    $evaluationCount = $completed->count();

    $totalActual = (int) $completed->sum('actual_demand');

    $totalAbsoluteError =
        (int) $completed->sum('absolute_error');

    $mae = $evaluationCount > 0
        ? $totalAbsoluteError / $evaluationCount
        : null;

    $wape = $totalActual > 0
        ? ($totalAbsoluteError / $totalActual) * 100
        : null;

    $accuracy = $wape !== null
        ? max(0, 100 - $wape)
        : null;

    /*
     * Paginate ONLY the records displayed in the table.
     * This prevents the page from becoming one long list.
     */
    $snapshots = \App\Models\ForecastSnapshot::with('medicine')
        ->orderByDesc('forecast_date')
        ->orderBy('medicine_id')
        ->paginate(10)
        ->withQueryString();

    return view(
        'admin.forecast-history',
        compact(
            'role',
            'userName',
            'snapshots',
            'evaluationCount',
            'mae',
            'wape',
            'accuracy'
        )
    );
}

/**
 * Review a forecast recommendation.
 *
 * Decisions:
 * - accept: accept the system suggested reorder quantity
 * - edit: change the reorder quantity
 * - dismiss: reject the recommendation
 *
 * Every decision is recorded using the existing ActivityLogger.
 */
public function review(
    Request $request,
    int $medicineId,
    string $decision,
    MedicineForecastService $forecastService
) {
    $role = strtolower(
        session('admin_role') ?? Auth::user()->role ?? 'bhw'
    );

    if (!in_array($role, ['admin', 'nurse'], true)) {
        return redirect()
            ->route('bhw.inventory')
            ->with(
                'error',
                'Access Denied: You are not allowed to review Forecast recommendations.'
            );
    }

    $allowedDecisions = ['accept', 'edit', 'dismiss'];

    if (!in_array($decision, $allowedDecisions, true)) {
        return back()->with(
            'error',
            'Invalid forecast review action.'
        );
    }

    $medicine = Medicine::findOrFail($medicineId);

    /*
     * Recalculate the forecast on the server.
     * This prevents the browser from changing the suggested
     * quantity before the audit record is created.
     */
    $evaluation = $forecastService->evaluate($medicine);

    if (!$evaluation['forecast_available']) {
        return back()->with(
            'error',
            'This medicine does not have enough historical data for a reliable forecast.'
        );
    }

    $suggestedQty = (int) ($evaluation['suggested_reorder_qty'] ?? 0);

    $finalQty = $suggestedQty;

    $reason = trim(
        (string) $request->input('reason', '')
    );

    /*
     * EDIT
     */
    if ($decision === 'edit') {

        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:0',
                'max:100000',
            ],
            'reason' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ]);

        $finalQty = (int) $validated['quantity'];
        $reason = trim($validated['reason']);
    }

    /*
     * DISMISS
     */
    if ($decision === 'dismiss') {

        $validated = $request->validate([
            'reason' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ]);

        $reason = trim($validated['reason']);
        $finalQty = 0;
    }

    /*
     * ACCEPT
     */
    if ($decision === 'accept') {

        $validated = $request->validate([
            'reason' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $reason = trim(
            (string) ($validated['reason'] ?? '')
        );
    }

    $decisionLabel = match ($decision) {
        'accept' => 'ACCEPTED',
        'edit' => 'EDITED',
        'dismiss' => 'DISMISSED',
        default => strtoupper($decision),
    };

    $details =
        'Medicine: ' . $medicine->name
        . ' | Decision: ' . $decisionLabel
        . ' | Forecast Demand: ' . $evaluation['estimated_demand'] . ' units'
        . ' | Suggested Reorder: ' . $suggestedQty . ' units'
        . ' | Final Reviewed Quantity: ' . $finalQty . ' units'
        . ' | Usable Stock: ' . $evaluation['usable_stock'] . ' units'
        . ' | Status: ' . $evaluation['status']
        . ' | Forecast Method: ' . $evaluation['forecast_method'];

    if ($reason !== '') {
        $details .= ' | Reason: ' . $reason;
    }
    /*
 * Attach the staff review to today's saved forecast snapshot.
 */
$snapshot = \App\Models\ForecastSnapshot::where(
    'medicine_id',
    $medicineId
)
    ->whereDate('forecast_date', today())
    ->first();

if ($snapshot) {
    $snapshot->update([
        'review_decision' => $decision,
        'reviewed_quantity' => $finalQty,
        'review_reason' => $reason !== '' ? $reason : null,
        'reviewed_by' => Auth::user()->name
            ?? session('admin_name')
            ?? session('user_name')
            ?? 'System',
        'reviewed_at' => now(),
    ]);
}

    \App\Services\ActivityLogger::log(
        'Forecast Recommendation ' . $decisionLabel,
        'Medicine Forecasting',
        $details,
        'FORECAST_REVIEW'
    );

    $message = match ($decision) {
        'accept' =>
            "Forecast recommendation for {$medicine->name} was accepted. Suggested reorder quantity: {$finalQty} units.",

        'edit' =>
            "Forecast recommendation for {$medicine->name} was edited to {$finalQty} units.",

        'dismiss' =>
            "Forecast recommendation for {$medicine->name} was dismissed.",

        default =>
            'Forecast review recorded.',
    };

    return back()->with(
        'success',
        $message
    );
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

    public function exportHistory()
{
    $role = strtolower(
        session('admin_role') ?? Auth::user()->role ?? 'bhw'
    );

    if (!in_array($role, ['admin', 'nurse'], true)) {
        return redirect()
            ->route('bhw.inventory')
            ->with(
                'error',
                'Access Denied: You are not allowed to export Forecast History.'
            );
    }

    $fileName = 'forecast-history-' . now()->format('Y-m-d') . '.xlsx';

    return Excel::download(
        new ForecastHistoryExport(),
        $fileName
    );
}
}

