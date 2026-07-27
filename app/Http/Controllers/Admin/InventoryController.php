<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\RestockRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    // ==================================================
    // STEP 0: FETCH AND DISPLAY INVENTORY MAIN DASHBOARD
    // ==================================================
    public function index()
{
    $medicines = Medicine::with(['batches' => function($query) {
        $query->orderBy('expiry_date', 'asc');
    }])->get()->map(function ($med) {
        // ✅ Always sync real stock
        $realStock = $med->batches->sum('quantity');
        $med->stock = $realStock;
        $med->current_stock = $realStock;
        $med->save();
        return $med;
    });

    $user = Auth::user();
    $role = $user && isset($user->role) ? strtolower($user->role) : 'admin'; 
    $userName = $user ? $user->name : 'Staff Member';

    $canDelete = in_array($role, ['admin']);
    $canEditOrStock = in_array($role, ['admin', 'nurse']);

    return view('admin.inventory', compact(
        'medicines',
        'role',
        'userName',
        'canDelete',
        'canEditOrStock'
    ));
}
    // ==================================================
    // STEP 1: PAKITA NG RESTOCK REQUEST FORM
    // ==================================================
    public function showRestockForm($id)
    {
        // Kunin ang gamot base sa ID
        $medicine = Medicine::findOrFail($id);

        // ==================================================
        // KUWENTAHIN ANG AI RECOMMENDATION (MOVING AVERAGE)
        // ==================================================
        $startDate = Carbon::now()->subMonths(3); // Huling 3 buwan
        $oneMonthAgo = Carbon::now()->subMonth();
        $twoMonthsAgo = Carbon::now()->subMonths(2);

        // Kunin ang mga rekord ng pagbibigay mula sa dispensing_records table
        $records = $medicine->dispensingRecords()
                    ->where('dispense_date', '>=', $startDate)
                    ->get();

        // Hatiin ang datos sa bawat buwan
        $period1 = $records->where('dispense_date', '>=', $oneMonthAgo)->sum('quantity_dispensed');
        $period2 = $records->where('dispense_date', '>=', $twoMonthsAgo)->where('dispense_date', '<', $oneMonthAgo)->sum('quantity_dispensed');
        $period3 = $records->where('dispense_date', '>=', $startDate)->where('dispense_date', '<', $twoMonthsAgo)->sum('quantity_dispensed');

        // Kuwentahin ang katamtaman / Moving Average
        $totalConsumption = $period1 + $period2 + $period3;
        $numberOfPeriods = count(array_filter([$period1, $period2, $period3]));

        if ($numberOfPeriods > 0) {
            $predictedDemand = (int) round($totalConsumption / $numberOfPeriods);
        } else {
            $predictedDemand = 0; // Kung walang datos
        }

        $currentStock = (int) $medicine->total_stock;

        // Formula para sa dami ng oorderin: (Kailangan * 3 buwan) - Meron na
        $recommendedQty = ($predictedDemand * 3) - $currentStock;
        $recommendedQty = max(50, (int) ceil($recommendedQty / 50) * 50); // Gawing bilog at hindi bababa sa 50

        // Ipadala lahat ng impormasyon sa Form
        return view('admin.request', compact(
            'medicine',
            'currentStock',
            'predictedDemand',
            'recommendedQty'
        ));
    }

    // ==================================================
    // STEP 2: USER SUBMITS REQUEST -> STATUS: PENDING
    // ==================================================
    public function submitRestockRequest(Request $request)
    {
        // Pagsala ng datos
        $request->validate([
            'medicine_id'        => 'required|integer',
            'quantity_requested' => 'required|integer|min:10',
            'reason'             => 'required|string',
            'supplier_id'        => 'nullable|string' // Text lang muna, wala pang table
        ]);

        // I-save sa Database
        RestockRequest::create([
            'medicine_id'        => $request->medicine_id,
            'supplier_id'        => $request->supplier_id,
            'quantity_requested' => $request->quantity_requested,
            'requested_by'       => Auth::id(), // Sino ang nag-request (kasalukuyang naka-login)
            'status'             => 'Pending Physician', // <-- Unang estado
            'reason'             => $request->reason,
        ]);

        return redirect()->route('forecast.index')->with('success', '✅ Request sent! Waiting for approval from Physician-in-Charge.');
    }

    // ==================================================
    // STEP 3: PHYSICIAN DASHBOARD - MGA NAGHIHINTAY NG APROBASYON
    // ==================================================
    public function viewForApproval()
    {
        // Lahat ng request na hindi pa desidido
        $pendingRequests = RestockRequest::with(['medicine', 'requester'])
                            ->where('status', 'Pending Physician')
                            ->latest()
                            ->get();

        // Lahat ng naaprubahan na, handang ipadala sa LHD
        $approvedRequests = RestockRequest::with(['medicine', 'requester', 'approver'])
                            ->where('status', 'Approved')
                            ->latest()
                            ->get();

        return view('admin.inventory.approval-dashboard', compact('pendingRequests', 'approvedRequests'));
    }

    // ==================================================
    // STEP 4: PHYSICIAN APPROVES / REJECTS
    // ==================================================
    public function processApproval(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes'  => 'nullable|string'
        ]);

        $req = RestockRequest::findOrFail($id);

        if ($request->action == 'approve') {
            $req->status = 'Approved';
            $req->approved_by = Auth::id(); // Sino ang nag-apruba
            $req->approved_at = Carbon::now();
        } else {
            $req->status = 'Rejected';
        }

        $req->physician_notes = $request->notes;
        $req->save();

        return redirect()->back()->with('success', '✅ Status updated successfully.');
    }

    // ==================================================
    // STEP 5: FORWARD TO LOCAL HEALTH DEPARTMENT
    // ==================================================
    public function sendToLHD($id)
    {
        $req = RestockRequest::findOrFail($id);
        
        // Siguraduhing Approved muna bago ipadala
        if($req->status != 'Approved') {
            return redirect()->back()->with('error', '❌ Cannot send. Request is not approved yet.');
        }

        $req->status = 'Sent to LHD';
        $req->save();

        return redirect()->back()->with('success', '📤 Request successfully forwarded to Local Health Department.');
    }
}