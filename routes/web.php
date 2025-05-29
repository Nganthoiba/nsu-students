<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/test', [HomeController::class, 'testApi']);

Route::get('/generatePDF', [PdfController::class, 'generatePDF'])->name('generatePDF');

Route::get('/home', [HomeController::class, 'showDashboard'])->name('home')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'showDashboard'])->name('dashboard')->middleware('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/createUser', [UserController::class, 'createUser']);

Route::get('/resetAllPasswords', [UserController::class, 'resetAllPasswords']);

Route::get('/courses/{short_name?}', [CourseController::class, 'getCourses']);
Route::get('/addCourse', [CourseController::class, 'addCourse']);
Route::get('/testDB', function() {    

    try {
        $connection = DB::connection();
        dd(DB::connection()->getDatabaseName());
    } catch (\Exception $e) {
        dd($e->getMessage());
    }    
});

Route::group(['prefix'=>'student'], function(){
    Route::get('/create', [StudentController::class, 'createStudent'])->name('createStudent');
    Route::post('/create', [StudentController::class, 'createStudent']);

    Route::get('/editStudent/{studentId}', [StudentController::class, 'editStudent'])->name('editStudent');
    Route::post('/editStudent/{studentId}', [StudentController::class, 'editStudent']);

    Route::post('/approve-student', [StudentController::class, 'approveStudent'])->name('approveStudent')->middleware('auth');
    Route::get('/list/{status?}', [StudentController::class, 'displayStudents'])->name('displayStudents')->middleware('auth');
    Route::get('/viewStudent/{studentId}', [StudentController::class, 'viewStudent'])->name('viewStudent')->middleware('auth');
    Route::get('/showStudentDetails/{studentId}', [StudentController::class, 'showStudentDetails'])->name('showStudentDetails');
    Route::get('/showCertificate/{studentId}/{type?}', [StudentController::class, 'showCertificate'])->name('showCertificate');
});

Route::group(['prefix'=>'excel'], function(){
    Route::post('/importStudents', [ExcelController::class, 'importStudents'])->name('excel.importStudents');
});
Route::group(['prefix'=>'settings'], function(){
    Route::get('/',[SettingController::class, 'userSetting'])->name('settings')->middleware('auth');
    Route::get('/changePassword', [SettingController::class, 'changePassword'])->name('setting.changePassword')->middleware('auth');
    Route::post('/changePassword', [SettingController::class, 'changePassword'])->middleware('auth');
});


