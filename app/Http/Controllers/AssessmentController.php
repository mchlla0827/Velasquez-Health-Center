<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class AssessmentController extends Controller
{
    // 1. Show the Assessment Form
    public function create($id)
    {
        $patient = Patient::findOrFail($id);
        
        // We pass the patient data so the form shows their name and ID at the top
        return view('patients.ncd-assessment', compact('patient'));    }

    // 2. Save the Assessment Form
    public function store(Request $request, $id)
    {
        try {
            $patient = Patient::findOrFail($id);

            // Grab everything submitted in the form except the security tokens
            $data = $request->except(['_token', '_method']);

            // Save the data to the patients table
            $patient->update($data);

            // Redirect back to the Patient Records page with a success message
            return redirect()->route('admin.patient-records')->with('success', 'NCD Risk Assessment completed successfully!');

        } catch (\Exception $e) {
            // If it fails, go back to the form and show the error
            return back()->with('error', 'Failed to save assessment: ' . $e->getMessage());
        }
    }
}