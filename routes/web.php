<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClassRoomController;
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
            Route::get('/verify-admin-email/{token}', 'VerifyAdminEmail')->name('ad.verify.email');
            Route::get('/invalid-token', 'adminInvalidToken')->name('ad.invalid.token');
            Route::post('/resend-admin-token', 'resendAdminToken')->name('ad.resend.token');
           
        });
    });
    Route::middleware(['auth:admin', 'is_locked_out', 'is_logged_in'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/admin-logout', 'logout')->name('ad.logout');
            Route::get('/admin-register', 'registerPage')->name('ad.register');
            Route::post('/admin-register-process', 'processAdminRegister')->name('ad.process.register');
            Route::get('/registration-success', 'registrationSuccess')->name('ad.registration.success');

        });
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/admin-dashboard', 'adminDashboard')->name('ad.dashboard');
            Route::get('/admin-profile', 'adminProfile')->name('ad.profile');
            Route::get('/admin-superusers', 'Superusers')->name('ad.superusers');

            Route::post('/admin-update-photo', 'updateProfilePhoto')->name('ad.update.photo');
            Route::post('/admin-update-details', 'updateAdminDetails')->name('ad.update.details');
            Route::post('/admin-update-password', 'updateAdminPassword')->name('ad.update.password');

            Route::post('/admin-toggle-status', 'toggleStatus')->name('ad.toggle.status');
            Route::get('/admin-details', 'adminDetails')->name('ad.details');
            Route::post('/admin-login-time', 'updateAdminLastLogin')->name('ad.update.login.time');
            Route::post('/admin-delete', 'deleteAdmin')->name('ad.delete.admin');

            // lecturer
            Route::post('/admin-register-lecturer', 'processLecturerRegister')->name('ad.process.register.lecturer');
            Route::get('/admin-lecturers', 'Lecturers')->name('ad.lecturers');
            Route::post('/admin-lecturer-toggle-status', 'toggleLecturerStatus')->name('ad.toggle.lecturer.status');
            Route::get('/admin-lecturer-details', 'lecturerDetails')->name('ad.lecturer.details');
            Route::post('/admin-lecturer-login-time', 'updateLecturerLastLogin')->name('ad.update.lecturer.login.time');
            Route::post('/admin-lecturer-delete', 'deleteLecturer')->name('ad.delete.lecturer');

            Route::post('/generate-password', 'generatePassword')->name('ad.generate.password');



        });

        Route::controller(ClassRoomController::class)->group(function () {
            Route::get('/list-classrooms', 'listClassRooms')->name('ad.list.classroom');
            Route::get('/admin-classrooms', 'classRooms')->name('ad.class.rooms');
            Route::post('/admin-process-classrooms', 'addClassRooms')->name('ad.class.room.add');


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
        Route::controller(LecturerController::class)->group(function () {
            Route::get('/lecturer-login', 'loginPage')->name('lect.login');
            Route::post('/lecturer-login-process', 'processLecturerLogin')->name('lect.process.login');
            Route::get('/verify-lecturer-email/{token}', 'VerifyLecturerEmail')->name('lect.verify.email');
            Route::get('/invalid-lecturer', 'lecturerInvalidToken')->name('lect.invalid.token');
            Route::post('/resend-lecturer-token', 'resendLecturerToken')->name('lect.resend.token');
           
        });
    });
    Route::middleware(['auth:lecturer','is_locked_out', 'is_lect_logged_in'])->group(function () {
        Route::controller(LecturerController::class)->group(function () {
            Route::get('/lecturer-logout', 'logout')->name('lect.logout');
        });
        // Route::controller(LecturerDashboardController::class)->group(function () {
        //     Route::get('/admin-dashboard', 'adminDashboard')->name('ad.dashboard');
        //     Route::get('/admin-profile', 'adminProfile')->name('ad.profile');
        //     Route::get('/admin-superusers', 'Superusers')->name('ad.superusers');

        //     Route::post('/admin-update-photo', 'updateProfilePhoto')->name('ad.update.photo');
        //     Route::post('/admin-update-details', 'updateAdminDetails')->name('ad.update.details');
        //     Route::post('/admin-update-password', 'updateAdminPassword')->name('ad.update.password');

        //     Route::post('/admin-toggle-status', 'toggleStatus')->name('ad.toggle.status');
        //     Route::get('/admin-details', 'adminDetails')->name('ad.details');
        //     Route::post('/admin-login-time', 'updateAdminLastLogin')->name('ad.update.login.time');
        //     Route::post('/admin-delete', 'deleteAdmin')->name('ad.delete.admin');

        //     // lecturer
        //     Route::post('/admin-register-lecturer', 'processLecturerRegister')->name('ad.process.register.lecturer');
        //     Route::get('/admin-lecturers', 'Lecturers')->name('ad.lecturers');
        //     Route::post('/admin-lecturer-toggle-status', 'toggleLecturerStatus')->name('ad.toggle.lecturer.status');
        //     Route::get('/admin-lecturer-details', 'lecturerDetails')->name('ad.lecturer.details');
        //     Route::post('/admin-lecturer-login-time', 'updateLecturerLastLogin')->name('ad.update.lecturer.login.time');
        //     Route::post('/admin-lecturer-delete', 'deleteLecturer')->name('ad.delete.lecturer');

        //     Route::post('/generate-password', 'generatePassword')->name('ad.generate.password');



        // });
    });
});