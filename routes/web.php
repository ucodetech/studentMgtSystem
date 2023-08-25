<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Lecturer\LecturerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// general route
Route::controller(GeneralController::class)->group(function () {
    Route::get('/locked-out', 'lockedOut')->name('locked.out');
    Route::get('/resent-token', 'resentToken')->name('resent.token');

   
});

// admin route 
Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/admin-login', 'loginPage')->name('ad.login');
            Route::post('/admin-login-process', 'processAdminLogin')->name('ad.process.login');
            Route::get('/admin-register', 'registerPage')->name('ad.register');
            Route::post('/admin-register-process', 'processAdminRegister')->name('ad.process.register');
            Route::get('/verify-admin-email/{token}', 'VerifyAdminEmail')->name('ad.verify.email');
            Route::get('/registration-success', 'registrationSuccess')->name('ad.registration.success');
            Route::get('/invalid-token', 'adminInvalidToken')->name('ad.invalid.token');
            Route::post('/resend-admin-token', 'resendAdminToken')->name('ad.resend.token');
           
        });
    });
    Route::middleware(['auth:admin', 'is_locked_out', 'is_logged_in'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/admin-logout', 'logout')->name('ad.logout');
        });
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/admin-dashboard', 'adminDashboard')->name('ad.dashboard');
            Route::get('/admin-profile', 'adminProfile')->name('ad.profile');
            Route::post('/admin-update-photo', 'updateProfilePhoto')->name('ad.update.photo');
        });
    });
});

// user route
Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web'])->group(function () {
        
    });
    Route::middleware(['auth:web'])->group(function () {
        
    });
});



//lecturer route
Route::prefix('lecturer')->name('lecturer.')->group(function(){
    Route::middleware(['guest:lecturer'])->group(function () {
        
    });
    Route::middleware(['auth:lecturer'])->group(function () {
        
    });
});