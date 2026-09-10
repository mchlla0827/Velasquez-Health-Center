<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    private function ensureAdmin()
    {
        abort_unless(strtolower(auth()->user()->role ?? '') === 'admin', 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $barangays = Barangay::orderBy('name')->get();
        return view('admin.barangays', compact('barangays'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:barangays,name'],
        ]);

        Barangay::create([
            'name' => $validated['name'],
            'status' => Barangay::STATUS_ACTIVE,
        ]);

        return redirect()->back()->with('success', 'Barangay added successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->ensureAdmin();
        $barangay = Barangay::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:barangays,name,' . $barangay->id],
        ]);

        $barangay->update(['name' => $validated['name']]);

        return redirect()->back()->with('success', 'Barangay updated successfully.');
    }

    public function toggleStatus($id)
    {
        $this->ensureAdmin();
        $barangay = Barangay::findOrFail($id);

        $barangay->update([
            'status' => $barangay->status === Barangay::STATUS_ACTIVE
                ? Barangay::STATUS_INACTIVE
                : Barangay::STATUS_ACTIVE,
        ]);

        $message = $barangay->status === Barangay::STATUS_ACTIVE
            ? 'Barangay reactivated successfully.'
            : 'Barangay archived successfully. Existing patient records are unaffected.';

        return redirect()->back()->with('success', $message);
    }
}