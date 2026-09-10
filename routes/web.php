<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\StockController;
use App\Models\ActivityLog;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DispenseController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ConsultationController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PATIENT SHARED ENDPOINTS (Accessible by Admin, Nurse, Doctor, BHW)
    |--------------------------------------------------------------------------
    */
    Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/show/{id}', [PatientController::class, 'show']);
    Route::get('/patients/search', [PatientController::class, 'search']);

    
    // FIX 1: Universal endpoint for medicine history (Handles both /patient/... and /nurse/patient/...)
    Route::get('/patient/{ptn}/medicine-history', [PatientController::class, 'getPatientMedicineHistory'])->name('patient.medicine.history');
Route::get('/nurse/patient/{ptn}/medicine-history', [PatientController::class, 'getPatientMedicineHistory'])->name('nurse.patient.medicine.history');

    Route::get('/check-batch', function(Request $request) {
        return response()->json(['exists' => \App\Models\Batch::where('batch_number', $request->batch_number)->exists()]);
    })->name('check.batch');

    Route::post('/stock-in', [StockController::class, 'storeIn'])->name('stock.in.store');
    Route::post('/stock-update', [StockController::class, 'updateStock'])->name('stock.update');


    // ==================================================
    // ADMIN ROUTES - FULL ACCESS
    // ==================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/create-user', [RegisteredUserController::class, 'create'])->name('users.create');
        Route::post('/create-user', [RegisteredUserController::class, 'store'])->name('users.store');
        Route::get('/manage-user', [DashboardController::class, 'manageUsers'])->name('manage-user');
        Route::put('/users/update/{id}', [RegisteredUserController::class, 'update'])->name('users.update');

        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/dispense', [MedicineController::class, 'dispenseForm'])->name('dispense');
        Route::post('/dispense/save', [MedicineController::class, 'dispenseSave'])->name('dispense.save');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
        Route::get('/stockout', [StockController::class, 'stockout'])->name('stockout');

        // REQUEST FORM MODULE
        Route::get('/request', [RequestController::class, 'index'])->name('request');
        Route::post('/request', [RequestController::class, 'store'])->name('request.store');
        Route::get('/request/{id}/status/{status}', [RequestController::class, 'updateStatus'])->name('request.status');
        Route::get('/request/{id}/edit', [RequestController::class, 'edit'])->name('request.edit');
        Route::put('/request/{id}', [RequestController::class, 'update'])->name('request.update');
        Route::delete('/request/{id}', [RequestController::class, 'destroy'])->name('request.destroy');

        Route::get('/reports', fn() => view('reports.landing'))->name('reports');
        Route::get('/reports/patient', [ReportsController::class, 'patientCategory'])->name('reports.patient');
        Route::get('/reports/patient/demographic', [ReportsController::class, 'patientDemographic'])->name('reports.patient.demographic');
        Route::get('/reports/patient/registration', [ReportsController::class, 'patientRegistration'])->name('reports.patient.registration');
        Route::get('/reports/risk', [ReportsController::class, 'risk'])->name('reports.risk');
        Route::get('/reports/dispensing', [ReportsController::class, 'dispensing'])->name('reports.dispensing');
        Route::get('/reports/medicine', [ReportsController::class, 'medicineCategory'])->name('reports.medicine');
        Route::get('/reports/medicine/inventory-status', [ReportsController::class, 'medicineInventoryStatus'])->name('reports.medicine.inventory-status');
        Route::get('/reports/medicine/stock-out', [ReportsController::class, 'stockOutReport'])->name('reports.medicine.stock-out');
        Route::get('/reports/operational', [ReportsController::class, 'operationalCategory'])->name('reports.operational');
        Route::get('/reports/operational/daily-service', [ReportsController::class, 'dailyServiceReport'])->name('reports.operational.daily-service');
        Route::get('/reports/operational/morbidity', [ReportsController::class, 'morbidityReport'])->name('reports.operational.morbidity');
        Route::get('/reports/operational/medicine-request', [ReportsController::class, 'medicineRequestReport'])->name('reports.operational.medicine-request');
        Route::get('/reports/api/patient', [ReportsController::class, 'apiPatient'])->name('reports.api.patient');
        Route::get('/reports/api/risk', [ReportsController::class, 'apiRisk'])->name('reports.api.risk');
        Route::get('/reports/api/medicine', [ReportsController::class, 'apiMedicine'])->name('reports.api.medicine');
        Route::get('/reports/api/dispensing', [ReportsController::class, 'apiDispensing'])->name('reports.api.dispensing');
        Route::get('/reports/api/operational', [ReportsController::class, 'apiOperational'])->name('reports.api.operational');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');


        Route::post('/medicines/store', [MedicineController::class, 'store'])->name('medicines.store');
        Route::get('/patient-registration', [PatientController::class, 'create'])->name('patient-registration');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');

        Route::get('/logs', function (Request $request) {
            $query = ActivityLog::query();
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('user_name', 'like', '%'.$request->search.'%')
                      ->orWhere('action', 'like', '%'.$request->search.'%')
                      ->orWhere('details', 'like', '%'.$request->search.'%');
                });
            }
            if ($request->filled('date')) $query->whereDate('created_at', $request->date);
            return view('admin.logs', [
                'logs' => $query->latest()->paginate(10),
                'totalToday' => ActivityLog::whereDate('created_at', today())->count(),
                'dispensedToday' => ActivityLog::whereDate('created_at', today())->where('type','DISPENSE')->count(),
                'createdToday' => ActivityLog::whereDate('created_at', today())->where('type','CREATE')->count(),
                'activeUsers' => ActivityLog::whereDate('created_at', today())->distinct('user_name')->count()
            ]);
        });

        
        Route::delete('/medicine/delete/{id}', [MedicineController::class, 'destroy'])->name('medicine.delete');
    });


    // ==================================================
    // NURSE ROUTES
    // ==================================================
    Route::middleware(['role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/dispense', [MedicineController::class, 'dispenseForm'])->name('dispense');
        Route::post('/dispense/save', [MedicineController::class, 'dispenseSave'])->name('dispense.save');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
        Route::get('/stockout', [StockController::class, 'stockout'])->name('stockout');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/reports', fn() => view('reports.landing'))->name('reports');
        Route::get('/patient-registration', [PatientController::class, 'create'])->name('patient-registration');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');

        Route::get('/reports/patient', [ReportsController::class, 'patientCategory'])->name('reports.patient');
        Route::get('/reports/patient/demographic', [ReportsController::class, 'patientDemographic'])->name('reports.patient.demographic');
        Route::get('/reports/patient/registration', [ReportsController::class, 'patientRegistration'])->name('reports.patient.registration');
        Route::get('/reports/risk', [ReportsController::class, 'risk'])->name('reports.risk');
        Route::get('/reports/medicine', [ReportsController::class, 'medicineCategory'])->name('reports.medicine');
        Route::get('/reports/medicine/inventory-status', [ReportsController::class, 'medicineInventoryStatus'])->name('reports.medicine.inventory-status');
        Route::get('/reports/medicine/stock-out', [ReportsController::class, 'stockOutReport'])->name('reports.medicine.stock-out');
        Route::get('/reports/dispensing', [ReportsController::class, 'dispensing'])->name('reports.dispensing');
        Route::get('/reports/operational', [ReportsController::class, 'operationalCategory'])->name('reports.operational');
        Route::get('/reports/operational/daily-service', [ReportsController::class, 'dailyServiceReport'])->name('reports.operational.daily-service');
        Route::get('/reports/operational/morbidity', [ReportsController::class, 'morbidityReport'])->name('reports.operational.morbidity');
        Route::get('/reports/operational/medicine-request', [ReportsController::class, 'medicineRequestReport'])->name('reports.operational.medicine-request');
        Route::get('/reports/api/patient', [ReportsController::class, 'apiPatient'])->name('reports.api.patient');
        Route::get('/reports/api/risk', [ReportsController::class, 'apiRisk'])->name('reports.api.risk');
        Route::get('/reports/api/medicine', [ReportsController::class, 'apiMedicine'])->name('reports.api.medicine');
        Route::get('/reports/api/dispensing', [ReportsController::class, 'apiDispensing'])->name('reports.api.dispensing');
        Route::get('/reports/api/operational', [ReportsController::class, 'apiOperational'])->name('reports.api.operational');

        Route::get('/request', [RequestController::class, 'index'])->name('request');
        Route::post('/request/store', [RequestController::class, 'store'])->name('request.store');
        Route::post('/request/update-status', [RequestController::class, 'updateStatus'])->name('request.updateStatus');
    });


    // ==================================================
    // DOCTOR ROUTES
    // ==================================================
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');

        Route::get('/request', [RequestController::class, 'index'])->name('request');

        Route::get('/reports', fn() => view('reports.landing'))->name('reports');
        Route::get('/reports/patient', [ReportsController::class, 'patientCategory'])->name('reports.patient');
        Route::get('/reports/patient/demographic', [ReportsController::class, 'patientDemographic'])->name('reports.patient.demographic');
        Route::get('/reports/patient/registration', [ReportsController::class, 'patientRegistration'])->name('reports.patient.registration');
        Route::get('/reports/risk', [ReportsController::class, 'risk'])->name('reports.risk');
        Route::get('/reports/medicine', [ReportsController::class, 'medicineCategory'])->name('reports.medicine');
        Route::get('/reports/medicine/inventory-status', [ReportsController::class, 'medicineInventoryStatus'])->name('reports.medicine.inventory-status');
        Route::get('/reports/medicine/stock-out', [ReportsController::class, 'stockOutReport'])->name('reports.medicine.stock-out');
        Route::get('/reports/dispensing', [ReportsController::class, 'dispensing'])->name('reports.dispensing');
        Route::get('/reports/operational', [ReportsController::class, 'operationalCategory'])->name('reports.operational');
        Route::get('/reports/operational/daily-service', [ReportsController::class, 'dailyServiceReport'])->name('reports.operational.daily-service');
        Route::get('/reports/operational/morbidity', [ReportsController::class, 'morbidityReport'])->name('reports.operational.morbidity');
        Route::get('/reports/operational/medicine-request', [ReportsController::class, 'medicineRequestReport'])->name('reports.operational.medicine-request');
        Route::get('/reports/api/patient', [ReportsController::class, 'apiPatient'])->name('reports.api.patient');
        Route::get('/reports/api/risk', [ReportsController::class, 'apiRisk'])->name('reports.api.risk');
        Route::get('/reports/api/medicine', [ReportsController::class, 'apiMedicine'])->name('reports.api.medicine');
        Route::get('/reports/api/dispensing', [ReportsController::class, 'apiDispensing'])->name('reports.api.dispensing');
        Route::get('/reports/api/operational', [ReportsController::class, 'apiOperational'])->name('reports.api.operational');
    });

    // ==================================================
    // BHW ROUTES
    // ==================================================
    Route::middleware(['role:bhw'])->prefix('bhw')->name('bhw.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/patient-registration', [PatientController::class, 'create'])->name('patient-registration');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');
        Route::get('/reports', fn() => view('reports.landing'))->name('reports');

        Route::get('/reports/patient', [ReportsController::class, 'patientCategory'])->name('reports.patient');
        Route::get('/reports/patient/demographic', [ReportsController::class, 'patientDemographic'])->name('reports.patient.demographic');
        Route::get('/reports/patient/registration', [ReportsController::class, 'patientRegistration'])->name('reports.patient.registration');
        Route::get('/reports/risk', [ReportsController::class, 'risk'])->name('reports.risk');
        Route::get('/reports/medicine', [ReportsController::class, 'medicineCategory'])->name('reports.medicine');
        Route::get('/reports/medicine/inventory-status', [ReportsController::class, 'medicineInventoryStatus'])->name('reports.medicine.inventory-status');
        Route::get('/reports/medicine/stock-out', [ReportsController::class, 'stockOutReport'])->name('reports.medicine.stock-out');
        Route::get('/reports/dispensing', [ReportsController::class, 'dispensing'])->name('reports.dispensing');
        Route::get('/reports/operational', [ReportsController::class, 'operationalCategory'])->name('reports.operational');
        Route::get('/reports/operational/daily-service', [ReportsController::class, 'dailyServiceReport'])->name('reports.operational.daily-service');
        Route::get('/reports/operational/morbidity', [ReportsController::class, 'morbidityReport'])->name('reports.operational.morbidity');
        Route::get('/reports/operational/medicine-request', [ReportsController::class, 'medicineRequestReport'])->name('reports.operational.medicine-request');
    });

    // ==================================================
    // SHARED ROUTES (ANY LOGGED IN USER)
    // ==================================================
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::put('/dispense/{id}', [MedicineController::class, 'updateDispense'])->name('dispense.update');
    Route::get('/stockout/{id}/details', [StockController::class, 'stockoutDetails'])->name('stockout.details');
    Route::get('/reports/export/patient', [ReportsController::class, 'exportPatient'])->name('reports.export.patient');
    Route::get('/reports/export/risk', [ReportsController::class, 'exportRisk'])->name('reports.export.risk');
    Route::get('/reports/export/medicine', [ReportsController::class, 'exportMedicine'])->name('reports.export.medicine');
    Route::get('/reports/export/dispensing', [ReportsController::class, 'exportDispensing'])->name('reports.export.dispensing');
    Route::get('/reports/export/operational', [ReportsController::class, 'exportOperational'])->name('reports.export.operational');
    Route::get('/reports/export/inventory-status', [ReportsController::class, 'exportInventoryStatus'])->name('reports.export.inventory-status');
    Route::get('/reports/export/stock-out', [ReportsController::class, 'exportStockOut'])->name('reports.export.stock-out');
    Route::get('/reports/export/patient-demographic', [ReportsController::class, 'exportPatientDemographic'])->name('reports.export.patient-demographic');
    Route::get('/reports/export/patient-registration', [ReportsController::class, 'exportPatientRegistration'])->name('reports.export.patient-registration');
    Route::get('/reports/export/daily-service', [ReportsController::class, 'exportDailyService'])->name('reports.export.daily-service');
    Route::get('/reports/export/morbidity', [ReportsController::class, 'exportMorbidity'])->name('reports.export.morbidity');
    Route::get('/reports/export/medicine-request', [ReportsController::class, 'exportMedicineRequest'])->name('reports.export.medicine-request');
    Route::get('/patients/{id}/prescriptions', [MedicineController::class, 'patientPrescriptions'])->name('patients.prescriptions');
    Route::get('/patients/{id}/check-active-queue', [PatientController::class, 'checkActiveQueue'])->name('patients.check-active-queue');
    Route::get('/barangays', [BarangayController::class, 'index'])->name('barangays.index');
    Route::get('/forecast/insights', [ForecastController::class, 'insights'])->name('forecast.insights');
    Route::post('/barangays', [BarangayController::class, 'store'])->name('barangays.store');
    Route::put('/barangays/{id}', [BarangayController::class, 'update'])->name('barangays.update');
    Route::put('/barangays/{id}/toggle', [BarangayController::class, 'toggleStatus'])->name('barangays.toggle');
    Route::get('/patients-archived', [PatientController::class, 'archivedPatients'])->name('patients.archived');
    Route::post('/patients/{id}/archive', [PatientController::class, 'archivePatient'])->name('patients.archive');
    Route::post('/patients/{id}/reactivate', [PatientController::class, 'reactivatePatient'])->name('patients.reactivate');
    Route::put('/prescriptions/{id}/outcome', [MedicineController::class, 'setPrescriptionOutcome'])->name('prescriptions.outcome');
   // Consultation History Routes (Medical History tab)
    Route::get('/patients/{id}/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/patients/{id}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('/patients/{id}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{id}', [ConsultationController::class, 'show'])->name('consultations.show');
   // NCD Assessment Routes
    Route::get('/patients/{id}/ncd-assessment', [PatientController::class, 'createNcdAssessment'])->name('ncd.create');
    Route::post('/patients/{id}/ncd-assessment', [PatientController::class, 'storeNcdAssessment'])->name('ncd.store');
    Route::post('/medicine/store', [MedicineController::class, 'store'])->name('medicine.store');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/ai-forecast', [ForecastController::class, 'index'])->name('ai.forecast');
    Route::post('/patients/queue/store', [PatientController::class, 'storeQueue'])->name('triage.storeQueue');

    // STANDARD URL: /triage/update-status/{id}
Route::post('/triage/update-status/{id}', [PatientController::class, 'updateStatus'])->name('triage.update-status');
Route::get('/triage/live-queue-data', [PatientController::class, 'getLiveQueueData'])->name('triage.liveData');

    Route::get('/patients/{id}/service-history', [PatientController::class, 'serviceHistory'])
    ->name('patients.service-history');


});
