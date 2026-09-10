<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\User;
use App\Models\StockTransaction;
use App\Models\DispensingRecord; // ✅ ADDED — CORRECT TABLE
use App\Models\TriageRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
public function index()
{
    // ✅ AYOS NA: $patientsToday = LISTAHAN (para sa count() sa card)
    // ✅ AYOS NA: $patientsTodayCount = BILANG (para sa badge sa recent patients)
    $patientsToday = Patient::whereDate('created_at', today())->latest()->get();
    $patientsTodayCount = $patientsToday->count();

    $lowStockMedicines = Medicine::whereColumn('stock', '<=', 'threshold')->select('id', 'name', 'stock')->get();
    $recentPatients = Patient::latest()->take(4)->get();
    $totalRegisteredPatients = Patient::count();

    $consultedPatientIds = TriageRecord::whereDate('created_at', today())
    ->pluck('patient_id')
    ->toArray();

    // ✅ FIXED: Count & Sum from DispensingRecord (YOUR ACTUAL DATA)
    $medicinesDispensedToday = DispensingRecord::whereDate('dispense_date', today())->sum('quantity_dispensed');
    $dispensedRecordsToday = DispensingRecord::whereDate('dispense_date', today())
        ->join('medicines', 'dispensing_records.medicine_id', '=', 'medicines.id')
        ->select(
            'dispensing_records.*',
            'medicines.name AS medicine_name',
            'medicines.brand'
        )
        ->orderBy('dispense_date', 'DESC')
        ->take(10) // Show last 10 only
        ->get();

    $patientsConsultedToday = TriageRecord::whereDate('created_at', today())->count();

    // Patient Risk Summary
    $totalInQueue = TriageRecord::whereDate('created_at', today())->count();
    $highRiskCount = TriageRecord::where('risk_level', 'High')->count();
    $mediumRiskCount = TriageRecord::where('risk_level', 'Medium')->count();
    $lowRiskCount = TriageRecord::where('risk_level', 'Low')->count();

    // AI Forecast Summary - now uses the SAME centralized service as the
    // main Forecast page, so the two screens can never show different
    // numbers for the same medicine.
    $forecastService = new \App\Services\MedicineForecastService(
        new \App\Services\InventoryStockService()
    );

    $forecastData = [];
    foreach (\App\Models\Medicine::all() as $med) {
        $result = $forecastService->evaluate($med);

        if ($result['is_shortage'] || $result['is_low']) {
            $forecastData[] = [
                'name' => $med->name,
                'predicted_shortage' => $result['status'],
                'high_demand' => 'Est. demand: ' . $result['estimated_demand'] . ' / mo',
                'recommended_restock' => $result['recommendation'],
            ];
        }
    }
    $forecastData = array_slice($forecastData, 0, 5);

    return view('dashboard', compact(
        'patientsToday',        // ✅ PARA SA CARD (Patients Registered Today)
        'patientsTodayCount',   // ✅ PARA SA BADGE (Recent Patients X today)
        'lowStockMedicines', 
        'recentPatients', 
        'highRiskCount',
        'totalRegisteredPatients', 
        'medicinesDispensedToday', 
        'patientsConsultedToday',
        'totalInQueue', 
        'mediumRiskCount', 
        'lowRiskCount', 
        'forecastData',
        'dispensedRecordsToday',
        'consultedPatientIds'
    ));
}
    public function manageUsers(Request $request)
    {
        $totalUsers = User::count();
        $adminCount = User::where('role', 'Admin')->count();
        $workerCount = User::whereIn('role', ['BHW', 'Health Worker'])->count();
        $activeCount = User::where('updated_at', '>=', now()->subMinutes(30))->count();

        $query = User::query();
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } elseif ($request->filled('status') && $request->status == 'active') {
            $query->where('updated_at', '>=', now()->subMinutes(30));
        }
        $users = $query->latest()->get();

        return view('admin.manage-user', compact('users', 'totalUsers', 'adminCount', 'workerCount', 'activeCount'));
    }
}