<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
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

// admin route 
Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest', 'admin'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/admin-login', 'loginPage')->name('ad.login');
            Route::post('/admin-login-process', 'processAdminLogin')->name('ad.process.login');
            Route::get('/admin-register', 'registerPage')->name('ad.register');
            Route::post('/admin-register-process', 'processAdminRegister')->name('ad.process.register');
        });
    });
    Route::middleware(['auth', 'admin'])->group(function () {
        
    });
});

// user route
Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest', 'web'])->group(function () {
        
    });
    Route::middleware(['auth', 'web'])->group(function () {
        
    });
});



//lecturer route
Route::prefix('lecturer')->name('lecturer.')->group(function(){
    Route::middleware(['guest', 'lecturer'])->group(function () {
        
    });
    Route::middleware(['auth', 'lecturer'])->group(function () {
        
    });
});