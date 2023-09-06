<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ClassRoomController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Lecturer\LecturerController;
use App\Http\Controllers\Lecturer\LecturerDashboardController;
use App\Http\Controllers\User\UserDashboardController;
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
            
            Route::post('/admin-get-realtime-data', 'getRealTimeData')->name('ad.realtime.data');

            Route::get('/admin-students', 'Students')->name('ad.students');
            Route::post('/admin-student-toggle-status', 'toggleStudentStatus')->name('ad.toggle.student.status');
            Route::get('/admin-student-details', 'studentDetails')->name('ad.student.details');
            Route::post('/admin-student-delete', 'deleteStudent')->name('ad.delete.student');

        });

        Route::controller(ClassRoomController::class)->group(function () {
            Route::get('/list-classrooms', 'listClassRooms')->name('ad.list.classroom');
            Route::get('/admin-classrooms', 'classRooms')->name('ad.class.rooms');
            Route::post('/admin-process-classrooms', 'addClassRooms')->name('ad.class.room.add');
            Route::post('/admin-delete-classrooms', 'deleteClassRoom')->name('ad.class.room.delete');
            Route::get('/admin-edit-classrooms', 'editClassRoom')->name('ad.class.room.edit');
            Route::post('/admin-update-classrooms', 'updateClassRoom')->name('ad.class.room.update');

        });

        Route::controller(CourseController::class)->group(function () {
            Route::get('/list-courses', 'listCourses')->name('ad.list.courses');
            Route::get('/admin-courses', 'Courses')->name('ad.courses');
            Route::post('/admin-process-course', 'addCourse')->name('ad.course.add');
            Route::post('/admin-delete-course', 'deleteCourse')->name('ad.course.delete');
            Route::get('/admin-edit-course/{id}', 'editCourse')->name('ad.course.edit');
            Route::post('/admin-update-course', 'updateCourse')->name('ad.course.update');

        });

        Route::controller(ClassScheduleController::class)->group(function () {
            Route::get('/list-schedules', 'listSchedules')->name('ad.list.schedules');
            Route::get('/admin-schedules', 'Schedules')->name('ad.schedules');
            Route::post('/admin-process-schedule', 'addSchedule')->name('ad.schedule.add');
            Route::post('/admin-delete-schedule', 'deleteSchedule')->name('ad.schedule.delete');
            Route::get('/admin-edit-schedule/{id}', 'editSchedule')->name('ad.schedule.edit');
            Route::post('/admin-update-schedule', 'updateSchedule')->name('ad.schedule.update');
            Route::post('/admin-close-schedule', 'closeSchedule')->name('ad.schedule.close');


        });
    });
});

// user route
Route::prefix('user')->name('user.')->group(function(){
    Route::middleware(['guest:web'])->group(function () {
        Route::controller(UserController::class)->group(function(){
            Route::get('/student-login', 'loginPage')->name('user.login');
            Route::post('/student-login-process', 'processStudentLogin')->name('user.process.login');
            Route::get('/verify-student-email/{token}', 'VerifyStudentEmail')->name('user.verify.email');
            Route::get('/invalid-student', 'StudentInvalidToken')->name('user.invalid.token');
            Route::post('/resend-student-token', 'resendStudentToken')->name('user.resend.token');
            Route::get('/student-register', 'registerPage')->name('user.register');
            Route::post('/student-register-process', 'processStudentRegister')->name('user.process.register');
            Route::get('/student-registration-success', 'registrationSuccess')->name('user.registration.success');
        });

    });
    Route::middleware(['auth:web', 'is_locked_out'])->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/student-logout', 'logout')->name('user.logout');
        });
        Route::controller(UserDashboardController::class)->group(function () {
            Route::get('/student-dashboard', 'studentDashboard')->name('user.dashboard');
            Route::get('/student-profile', 'studentProfile')->name('user.profile');

            Route::post('/student-update-photo', 'updateProfilePhoto')->name('user.update.photo');
            Route::post('/student-update-details', 'updateStudentDetails')->name('user.update.details');
            Route::post('/student-update-password', 'updateStudentPassword')->name('user.update.password');

            Route::post('/student-login-time', 'updateStudentLastLogin')->name('user.update.login.time');
            Route::post('/student-start-class', 'joinClass')->name('user.start.class');

            Route::get('/get-schedules', 'getSchedulesforCalendar')->name('user.get.schedules.calendar');

        });
        Route::controller(AttendanceController::class)->group(function () {
            Route::post('/student-mark-attendance', 'markStudent')->name('user.mark.attendance');


        });
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
        Route::controller(LecturerDashboardController::class)->group(function () {
            Route::get('/lecturer-dashboard', 'lecturerDashboard')->name('lect.dashboard');
            Route::get('/lecturer-profile', 'lecturerProfile')->name('lect.profile');

            Route::post('/lect-update-photo', 'updateProfilePhoto')->name('lect.update.photo');
            Route::post('/lect-update-details', 'updateLecturerDetails')->name('lect.update.details');
            Route::post('/lect-update-password', 'updateLecturerPassword')->name('lect.update.password');

            Route::post('/lecturer-login-time', 'updateLecturerLastLogin')->name('lect.update.login.time');
            Route::get('/get-schedules', 'getSchedulesforCalendar')->name('lect.get.schedules.calendar');
            Route::post('/lecturer-close-previous-schedule', 'closePreviousSchedule')->name('lect.schedule.close.previous');
            Route::post('/lecturer-close-schedule', 'closeSchedule')->name('lect.schedule.close');
            Route::post('/lecturer-start-class', 'startClass')->name('lect.start.class');
            Route::post('/lecturer-auto-close-class', 'autoEndClass')->name('lect.auto.close.class');

        });

        Route::controller(AttendanceController::class)->group(function () {
            Route::post('/lecturer-toggle-attendance', 'toggleAttendance')->name('lect.toggle.attendance');
            Route::post('/lecturer-mark-student', 'markStudent')->name('lect.mark.student');
            Route::get('/lecturer-loggedinstudents/{schedule_id}', 'loggedInStudent')->name('lect.logged.in.students');
            Route::get('/lecturer-attendance', 'Attendance')->name('lect.attendance');
            Route::post('/lecturer-fetch-attendance', 'FetchAttendance')->name('lect.fetch.attendance');


        });
       
    });
});