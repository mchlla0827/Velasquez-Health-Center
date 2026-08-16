<?php

namespace App\Http\Controllers;

use App\Models\DispensingRecord;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Actual system role
    $role = $this->role();

    // Physician-in-Charge status
    $is_pic = (int) ($user?->is_physician_in_charge ?? 0);

    // Inventory VIEW permission
    abort_unless(
        in_array($role, ['admin', 'nurse', 'bhw'], true)
        || ($role === 'doctor' && $is_pic === 1),
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

    // Batches are the source of truth.
    // Keep legacy stock columns synchronized.
    foreach ($medicines as $medicine) {
        $this->syncMedicineStock($medicine);
    }

    // Doctor PIC can VIEW inventory but cannot add medicine.
    $canAddMedicine = in_array(
        $role,
        ['admin', 'nurse'],
        true
    );

    /*
     * DISPLAY ROLE
     *
     * Doctor + Physician-in-Charge = PIC
     * Otherwise use the normal role.
     */
    $displayRole = (
        $role === 'doctor' &&
        $is_pic === 1
    )
        ? 'PIC'
        : strtoupper($role);

    /*
     * Role badge colors.
     *
     * PIC uses the Doctor color because PIC is
     * still a Doctor in the system.
     */
    $roleColors = [
        'admin'  => '#9333EA', // Purple
        'nurse'  => '#10B981', // Green
        'doctor' => '#3B82F6', // Blue
        'bhw'    => '#6366F1', // Indigo
        'default' => '#6B7280', // Gray
    ];

    $roleColor = $roleColors[$role] ?? $roleColors['default'];

    return view('medicine.inventory', [
        'medicines' => $medicines,
        'userName' => $user?->name
            ?? session('admin_name')
            ?? session('user_name')
            ?? 'Staff',

        // Actual system role
        'role' => $role,

        // PIC status
        'is_pic' => $is_pic,

        // Displayed role: ADMIN, NURSE, BHW, DOCTOR, or PIC
        'displayRole' => $displayRole,

        // Badge color
        'roleColor' => $roleColor,

        // Inventory permissions
        'canAddMedicine' => $canAddMedicine,
    ]);
}
    public function dispenseForm()
    {
        $role = $this->role();
        abort_unless(in_array($role, ['admin', 'nurse'], true), 403);

        $medicines = Medicine::with(['batches' => function ($query) {
            $query->where('quantity', '>', 0)
                ->orderBy('expiry_date')
                ->orderBy('id');
        }])->orderBy('name')->get();

        $patients = Patient::orderByDesc('patient_id')->get();

        $dispensingHistory = DispensingRecord::with(['patient', 'medicine'])
            ->orderByDesc('dispense_date')
            ->orderByDesc('created_at')
            ->paginate(10);

        $userName = Auth::user()?->name ?? session('admin_name') ?? 'Staff';
        $viewPath = $role === 'admin' ? 'admin.dispense' : 'nurse.dispense';

        return view($viewPath, compact(
            'medicines',
            'patients',
            'dispensingHistory',
            'role',
            'userName'
        ));
    }

    public function dispenseSave(Request $request)
    {
        $role = $this->role();
        abort_unless(in_array($role, ['admin', 'nurse'], true), 403);

        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'patient_ptn' => ['required', 'string', 'max:255'],
            'family_no' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'patient_name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0'],
            'sex' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'philhealth_no' => ['nullable', 'string', 'max:255'],
            'diagnosis' => ['required', 'string', 'max:1000'],
            'medicine_id' => ['required', 'exists:medicines,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string', 'max:100'],
            'dispensed_by' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $medicine = Medicine::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['medicine_id']);

                /*
                 * FEFO: earliest expiry first.
                 * Expired batches are deliberately unavailable for dispensing.
                 */
                $batches = $medicine->batches()
                    ->where('quantity', '>', 0)
                    ->whereDate('expiry_date', '>=', today())
                    ->orderBy('expiry_date')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $availableStock = (int) $batches->sum('quantity');

                if ($availableStock < (int) $validated['quantity']) {
                    throw new \RuntimeException(
                        "Not enough usable stock. Available: {$availableStock}"
                    );
                }

                $dispensingRecord = DispensingRecord::create([
                    'patient_id' => $validated['patient_id'],
                    'patient_ptn' => trim($validated['patient_ptn']),
                    'family_no' => $validated['family_no'],
                    'barangay' => $validated['barangay'],
                    'dispense_date' => $validated['date'],
                    'patient_name' => $validated['patient_name'],
                    'age' => $validated['age'],
                    'sex' => $validated['sex'],
                    'address' => $validated['address'],
                    'philhealth_no' => $validated['philhealth_no'],
                    'diagnosis' => $validated['diagnosis'],
                    'medicine_id' => $medicine->id,
                    'quantity_dispensed' => $validated['quantity'],
                    'unit' => $validated['unit'],
                    'dispensed_by' => $validated['dispensed_by'],
                ]);

                $remaining = (int) $validated['quantity'];

                foreach ($batches as $batch) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $deducted = min((int) $batch->quantity, $remaining);

                    $batch->decrement('quantity', $deducted);

                    StockTransaction::create([
                        'medicine_id' => $medicine->id,
                        'batch_number' => $batch->batch_number,
                        'expiry' => $batch->expiry_date,
                        'quantity' => $deducted,
                        'type' => 'OUT',
                        'remarks' => 'Dispensed to patient: ' .
                            $validated['patient_name'] .
                            ' (Record #' . $dispensingRecord->id . ')',
                    ]);

                    $remaining -= $deducted;
                }

                $this->syncMedicineStock($medicine);
            }, 3);

            $redirectRoute = $role === 'admin'
                ? 'admin.dispense'
                : 'nurse.dispense';

            return redirect()
                ->route($redirectRoute)
                ->with('success', 'Medicine dispensed successfully.');
        } catch (\RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Unable to dispense medicine. Please try again.');
        }
    }

    public function store(Request $request)
    {
        $role = $this->role();
        abort_unless(in_array($role, ['admin', 'nurse'], true), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'dosage_form' => ['required', 'string', 'max:255'],
            'dosage_strength' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:100'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $exists = Medicine::query()
            ->whereRaw('LOWER(name) = ?', [strtolower($validated['name'])])
            ->whereRaw('LOWER(brand) = ?', [strtolower($validated['brand'])])
            ->whereRaw('LOWER(dosage_strength) = ?', [strtolower($validated['dosage_strength'])])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'duplicate' => true,
                'message' => 'Duplicate entry — this medicine already exists.',
            ], 422);
        }

        $validated['stock'] = 0;
        $validated['current_stock'] = 0;
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 50;

        Medicine::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medicine saved successfully.',
        ]);
    }

    private function syncMedicineStock(Medicine $medicine): int
    {
        $realStock = (int) $medicine->batches()->sum('quantity');

        $medicine->forceFill([
            'stock' => $realStock,
            'current_stock' => $realStock,
        ])->save();

        return $realStock;
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