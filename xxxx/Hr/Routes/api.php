<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group([
    'prefix' => 'hr',
], function () {


    Route::post('attendance', [\App\Modules\Hr\Http\Controllers\AttendanceController::class, 'store'])
        ->name('hr.attendance.store');

    Route::post('attendance/sync-batch', [\App\Modules\Hr\Http\Controllers\AttendanceController::class, 'syncBatch'])
        ->name('hr.attendance.sync-batch');
});
                         






