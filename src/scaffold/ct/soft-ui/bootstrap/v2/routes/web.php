
<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Modules\Access\Http\Livewire\AccessControls\AccessControlManager;
use Spatie\Permission\Models\Permission;
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
    Route::get('access-control-management/{module}', function () {

        // Chech if only admin can access this view. If the user is not admin do not proceed
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }


        return view('access.views::access-control-management', [
            'selectedModule' => request("module"),
            'isUrlAccess' => true,
        ]);
    });
});





Route::get('/{module}/{view}', function ($module, $view) {
    // Validation
    Validator::make(['module' => $module, 'view' => $view], [
        'module' => 'required|string',
        'view' => 'required|string',
    ])->validate();

    $allowedModules = ['core', 'billing', 'sales', 'organization', 'hr', 'profile', 'item', 'warehouse', 'user', 'access'];

    if (!in_array($module, $allowedModules)) {
        abort(404, 'Invalid module');
    }



    // Chech if only admin can access this view. If the user is not admin do not proceed
    if (in_array($view, AccessControlManager::ROLE_ADMIN_ONLY_VIEWS)) {
        // Check if the user has the role
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

    // If user is  not admin, check if the user has the permission
    } else if (auth()->check() && !auth()->user()->hasRole(['admin', 'super_admin'])) {
        // Build a dynamic permission name
        $permission = "view_".AccessControlManager::getViewPerminsionModelName(($view));

        // Check permission or role
        if (!auth()->check() || !auth()->user()->can($permission)) {
            if ($view !=="my-profile") {
                abort(403, 'Unauthorized');
            }
        }
    }



    // Compose view path
    $viewName = $module . '.views::' . $view;

    // Check view existence
    if (view()->exists($viewName)) {
        return view($viewName);
    }

    abort(404, 'View not found');
});












