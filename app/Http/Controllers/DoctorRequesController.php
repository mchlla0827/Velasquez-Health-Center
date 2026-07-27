<?php

namespace App\Http\Controllers;

use App\Models\MedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:doctor');
    }

    public function index()
    {
        $user = Auth::user();
        $isPIC = (int) $user->is_physician_in_charge;

        // ✅ KUNG PIC (is_physician_in_charge = 1)
        if ($isPIC === 1) {
            
            // ✅ GAYA NG ADMIN: MAKIKITA LAHAT
            $pending = MedicineRequest::where('status', 'Pending Physician')->count();
            $approved = MedicineRequest::where('status', 'Approved')->count();
            $rejected = MedicineRequest::where('status', 'Rejected')->count();
            $completed = MedicineRequest::where('status', 'Completed')->count();
            
            $requests = MedicineRequest::orderBy('created_at', 'desc')->get();

            // ✅ GINAGAMIT ANG VIEW NG ADMIN
            return view('admin.request', compact('requests', 'pending', 'approved', 'rejected', 'completed'));

        } else {
            abort(403, 'Access Denied: Only Physician In Charge can view this page.');
        }
    }

    // ✅ FUNCTION PARA MAG-APPROVE / MAG-REJECT
    public function updateStatus(Request $request, $id)
    {
        if ((int)Auth::user()->is_physician_in_charge !== 1) {
            abort(403, 'Access Denied');
        }

        $req = MedicineRequest::findOrFail($id);
        $req->status = $request->status;
        $req->approved_by = Auth::id(); // ✅ ILALAGAY ANG PANGALAN NG DOKTOR NA NAG-APRUBA
        $req->physician_notes = $request->physician_notes; // ✅ PWEDE SIYA MAGLAGAY NG NOTE
        $req->save();
        
        return redirect()->back()->with('success', "Request marked as {$request->status}.");
    }
}