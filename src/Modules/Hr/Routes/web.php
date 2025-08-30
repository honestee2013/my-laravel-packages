<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Hr\Http\Controllers\IdCardController;
use App\Modules\Hr\Http\Controllers\PayrollRunController;



// Example route for HR managers to view a specific employee's ID card
// This route should be protected by appropriate authorization (e.g., 'role:hr_manager')
Route::middleware(['auth'])->group(function () {
    Route::get('/employee-id-card/show/{id}', [IdCardController::class, 'showEmployeeIdCard'])->name('show.employee.id.card');
    Route::get('/employee-id-card/download/{id}', [IdCardController::class, 'downloadEmployeeIdCard'])->name('download.employee.id.card');
    Route::get('/payroll-runs/{payrollRun}/generate', [PayrollRunController::class, 'generatePayroll'])
    ->name('payroll-runs.generate');

});







