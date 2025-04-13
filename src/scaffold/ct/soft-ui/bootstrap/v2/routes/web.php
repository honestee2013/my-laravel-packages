<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', function () {
		return view('dashboard');
	})->name('dashboard');

	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');

    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');






Route::group([
    'prefix' => 'access',
], function () {
    Route::get('access-control-management/{module}', function(){
        return view('access.views::access-control-management',
        [
            'selectedModule' => request("module"),
            'isUrlAccess' => true,
        ]);
    });
});



Route::get('/{module}/{view}', function ($module, $view) {
    // Validate module and view parameters
    Validator::make(['module' => $module, 'view' => $view], [
        'module' => 'required|string',
        'view' => 'required|string',
    ])->validate();

    // Define an allowed list of modules (you can modify this as needed)
    $allowedModules = [
        'core', 'billing', 'sales', 'organization', 'hr', 'profile', 'item', 'warehouse',
    ]; // Add your allowed modules here
    if (!in_array($module, $allowedModules)) {
        abort(404, 'Invalid module');
    }

    // Construct the view name securely. App\Modules\[Module Name]\Resources\views IS MAPPED TO [Module Name]::
    $viewName = $module . '.views::' . $view;

    // Check if the view file exists
    if (view()->exists($viewName)) {
        return view($viewName);
    } else {
        // Handle the case where the view doesn't exist
        abort(404, 'View not found');
    }
});






*/