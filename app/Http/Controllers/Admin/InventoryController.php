<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\RestockRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();

        $role = $this->role();

        $isPic = (int) ($user?->is_physician_in_charge ?? 0);

        
        abort_unless(
            in_array($role, ['admin', 'nurse', 'bhw', 'doctor'], true)
            || ($role === 'doctor' && $isPic === 1),
            403
        );

        $medicines = Medicine::with([
            'batches' => function ($query) {
                $query
                    ->orderBy('expiry_date')
                    ->orderBy('id');
            }
        ])
            ->orderBy('name')
            ->get();

        
        foreach ($medicines as $medicine) {
            $this->syncMedicineStock($medicine);
        }

        $userName = $user?->name
            ?? session('admin_name')
            ?? session('user_name')
            ?? 'Staff Member';

        
        $canDelete = ($role === 'admin');

        $canEditOrStock = in_array(
            $role,
            ['admin', 'nurse'],
            true
        );

       
        $canViewInventory = true;

        return view('admin.inventory', compact(
            'medicines',
            'role',
            'userName',
            'canViewInventory',
            'canDelete',
            'canEditOrStock'
        ));
    }

   
    public function showRestockForm($id)
    {
        $this->requireRole(['admin', 'nurse']);

        $medicine = Medicine::with('batches')->findOrFail($id);

        $currentStock = $this->syncMedicineStock($medicine);

       
        $currentMonthStart = Carbon::today()->startOfMonth();

        $monthlyConsumption = [];

        for ($monthsAgo = 1; $monthsAgo <= 3; $monthsAgo++) {
            $start = $currentMonthStart->copy()->subMonths($monthsAgo);
            $end = $start->copy()->addMonth();

            $monthlyConsumption[] = (int) $medicine
                ->dispensingRecords()
                ->whereDate('dispense_date', '>=', $start)
                ->whereDate('dispense_date', '<', $end)
                ->sum('quantity_dispensed');
        }

        $predictedDemand = (int) round(
            array_sum($monthlyConsumption) / 3
        );

       
        $shortfall = max(
            0,
            ($predictedDemand * 3) - $currentStock
        );

       
        $recommendedQty = $shortfall === 0
            ? 0
            : max(
                50,
                (int) (ceil($shortfall / 50) * 50)
            );

        return view('admin.request', compact(
            'medicine',
            'currentStock',
            'predictedDemand',
            'recommendedQty'
        ));
    }

    
    public function submitRestockRequest(Request $request)
    {
        $this->requireRole(['admin', 'nurse']);

        $validated = $request->validate([
            'medicine_id' => [
                'required',
                'exists:medicines,id'
            ],
            'quantity_requested' => [
                'required',
                'integer',
                'min:10'
            ],
            'reason' => [
                'required',
                'string',
                'max:2000'
            ],
            'supplier_id' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);

        RestockRequest::create([
            'medicine_id' => $validated['medicine_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,
            'quantity_requested' => $validated['quantity_requested'],
            'requested_by' => Auth::id(),
            'status' => 'Pending Physician',
            'reason' => $validated['reason'],
        ]);

        return redirect()
            ->route('forecast.index')
            ->with(
                'success',
                'Request sent. Waiting for physician approval.'
            );
    }

   
    public function viewForApproval()
    {
        $this->requireRole([
            'admin',
            'physician',
            'doctor'
        ]);

        $pendingRequests = RestockRequest::with([
            'medicine',
            'requester'
        ])
            ->where('status', 'Pending Physician')
            ->latest()
            ->get();

        $approvedRequests = RestockRequest::with([
            'medicine',
            'requester',
            'approver'
        ])
            ->where('status', 'Approved')
            ->latest()
            ->get();

        return view(
            'admin.inventory.approval-dashboard',
            compact(
                'pendingRequests',
                'approvedRequests'
            )
        );
    }

    
    public function processApproval(Request $request, $id)
    {
        $this->requireRole([
            'admin',
            'physician',
            'doctor'
        ]);

        $validated = $request->validate([
            'action' => [
                'required',
                'in:approve,reject'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000'
            ],
        ]);

        DB::transaction(function () use ($validated, $id) {
            $restockRequest = RestockRequest::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($restockRequest->status !== 'Pending Physician') {
                throw new \RuntimeException(
                    'This request has already been processed.'
                );
            }

            $restockRequest->physician_notes = $validated['notes'];

            if ($validated['action'] === 'approve') {
                $restockRequest->status = 'Approved';
                $restockRequest->approved_by = Auth::id();
                $restockRequest->approved_at = now();
            } else {
                $restockRequest->status = 'Rejected';
            }

            $restockRequest->save();
        }, 3);

        return back()->with(
            'success',
            'Request status updated successfully.'
        );
    }

   
    public function sendToLHD($id)
    {
        $this->requireRole(['admin']);

        DB::transaction(function () use ($id) {
            $restockRequest = RestockRequest::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($restockRequest->status !== 'Approved') {
                throw new \RuntimeException(
                    'Cannot send this request because it is not approved yet.'
                );
            }

            $restockRequest->update([
                'status' => 'Sent to LHD',
            ]);
        }, 3);

        return back()->with(
            'success',
            'Request successfully forwarded to Local Health Department.'
        );
    }

   
    private function syncMedicineStock(Medicine $medicine): int
    {
        $realStock = (int) $medicine
            ->batches()
            ->sum('quantity');

        $medicine->forceFill([
            'stock' => $realStock,
            'current_stock' => $realStock,
        ])->save();

        return $realStock;
    }

    private function requireRole(array $roles): void
    {
        abort_unless(
            in_array($this->role(), $roles, true),
            403
        );
    }

    
    private function role(): string
    {
        return strtolower(
            session('admin_role')
            ?? Auth::user()?->role
            ?? 'bhw'
        );
    }
}