<?php

namespace App\Http\Controllers;

use App\Models\DispensingRecord;
use App\Models\Batch;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\StockTransaction;
use App\Models\User;
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

    $expiringBatches = Batch::query()
        ->join('medicines', 'medicines.id', '=', 'batches.medicine_id')
        ->where('batches.quantity', '>', 0)
        ->whereDate('batches.expiry_date', '>=', today())
        ->whereDate('batches.expiry_date', '<=', today()->addDays(180))
        ->orderBy('batches.expiry_date')
        ->orderBy('batches.id')
        ->get([
            'batches.id',
            'batches.medicine_id',
            'batches.batch_number',
            'batches.expiry_date',
            'batches.quantity',
            'batches.remarks',
            'batches.created_at',
            'medicines.name as medicine_name',
            'medicines.brand as brand_name',
            'medicines.dosage_strength',
        ]);

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
        'expiringBatches' => $expiringBatches,
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
    /**
     * Return a patient's active (pending or partially-fulfilled)
     * prescriptions as JSON - used by the Dispense form to auto-fill
     * medicine/dosage/etc. once the nurse selects a patient, instead of
     * letting them pick any random medicine unrelated to what was
     * actually prescribed.
     */
    public function patientPrescriptions($patientId)
    {
        $prescriptions = \App\Models\ConsultationPrescription::with('medicine')
            ->whereHas('consultation', function ($q) use ($patientId) {
                $q->where('patient_id', $patientId);
            })
            ->where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'partially_dispensed')
                            ->where(function ($q3) {
                                $q3->whereNull('fulfillment_outcome')
                                    ->orWhere('fulfillment_outcome', \App\Models\ConsultationPrescription::OUTCOME_AWAITING_STOCK);
                            });
                    });
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($rx) {
                $usableStock = $rx->medicine
                    ? app(\App\Services\InventoryStockService::class)->usableStock($rx->medicine)
                    : 0;

                return [
                    'id' => $rx->id,
                    'medicine_id' => $rx->medicine_id,
                    'medicine_name' => $rx->medicine_name,
                    'dosage' => $rx->dosage,
                    'frequency' => $rx->frequency,
                    'duration' => $rx->duration,
                    'quantity' => (int) $rx->quantity,
                    'quantity_dispensed' => (int) $rx->quantity_dispensed,
                    'remaining_needed' => max(0, (int) $rx->quantity - (int) $rx->quantity_dispensed),
                    'unit' => $rx->unit,
                    'instructions' => $rx->instructions,
                    'usable_stock' => $usableStock,
                    'diagnosis' => optional($rx->consultation)->assessment_diagnosis,
                ];
            });

        return response()->json(['prescriptions' => $prescriptions]);
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

        $staffMembers = User::where('role', 'nurse')
            ->orderBy('name')
            ->get(['name']);

        $dispensingHistory = DispensingRecord::with(['patient', 'medicine', 'prescription'])
            ->orderByDesc('dispense_date')
            ->orderByDesc('created_at')
            ->paginate(10);

        $userName = Auth::user()?->name ?? session('admin_name') ?? 'Staff';
        $viewPath = 'admin.dispense';

        return view($viewPath, compact(
            'medicines',
            'patients',
            'staffMembers',
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
            'prescription_id' => ['required', 'exists:consultation_prescriptions,id'],
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
            $resultMessage = DB::transaction(function () use ($validated) {
                $medicine = Medicine::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['medicine_id']);

                $prescription = \App\Models\ConsultationPrescription::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['prescription_id']);

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
                $requestedQty = (int) $validated['quantity'];

                $dispenseQty = min($requestedQty, $availableStock);
                $unfulfilledQty = $requestedQty - $dispenseQty;

                if ($unfulfilledQty === 0) {
                    $status = 'fully_dispensed';
                } elseif ($dispenseQty > 0) {
                    $status = 'partially_dispensed';
                } else {
                    $status = 'not_dispensed_stockout';
                }

                $dispensingRecord = DispensingRecord::create([
                    'patient_id' => $validated['patient_id'],
                    'prescription_id' => $prescription->id,
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
                    'quantity_dispensed' => $dispenseQty,
                    'status' => $status,
                    'unfulfilled_quantity' => $unfulfilledQty,
                    'unit' => $validated['unit'],
                    'dispensed_by' => $validated['dispensed_by'],
                ]);

                $remaining = $dispenseQty;

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

                $totalDispensedForRx = $prescription->quantity_dispensed + $dispenseQty;
                $prescription->quantity_dispensed = $totalDispensedForRx;
                $prescription->unfulfilled_quantity = max(0, $prescription->quantity - $totalDispensedForRx);
                $prescription->status = $totalDispensedForRx >= $prescription->quantity
                    ? 'fully_dispensed'
                    : ($totalDispensedForRx > 0 ? 'partially_dispensed' : 'not_dispensed_stockout');

                if ($prescription->status !== 'fully_dispensed' && !$prescription->fulfillment_outcome) {
                    $prescription->fulfillment_outcome = \App\Models\ConsultationPrescription::OUTCOME_AWAITING_STOCK;
                }

                $prescription->save();

                $this->syncMedicineStock($medicine);

                // Any unfulfilled quantity from THIS dispensing attempt
                // means the patient was affected by a shortage, even if
                // the medicine's overall stock hasn't dropped to exactly
                // zero (e.g. another batch still has some units left).
                // Make sure an active stock-out record exists so this
                // patient shows up in the Stock-Out Log's affected list.
                if ($unfulfilledQty > 0) {
                    app(\App\Services\InventoryStockService::class)
                        ->checkAndUpdateStockOutStatus($medicine, app(\App\Services\InventoryStockService::class)->usableStock($medicine));
                }

                return match ($status) {
                    'fully_dispensed' => 'Medicine dispensed successfully.',
                    'partially_dispensed' => "Only {$dispenseQty} of {$requestedQty} could be dispensed - insufficient stock. The shortfall has been recorded.",
                    default => 'Medicine could not be dispensed - no usable stock available. This has been recorded as a stock-out.',
                };
            }, 3);

            $redirectRoute = $role === 'admin'
                ? 'admin.dispense'
                : 'nurse.dispense';

            return redirect()
                ->route($redirectRoute)
                ->with('success', $resultMessage);
        } catch (\RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'Unable to dispense medicine. Please try again.');
        }
    }

    /**
     * Set what happens to the unfulfilled remainder of a partially
     * dispensed prescription. Does NOT touch the Stock-Out Log's
     * record of the shortage - that's a permanent record regardless
     * of how the prescription itself gets resolved.
     */
    public function setPrescriptionOutcome(Request $request, $id)
    {
        $role = $this->role();
        abort_unless(in_array($role, ['admin', 'nurse'], true), 403);

        $validated = $request->validate([
            'outcome' => ['required', 'in:awaiting_stock,referred_other_pharmacy,cancelled'],
        ]);

        $prescription = \App\Models\ConsultationPrescription::findOrFail($id);
        $prescription->fulfillment_outcome = $validated['outcome'];
        $prescription->save();

        return response()->json(['success' => true]);
    }

    /**
     * Correct a dispensing record's quantity. Adjusts inventory by the
     * DIFFERENCE between old and new quantity - since DispensingRecord
     * doesn't track which specific batch(es) it drew from (the original
     * dispense may have spanned multiple via FEFO), corrections apply
     * the same FEFO principle to the delta:
     *   - quantity decreased -> return the difference to the
     *     earliest-expiring valid batch (units were never really used)
     *   - quantity increased -> deduct the extra difference using the
     *     same FEFO order as a normal dispense
     */
    public function updateDispense(Request $request, $id)
    {
        $role = $this->role();
        abort_unless(in_array($role, ['admin', 'nurse'], true), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $record = DispensingRecord::findOrFail($id);

        try {
            DB::transaction(function () use ($validated, $record) {
                $medicine = Medicine::query()->lockForUpdate()->findOrFail($record->medicine_id);

                $oldQty = (int) $record->quantity_dispensed;
                $newQty = (int) $validated['quantity'];
                $diff = $newQty - $oldQty;

                if ($diff === 0) {
                    return;
                }

                if ($diff < 0) {
                    $returnQty = abs($diff);
                    $batch = $medicine->batches()
                        ->whereDate('expiry_date', '>=', today())
                        ->orderBy('expiry_date')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->first();

                    if ($batch) {
                        $batch->increment('quantity', $returnQty);
                        StockTransaction::create([
                            'medicine_id' => $medicine->id,
                            'batch_number' => $batch->batch_number,
                            'expiry' => $batch->expiry_date,
                            'quantity' => $returnQty,
                            'type' => 'IN',
                            'remarks' => "Correction: dispensing record #{$record->id} quantity reduced from {$oldQty} to {$newQty}",
                        ]);
                    }
                } else {
                    $extraQty = $diff;
                    $batches = $medicine->batches()
                        ->where('quantity', '>', 0)
                        ->whereDate('expiry_date', '>=', today())
                        ->orderBy('expiry_date')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    $availableStock = (int) $batches->sum('quantity');
                    if ($availableStock < $extraQty) {
                        throw new \RuntimeException(
                            "Not enough usable stock to increase this record. Available: {$availableStock}"
                        );
                    }

                    $remaining = $extraQty;
                    foreach ($batches as $batch) {
                        if ($remaining <= 0) break;
                        $deducted = min((int) $batch->quantity, $remaining);
                        $batch->decrement('quantity', $deducted);

                        StockTransaction::create([
                            'medicine_id' => $medicine->id,
                            'batch_number' => $batch->batch_number,
                            'expiry' => $batch->expiry_date,
                            'quantity' => $deducted,
                            'type' => 'OUT',
                            'remarks' => "Correction: dispensing record #{$record->id} quantity increased from {$oldQty} to {$newQty}",
                        ]);

                        $remaining -= $deducted;
                    }
                }

                $record->update(['quantity_dispensed' => $newQty]);
                app(\App\Services\InventoryStockService::class)->syncLegacyColumns($medicine);
            }, 3);

            return response()->json(['success' => true, 'message' => 'Dispensing record updated and inventory adjusted.']);
        } catch (\RuntimeException $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()], 422);
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['success' => false, 'message' => 'Unable to update record. Please try again.'], 500);
        }
    }

    /**
     * Delete a medicine. This route sits behind role:admin middleware,
     * so only Admin can ever reach it - no additional role check needed
     * here, but we do protect data integrity: a medicine with existing
     * dispensing history cannot be hard-deleted, since Reports and
     * DispensingRecord rely on that relationship. Suggest deactivating
     * instead for medicines that have been used.
     */
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);

        if ($medicine->dispensingRecords()->exists()) {
            return redirect()->back()->with(
                'error',
                'Cannot delete this medicine: it has dispensing history on record. Consider marking it inactive instead of deleting it.'
            );
        }

        DB::transaction(function () use ($medicine) {
            $medicine->batches()->delete();
            $medicine->delete();
        });

        return redirect()->back()->with('success', 'Medicine deleted successfully.');
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

        // The form field is named low_stock_threshold, but the actual
        // database column is `threshold` - map it explicitly rather than
        // relying on the names matching (they never did, which is why
        // every medicine's configured threshold was silently discarded).
        $validated['threshold'] = $validated['low_stock_threshold'] ?? 50;
        unset($validated['low_stock_threshold']);
        $validated['stock'] = 0;
        $validated['current_stock'] = 0;

        Medicine::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medicine saved successfully.',
        ]);
    }

    private function syncMedicineStock(Medicine $medicine): int
    {
        return app(\App\Services\InventoryStockService::class)->syncLegacyColumns($medicine);
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