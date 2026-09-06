<?php

namespace App\Http\Controllers;

use App\Models\MedicineRequest;
use App\Models\Medicine; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            
            if ($user->role === 'Doctor' && trim((string)$user->is_physician_in_charge) !== '1') {
                abort(403, 'ACCESS DENIED: YOU ARE NOT THE PHYSICIAN IN CHARGE.');
            }
            
            
            if (!in_array($user->role, ['admin', 'Nurse', 'Doctor'])) {
                abort(403, 'Access Denied');
            }

            return $next($request);
        });
    }

    public function index()
{
    $user = Auth::user();

    $role = strtolower(trim($user->role));
    $isPIC = (string)$user->is_physician_in_charge === '1';

    // ==========================
    // ADMIN or PIC DOCTOR
    // ==========================
    if ($role === 'admin' || ($role === 'doctor' && $isPIC)) {

        $requests = MedicineRequest::latest()->get();

        $pending = MedicineRequest::where('status', 'Pending Physician')->count();
        $approved = MedicineRequest::where('status', 'Approved')->count();
        $rejected = MedicineRequest::where('status', 'Rejected')->count();
        $completed = MedicineRequest::where('status', 'Completed')->count();

        return view('admin.request', compact(
            'requests',
            'pending',
            'approved',
            'rejected',
            'completed'
        ));
    }

    // ==========================
    // NURSE
    // ==========================
    if ($role === 'nurse') {

        $medicines = Medicine::orderBy('name')->get();

        $userName = $user->name;

        $myRequests = MedicineRequest::where('requested_by', $user->id)
            ->latest()
            ->take(20)
            ->get();

        return view('nurse.request', compact(
            'medicines',
            'userName',
            'role',
            'myRequests'
        ));
    }

    abort(403, 'Unauthorized');
}
    // ==================================================
    // STORE: PARA SA NURSE LANG
    // ==================================================
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'Doctor' || $user->role === 'Admin') {
            abort(403, 'Doctors/Admin cannot create requests.'); 
        }

        $validated = $request->validate([
            'responsibility_center_code' => 'nullable|string',
            'ris_number' => 'nullable|string',
            'date_prepared' => 'required|date',
            'medicine_id' => 'required|array',
            'medicine_id.*' => 'required|integer',
            'unit' => 'nullable|array',
            'batch' => 'nullable|array',
            'expiry' => 'nullable|array',
            'qty_requested' => 'required|array',
            'qty_requested.*' => 'required|integer|min:1',
            'purpose' => 'required|string',
        ]);

        foreach ($validated['medicine_id'] as $key => $medId) {
            MedicineRequest::create([
                'medicine_id' => $medId,
                'quantity_requested' => $validated['qty_requested'][$key],
                'requested_by' => $user->id,
                'status' => 'Pending Physician',
                'reason' => $validated['purpose'],
                'responsibility_center_code' => $validated['responsibility_center_code'] ?? null,
                'ris_number' => $validated['ris_number'] ?? null,
                'date_prepared' => $validated['date_prepared'],
                'unit' => $validated['unit'][$key] ?? null,
                'batch' => $validated['batch'][$key] ?? null,
                'expiry' => !empty($validated['expiry'][$key]) ? $validated['expiry'][$key] : null,
            ]);
        }

        return redirect()->route('nurse.request')->with('success', 'Request submitted successfully.');
    }

    // ==================================================
    // UPDATE STATUS: ADMIN at DOCTOR (PIC) LANG
    // ==================================================
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        //NURSE BAWAL, ADMIN AT PIC LANG PWEDE
        if ($user->role === 'Nurse' || ($user->role === 'Doctor' && trim((string)$user->is_physician_in_charge) !== '1')) {
            abort(403, 'Access Denied: Not authorized to change status.');
        }

        $req = MedicineRequest::findOrFail($id);
        $req->status = $request->status;
        $req->approved_by = Auth::id();
        $req->physician_notes = $request->physician_notes ?? null;
        $req->save();

        $redirectRoute = strtolower($user->role) === 'admin' ? 'admin.request' : 'doctor.request';
        return redirect()->route($redirectRoute)->with('success', 'Status updated successfully.');
    }

    // ==================================================
    // EDIT & UPDATE (NURSE LANG)
    // ==================================================
    public function edit($id)
    {
        $req = MedicineRequest::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'Nurse' || $req->requested_by != $user->id) {
            abort(403, 'Access Denied');
        }

        $medicines = Medicine::orderBy('name', 'asc')->get();
        $userName = $user->name;
        $role = $user->role;
        $myRequests = MedicineRequest::where('requested_by', $user->id)->latest()->take(20)->get();
        return view('nurse.request', compact('req', 'medicines', 'userName', 'role', 'myRequests'));
    }

    public function update(Request $request, $id)
    {
        $req = MedicineRequest::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'Nurse' || $req->requested_by != $user->id) {
            abort(403, 'Access Denied');
        }

        $validated = $request->validate([
            'medicine_id' => 'required|integer',
            'quantity_requested' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'status' => 'required|string',
        ]);

        $req->update($validated);
        return redirect()->route('nurse.request')->with('success', 'Updated successfully.');
    }

    // ==================================================
    // DELETE
    // ==================================================
    public function destroy($id)
    {
        $req = MedicineRequest::findOrFail($id);
        $user = Auth::user();
        
        if ($user->role === 'Nurse' && $req->requested_by != $user->id) {
            abort(403, 'Access Denied');
        }
        
        $req->delete();

        $redirectRoute = match (strtolower($user->role)) {
            'admin' => 'admin.request',
            'nurse' => 'nurse.request',
            default => 'doctor.request',
        };
        return redirect()->route($redirectRoute)->with('success', 'Deleted successfully.');
    }
}