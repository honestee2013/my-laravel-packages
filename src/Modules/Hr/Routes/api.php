<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::group([
    'prefix' => 'hr',
    'middleware' => 'auth:sanctum',
], function () {

    Route::post('attendance/store', [\App\Modules\Hr\Http\Controllers\AttendanceController::class, 'store'])
        ->name('hr.attendance.store');

    Route::post('attendance/batch-store', [\App\Modules\Hr\Http\Controllers\AttendanceController::class, 'batchStore']);

    Route::get('user/sync', [\App\Modules\Hr\Http\Controllers\UserSyncController::class, 'syncUser']);



Route::middleware('auth:sanctum')->get('/test', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});





});












