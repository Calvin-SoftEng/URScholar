<?php

use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ScholarController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MISController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SystemAdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// MASTER ADMIN -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:system_admin'])->group(function () {
    
    Route::get('/system_admin/dashboard', [SystemAdminController::class, 'dashboard'])->name('system_admin.dashboard');

    // univ settings
    Route::get('/mis/univ-settings/course', [SystemAdminController::class, 'course'])->name('mis.course');
    Route::get('/mis/univ-settings/course/config/{campuses}', [SystemAdminController::class, 'course_config'])->name('mis.course_config');
    Route::post('/mis/univ-settings/course/config/{campuses}/store', [SystemAdminController::class, 'store_course_config'])->name('mis.course_config');

    Route::get('/mis/univ-settings/campuses', [SystemAdminController::class, 'campuses'])->name('mis.campuses');
    Route::post('/mis/univ-settings/campuses/store', [SystemAdminController::class, 'store_campus'])->name('mis.store_campus');
    Route::post('/mis/univ-settings/campuses/assign', [SystemAdminController::class, 'assign_campus'])->name('mis.assign_campus');

    Route::get('/mis/univ-settings/schoolyear-term', [SystemAdminController::class, 'sy_term'])->name('mis.sy_term');

    // user settings
    Route::get('/mis/user-settings/user-roles', [SystemAdminController::class, 'roles'])->name('mis.roles');
    Route::get('/mis/user-settings/users', [SystemAdminController::class, 'users'])->name('mis.users');

});

// SCHOLARSHIP ADMIN -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:super_admin'])->group(function () {

    Route::get('/admin/dashboard', [SuperAdminController::class, 'dashboard'])
        ->name('super_admin.dashboard');

    //Admin-dashboard
    // Route::get('/admin/dashboard', [ScholarshipController::class, 'dashboard_scholarship'])->name('scholarships.dashboard_scholarship');

    //Sponsors
    Route::get('/sponsors', [SponsorController::class, 'index'])->name('sponsor.index');
    Route::post('/sponsors/store', [SponsorController::class, 'store'])->name('sponsor.store');
    Route::get('/sponsors/create', [SponsorController::class, 'create'])->name('sponsor.create');


    //Scholarships
    Route::post('/sponsors/create-scholarship', [ScholarshipController::class, 'store'])->name('scholarships.store');

    
    Route::get('/scholarships', [ScholarshipController::class, 'scholarship'])->name('scholarships.index');
    // Route::post('/scholarships', [ScholarshipController::class, 'store'])->name('scholarships.store');
    Route::put('/scholarships/{id}', [ScholarshipController::class, 'update'])->name('scholarships.update');

    // expand
    Route::get('/scholarships/submitted-requirements', [ScholarController::class, 'expand_requirements'])->name('requirements.expand_requirements');

    Route::post('/scholarships/{scholarship}/send-access/send', [EmailController::class, 'send'])->name('requirements.send');
    //ScholarshipsTabs
    Route::get('/scholarships/{scholarship}/requirements', [ScholarshipController::class, 'requirementsTab'])->name('requirementsTab.requirements');
    Route::get('/scholarships/{scholarship}/send-access', [EmailController::class, 'index'])->name('requirements.index');

    //Scholars
    Route::get('/scholarships/{scholarship}/adding-scholars', [ScholarController::class, 'adding'])->name('scholars.adding');

    Route::get('/scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarship.show');
    Route::post('/scholarships/{scholarship}/upload', [ScholarController::class, 'upload'])->name('scholars.upload');

    // Messaging
    Route::get('/group-page', [MessageController::class, 'index'])->name('messaging.index');
    Route::post('/group-page', [MessageController::class, 'store'])->name('messaging.store');

    //Applicants
    Route::get('/scholarships/{scholarship}/applicants', [ApplicationController::class, 'show'])->name('scholarships.applicants');

    //Settings
    Route::get('/settings/sponsors', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/sponsors/create', [SettingsController::class, 'create_sponsor'])->name('settings.sponsor');



    Route::get('/settings/adding-students', [SettingsController::class, 'adding'])->name('settings.adding');
    Route::post('/settings/adding-students/store', [SettingsController::class, 'store_student'])->name('settings.store');

    // Route::get('/scholarships/{scholarship}', [ScholarController::class, 'index'])->name('scholars.index');
    // Route::get('/coordinator/scholarships/scholars', [ScholarController::class, 'index'])->name('scholars.index');
    // Route::inertia('/coordinator/scholarships/scholars', 'Coordinator/Scholarships/Scholars')->name('scholars');

});

Route::middleware(['auth', 'usertype:student', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // scholarship
    Route::get('/student/scholarship', [StudentController::class, 'scholarship'])->name('student.scholarship');
    Route::get('/student/scholarship/confirmation', [StudentController::class, 'confirmation'])->name('student.confirmation');

    //profile
    Route::get('/myProfile', [StudentController::class, 'profile'])->name('student.profile');

    // Route::get('/student/scholarship', [StudentController::class, 'scholarship'])->name('student.scholarships');
    
    // application
    Route::get('/student/application', [StudentController::class, 'application'])->name('student.application');
    Route::post('/student/application/upload', [StudentController::class, 'applicationUpload'])->name('student.application.upload');


    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');


    Route::get('/available-scholarships', [ApplicationController::class, 'index'])->name('available.index');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('application.store');;
});

Route::middleware(['auth'])->group(function () {

    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');
    Route::post('/verify-account/verifying', [StudentController::class, 'verifyingAccount'])->name('student.verify-account.verifying');


});




require __DIR__ . '/auth.php';
