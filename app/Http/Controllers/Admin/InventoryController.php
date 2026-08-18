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
    /**
     * Display the inventory.
     *
     * Inventory viewing is allowed for:
     * - Admin
     * - Nurse
     * - BHW
     * - Doctor who is Physician-in-Charge
     */
    public function index()
    {
        $user = Auth::user();

        $role = $this->role();

        $isPic = (int) ($user?->is_physician_in_charge ?? 0);

        /*
         * Inventory VIEW permission.
         *
         * Doctor is only allowed when they are
         * the designated Physician-in-Charge.
         */
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

        /*
         * Synchronize medicine stock with actual batch quantities.
         */
        foreach ($medicines as $medicine) {
            $this->syncMedicineStock($medicine);
        }

        $userName = $user?->name
            ?? session('admin_name')
            ?? session('user_name')
            ?? 'Staff Member';

        /*
         * ACTION PERMISSIONS
         *
         * Doctor PIC is intentionally NOT included here.
         * They can only VIEW inventory.
         */
        $canDelete = ($role === 'admin');

        $canEditOrStock = in_array(
            $role,
            ['admin', 'nurse'],
            true
        );

        /*
         * Optional explicit permission for Blade.
         */
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

    /**
     * Display the restock request form.
     *
     * Only Admin and Nurse can create restock requests.
     */
    public function showRestockForm($id)
    {
        $this->requireRole(['admin', 'nurse']);

        $medicine = Medicine::with('batches')->findOrFail($id);

        $currentStock = $this->syncMedicineStock($medicine);

        /*
         * A true three-month moving average includes
         * months with zero dispensing.
         */
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

        /*
         * Maintain a three-month supply.
         * Do not request stock when enough is available.
         */
        $shortfall = max(
            0,
            ($predictedDemand * 3) - $currentStock
        );

        /*
         * Keep the existing ordering increment of 50,
         * but only for an actual shortfall.
         */
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

    /**
     * Submit a restock request.
     *
     * Only Admin and Nurse can submit requests.
     */
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

    /**
     * Display restock requests awaiting physician approval.
     *
     * Admin, Physician, and Doctor can access this dashboard.
     */
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

    /**
     * Approve or reject a restock request.
     */
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

    /**
     * Forward an approved request to the LHD.
     *
     * Only Admin can perform this action.
     */
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

    /**
     * Synchronize medicine stock with the total quantity
     * stored in its batches.
     */
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

    /**
     * Require one of the specified roles.
     */
    private function requireRole(array $roles): void
    {
        abort_unless(
            in_array($this->role(), $roles, true),
            403
        );
    }

    /**
     * Get the authenticated user's normalized role.
     */
    private function role(): string
    {
        return strtolower(
            session('admin_role')
            ?? Auth::user()?->role
            ?? 'bhw'
        );
    }
}