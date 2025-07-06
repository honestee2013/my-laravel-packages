<?php 


use Illuminate\Support\Facades\Route;
use App\Modules\Hr\Http\Controllers\IdCardController;

// Example route for the authenticated user to view their own ID card
Route::middleware(['auth'])->group(function () {
    Route::get('/hr/my-id-card', [IdCardController::class, 'showMyIdCard'])->name('my.id.card');
});

// Example route for HR managers to view a specific employee's ID card
// This route should be protected by appropriate authorization (e.g., 'role:hr_manager')
Route::middleware(['auth', 'can:view-employee-id-cards'])->group(function () {
    Route::get('/employee/{userId}/id-card', [IdCardController::class, 'showEmployeeIdCard'])->name('employee.id.card');
});


/*
// Example routes for downloading ID cards
Route::middleware(['auth'])->group(function () {
    Route::get('/my-id-card/download', [IdCardController::class, 'downloadMyIdCard'])->name('my.id.card.download');
});

// For HR manager to download other employee's ID cards
Route::middleware(['auth', 'can:download-employee-id-cards'])->group(function () {
    Route::get('/employee/{userId}/id-card/download', [IdCardController::class, 'downloadEmployeeIdCard'])->name('employee.id.card.download');
});

*/

