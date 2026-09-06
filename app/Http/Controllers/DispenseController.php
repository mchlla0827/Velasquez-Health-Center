<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\DispensingRecord;
use App\Models\Medicine;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispenseController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Ready to dispense!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = $this->role();

        abort_unless(
            in_array($role, ['admin', 'nurse'], true),
            403
        );

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'medicine_id' => ['required', 'exists:medicines,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit' => ['required', 'string', 'max:100'],
            'diagnosis' => ['required', 'string', 'max:1000'],
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {

                $record = DispensingRecord::query()
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($record->status === 'VOIDED') {
                    throw new \RuntimeException(
                        'A voided dispensing record cannot be edited.'
                    );
                }

                /*
                 * 1. Return the original quantities to their original batches.
                 */
                $oldTransactions = StockTransaction::query()
                    ->where('dispensing_record_id', $record->id)
                    ->where('type', 'OUT')
                    ->lockForUpdate()
                    ->get();

                $oldMedicine = Medicine::query()
                    ->lockForUpdate()
                    ->findOrFail($record->medicine_id);

                foreach ($oldTransactions as $transaction) {

                    $batch = Batch::query()
                        ->where('medicine_id', $transaction->medicine_id)
                        ->where('batch_number', $transaction->batch_number)
                        ->lockForUpdate()
                        ->first();

                    if ($batch) {
                        $batch->increment(
                            'quantity',
                            $transaction->quantity
                        );

                        StockTransaction::create([
                            'medicine_id' => $transaction->medicine_id,
                            'dispensing_record_id' => $record->id,
                            'batch_number' => $batch->batch_number,
                            'expiry' => $batch->expiry_date,
                            'quantity' => $transaction->quantity,
                            'type' => 'IN',
                            'remarks' => 'Returned stock during edit of Dispensing Record #' . $record->id,
                        ]);
                    }
                }

                /*
                 * Sync the old medicine stock.
                 */
                $oldMedicine->recalculateStock();

                /*
                 * 2. Get the NEW medicine.
                 */
                $medicine = Medicine::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['medicine_id']);

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

                /*
                 * 3. Remove the old OUT transactions from the active set.
                 */
                StockTransaction::query()
                    ->where('dispensing_record_id', $record->id)
                    ->where('type', 'OUT')
                    ->delete();

                /*
                 * 4. Deduct the new quantity using FEFO.
                 */
                $remaining = (int) $validated['quantity'];

                foreach ($batches as $batch) {

                    if ($remaining <= 0) {
                        break;
                    }

                    $deducted = min(
                        (int) $batch->quantity,
                        $remaining
                    );

                    $batch->decrement(
                        'quantity',
                        $deducted
                    );

                    StockTransaction::create([
                        'medicine_id' => $medicine->id,
                        'dispensing_record_id' => $record->id,
                        'batch_number' => $batch->batch_number,
                        'expiry' => $batch->expiry_date,
                        'quantity' => $deducted,
                        'type' => 'OUT',
                        'remarks' => 'Updated dispensing record #' . $record->id . ' for patient: ' . $record->patient_name,
                    ]);

                    $remaining -= $deducted;
                }

                /*
                 * 5. Update dispensing record.
                 */
                $record->update([
                    'dispense_date' => $validated['date'],
                    'medicine_id' => $medicine->id,
                    'quantity_dispensed' => $validated['quantity'],
                    'unit' => $validated['unit'],
                    'diagnosis' => $validated['diagnosis'],
                ]);

                $medicine->recalculateStock();
            }, 3);

            $redirectRoute = $role === 'admin'
                ? 'admin.dispense'
                : 'nurse.dispense';

            return redirect()
                ->route($redirectRoute)
                ->with(
                    'success',
                    'Dispensing record updated successfully.'
                );

        } catch (\RuntimeException $exception) {

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());

        } catch (\Throwable $exception) {

            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update dispensing record.'
                );
        }
    }

    public function void($id)
    {
        $role = $this->role();

        abort_unless(
            in_array($role, ['admin', 'nurse'], true),
            403
        );

        try {
            DB::transaction(function () use ($id) {

                $record = DispensingRecord::query()
                    ->lockForUpdate()
                    ->findOrFail($id);

                if ($record->status === 'VOIDED') {
                    throw new \RuntimeException(
                        'This dispensing record is already voided.'
                    );
                }

                $transactions = StockTransaction::query()
                    ->where('dispensing_record_id', $record->id)
                    ->where('type', 'OUT')
                    ->lockForUpdate()
                    ->get();

                $medicine = Medicine::query()
                    ->lockForUpdate()
                    ->findOrFail($record->medicine_id);

                foreach ($transactions as $transaction) {

                    $batch = Batch::query()
                        ->where('medicine_id', $transaction->medicine_id)
                        ->where('batch_number', $transaction->batch_number)
                        ->lockForUpdate()
                        ->first();

                    if (! $batch) {
                        throw new \RuntimeException(
                            'Unable to locate batch ' . $transaction->batch_number
                        );
                    }

                    $batch->increment(
                        'quantity',
                        $transaction->quantity
                    );

                    StockTransaction::create([
                        'medicine_id' => $transaction->medicine_id,
                        'dispensing_record_id' => $record->id,
                        'batch_number' => $batch->batch_number,
                        'expiry' => $batch->expiry_date,
                        'quantity' => $transaction->quantity,
                        'type' => 'IN',
                        'remarks' => 'Returned stock due to voided Dispensing Record #' . $record->id,
                    ]);
                }

                $record->update([
                    'status' => 'VOIDED',
                    'voided_at' => now(),
                    'voided_by' => auth()->user()?->name ?? 'System',
                ]);

                $medicine->recalculateStock();
            }, 3);

            return back()->with(
                'success',
                'Dispensing record voided successfully.'
            );

        } catch (\RuntimeException $exception) {

            return back()->with(
                'error',
                $exception->getMessage()
            );

        } catch (\Throwable $exception) {

            report($exception);

            return back()->with(
                'error',
                'Unable to void dispensing record.'
            );
        }
    }

    /**
     * Resolves role either from query string (?role=nurse) or authenticated user.
     */
    private function role(): string
    {
        $role = request('role') ?? auth()->user()?->role;

        return strtolower((string) $role);
    }
}