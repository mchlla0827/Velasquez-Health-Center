<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\StockController;
use App\Models\ActivityLog;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TriageController; 
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DispenseController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
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
    
    // ✅ FIX 1: Universal endpoint for medicine history (Handles both /patient/... and /nurse/patient/...)
    Route::get('/patient/{ptn}/medicine-history', [PatientController::class, 'getPatientMedicineHistory'])->name('patient.medicine.history');
Route::get('/nurse/patient/{ptn}/medicine-history', [PatientController::class, 'getPatientMedicineHistory'])->name('nurse.patient.medicine.history');


    // ==================================================
    // ✅ ADMIN ROUTES - FULL ACCESS
    // ==================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/create-user', [RegisteredUserController::class, 'create'])->name('users.create');
        Route::post('/create-user', [RegisteredUserController::class, 'store'])->name('users.store');
        Route::get('/manage-user', [DashboardController::class, 'manageUsers'])->name('manage-user');
        Route::put('/users/update/{id}', [RegisteredUserController::class, 'update'])->name('users.update');

        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/dispense', [MedicineController::class, 'dispenseForm'])->name('dispense');
        Route::post('/dispense', [MedicineController::class, 'dispenseSave'])->name('dispense.save');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
        Route::get('/stockout', [StockController::class, 'stockout'])->name('stockout');
        Route::post('/stockout/store', [StockController::class, 'storeStockOut'])->name('stockout.store');

        // REQUEST FORM MODULE
        Route::get('/request', [RequestController::class, 'index'])->name('request');
        Route::post('/request', [RequestController::class, 'store'])->name('request.store');
        Route::get('/request/{id}/status/{status}', [RequestController::class, 'updateStatus'])->name('request.status');
        Route::get('/request/{id}/edit', [RequestController::class, 'edit'])->name('request.edit');
        Route::put('/request/{id}', [RequestController::class, 'update'])->name('request.update');
        Route::delete('/request/{id}', [RequestController::class, 'destroy'])->name('request.destroy');

        Route::get('/reports', fn() => view('admin.reports'))->name('reports');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');

        Route::get('/inventory/index', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/restock/{id}', [InventoryController::class, 'showRestockForm'])->name('restock.form');
        Route::post('/inventory/restock/submit', [InventoryController::class, 'submitRestockRequest'])->name('restock.submit');
        Route::get('/inventory/approval-queue', [InventoryController::class, 'viewForApproval'])->name('restock.approval');
        Route::post('/inventory/process/{id}', [InventoryController::class, 'processApproval'])->name('restock.process');
        Route::post('/inventory/send-lhd/{id}', [InventoryController::class, 'sendToLHD'])->name('restock.sendlhd');

        Route::post('/medicines/store', [MedicineController::class, 'store'])->name('medicines.store');
        Route::get('/check-batch', function(Request $request) {
            return response()->json(['exists' => \App\Models\Batch::where('batch_number', $request->batch_number)->exists()]);
        })->name('check.batch');

        Route::post('/stock-in', [StockController::class, 'storeIn'])->name('stock.in.store');

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

        Route::get('/reports/patient-reports', fn() => view('admin.reports.patient-reports'))->name('reports.patient');
        Route::get('/reports/risk', fn() => view('admin.reports.risk'))->name('reports.risk');
        Route::get('/reports/dispensing', fn() => view('admin.reports.dispensing'))->name('reports.dispensing');
        Route::get('/reports/medicine', fn() => view('admin.reports.medicine'))->name('reports.medicine');
        Route::get('/reports/operational', fn() => view('admin.reports.operational'))->name('reports.operational');
        Route::delete('/medicine/delete/{id}', [MedicineController::class, 'destroy'])->name('medicine.delete');
    });


    // ==================================================
    // ✅ NURSE ROUTES
    // ==================================================
    Route::middleware(['role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/dispense', [MedicineController::class, 'dispenseForm'])->name('dispense');
        Route::post('/dispense/save', [MedicineController::class, 'dispenseSave'])->name('dispense.save');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
        Route::get('/stockout', [StockController::class, 'stockout'])->name('stockout');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/reports', fn() => view('nurse.reports'))->name('reports');
        Route::get('/patient-registration', [PatientController::class, 'create'])->name('patient-registration');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');

        Route::get('/reports/patient-records', fn() => view('nurse.reports.patient-records'))->name('reports.patient-records');
        Route::get('/reports/risk', fn() => view('nurse.reports.risk'))->name('reports.risk');
        Route::get('/reports/medicine', fn() => view('nurse.reports.medicine'))->name('reports.medicine');
        Route::get('/reports/dispensing', fn() => view('nurse.reports.dispensing'))->name('reports.dispensing');
        Route::get('/reports/operational', fn() => view('nurse.reports.operational'))->name('reports.operational');

        Route::get('/request', [RequestController::class, 'index'])->name('request');
        Route::post('/request/store', [RequestController::class, 'store'])->name('request.store');
        Route::post('/request/update-status', [RequestController::class, 'updateStatus'])->name('request.updateStatus');
    });


    // ==================================================
    // ✅ DOCTOR ROUTES
    // ==================================================
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');

        Route::get('/request', [RequestController::class, 'index'])->name('request');

        Route::get('/reports', fn() => view('doctor.reports'))->name('reports');
        Route::get('/reports/patient-records', fn() => view('doctor.reports.patient-records'))->name('reports.patient-records');
        Route::get('/reports/risk', fn() => view('doctor.reports.risk'))->name('reports.risk');
        Route::get('/reports/medicine', fn() => view('doctor.reports.medicine'))->name('reports.medicine');
        Route::get('/reports/dispensing', fn() => view('doctor.reports.dispensing'))->name('reports.dispensing');
        Route::get('/reports/operational', fn() => view('doctor.reports.operational'))->name('reports.operational');
    });

    // ==================================================
    // ✅ BHW ROUTES
    // ==================================================
    Route::middleware(['role:bhw'])->prefix('bhw')->name('bhw.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/inventory', [MedicineController::class, 'index'])->name('inventory');
        Route::get('/triage', [PatientController::class, 'triage'])->name('triage');
        Route::get('/patient-registration', [PatientController::class, 'create'])->name('patient-registration');
        Route::get('/patient-records', [PatientController::class, 'index'])->name('patient-records');
        Route::get('/reports', fn() => view('bhw.reports'))->name('reports');

        Route::get('/reports/patient-records', fn() => view('bhw.reports.patient-records'))->name('reports.patient-records');
        Route::get('/reports/risk', fn() => view('bhw.reports.risk'))->name('reports.risk');
        Route::get('/reports/medicine', fn() => view('bhw.reports.medicine'))->name('reports.medicine');
        Route::get('/reports/dispensing', fn() => view('bhw.reports.dispensing'))->name('reports.dispensing');
        Route::get('/reports/operational', fn() => view('bhw.reports.operational'))->name('reports.operational');
    });

    // ==================================================
    // ✅ SHARED ROUTES (ANY LOGGED IN USER)
    // ==================================================
    Route::post('/patients/queue', [PatientController::class, 'addToQueue'])->name('patients.queue.store');
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patients/{id}/ncd-assessment', [App\Http\Controllers\AssessmentController::class, 'create'])->name('ncd.create');
    Route::post('/patients/{id}/ncd-assessment', [App\Http\Controllers\AssessmentController::class, 'store'])->name('ncd.store');
    Route::post('/medicine/store', [MedicineController::class, 'store'])->name('medicine.store');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/ai-forecast', [ForecastController::class, 'index'])->name('ai.forecast');
    Route::post('/patients/queue/store', [PatientController::class, 'storeQueue'])->name('triage.storeQueue');

    Route::post('/triage/{id}/update-status', [PatientController::class, 'updateStatus'])->name('triage.updateStatus');
    Route::get('/triage/live-queue-data', [PatientController::class, 'getLiveQueueData'])->name('triage.liveData');

    Route::get('/patients/{id}/service-history', [PatientController::class, 'serviceHistory'])
    ->name('patients.service-history');

});