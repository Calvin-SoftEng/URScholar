<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\ScholarshipController;
use App\Http\Controllers\Staff\ScholarController;
use App\Http\Controllers\Staff\MessageController;
use App\Http\Controllers\MISController;
use App\Http\Controllers\Staff\SettingsController;
use App\Http\Controllers\Staff\SponsorController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\SuperAdminController;
// use App\Http\Controllers\SystemAdminController;
use App\Http\Controllers\MIS\SystemAdminController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// })->name('welcome');

Route::get('/', [LandingPageController::class, 'index'])->name('welcome');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// MASTER ADMIN -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:system_admin'])->group(function () {

    Route::get('/system_admin/dashboard', [SystemAdminController::class, 'dashboard'])->name('system_admin.dashboard');

    // portal branding
    Route::get('/system_admin/univ-settings/portal-branding', [SystemAdminController::class, 'portal_branding'])->name('sa.portal_branding');

    // user settings
    Route::get('/system_admin/user-settings/system-users_roles', [SystemAdminController::class, 'system_user_roles'])->name('sa.user_roles');
    Route::get('/system_admin/user-settings/users', [SystemAdminController::class, 'system_users'])->name('sa.users');
    Route::get('/system_admin/user-settings/activity-logs', [SystemAdminController::class, 'activity_logs'])->name('sa.activity_logs');

    // univ settings
    Route::get('/system_admin/univ-settings/courses', [SystemAdminController::class, 'courses'])->name('sa.courses');
    Route::get('/system_admin/univ-settings/course/config/{campuses}', [SystemAdminController::class, 'course_config'])->name('sa.course_config');
    Route::post('/system_admin/univ-settings/course/config/{campuses}/store', [SystemAdminController::class, 'store_course_config'])->name('sa.course_config_store');

    Route::get('/system_admin/univ-settings/campuses', [SystemAdminController::class, 'campuses'])->name('sa.campuses');
    Route::post('/system_admin/univ-settings/campuses/store', [SystemAdminController::class, 'store_campus'])->name('sa.store_campus');
    Route::post('/system_admin/univ-settings/campuses/assign', [SystemAdminController::class, 'assign_campus'])->name('sa.assign_campus');

    Route::get('/system_admin/univ-settings/schoolyear-term', [SystemAdminController::class, 'sy_and_term'])->name('sa.sy_term');

});

// SCHOLARSHIP STAFF -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:super_admin,coordinator'])->group(function () {

    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])
        ->name('staff.dashboard');

    //Sponsors
    Route::get('/sponsors', [SponsorController::class, 'index'])->name('sponsor.index');
    Route::post('/sponsors/store', [SponsorController::class, 'store'])->name('sponsor.store');
    Route::get('/sponsors/create', [SponsorController::class, 'create'])->name('sponsor.create');


    //Scholarships
    Route::post('/sponsors/create-scholarship', [ScholarshipController::class, 'store'])->name('scholarships.store');

    Route::post('/sholarships/{scholarship}/one-time-payment', [ScholarshipController::class, 'one_time'])->name('scholarships.one_time');

    Route::get('/scholarships', [ScholarshipController::class, 'scholarship'])->name('scholarships.index');
    // Route::post('/scholarships', [ScholarshipController::class, 'store'])->name('scholarships.store');
    Route::put('/scholarships/{id}', [ScholarshipController::class, 'update'])->name('scholarships.update');


    // expand
    Route::get('/scholarships/submitted-requirements', [ScholarController::class, 'expand_requirements'])->name('requirements.expand_requirements');

    Route::post('/scholarships/{scholarship}/send-access/send', [EmailController::class, 'send'])->name('requirements.send');

    //ScholarshipsTabs
    Route::get('/scholarships/{scholarship}/requirements', [ScholarshipController::class, 'requirementsTab'])->name('requirementsTab.requirements');
    Route::get('/scholarships/{scholarship}/send-access', [EmailController::class, 'index'])->name('requirements.index');
    
    Route::post('/scholarship/forward-batches', [ScholarshipController::class, 'forward'])->name('scholars.forward');

    Route::get('/scholarships/scholar={id}', [ScholarController::class, 'scholar'])->name('scholarships.scholar_scholarship_details');
    Route::post('/scholarships/scholar/update-requirements', [ScholarController::class, 'updateStatus'])->name('scholarships.updateStatus');


    //Scholars
    Route::get('/urs-scholars', [ScholarController::class, 'scholars'])->name('scholars.show');

    Route::get('/urs-scholars/scholar-information', [ScholarController::class, 'scholar_information'])->name('scholars.scholar_information');

    Route::get('/scholarships/{scholarship}/adding-scholars', [ScholarController::class, 'adding'])->name('scholars.adding');

    Route::get('/scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarship.show');



    Route::get('/scholarships/{scholarshipId}/batch/{batchId}', [ScholarshipController::class, 'batch'])->name('scholarship.batch');


    Route::post('/scholarships/{scholarship}/manual-upload', [ScholarController::class, 'manual'])->name('scholars.manual');
    Route::post('/scholarships/{scholarship}/upload', [ScholarController::class, 'upload'])->name('scholars.upload');
    Route::get('/scholarships/{scholarship}/batch/{batch}/report', [ScholarshipController::class, 'downloadBatchReport']);




    // Messaging
    Route::get('/group-page', [MessageController::class, 'index'])->name('messaging.index');
    Route::post('/group-page/message', [MessageController::class, 'store'])->name('messaging.store');

    //Applicants
    Route::get('/scholarships/{scholarship}/applicants', [ApplicationController::class, 'show'])->name('scholarships.applicants');

    //Settings
    Route::get('/settings/sponsors', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/sponsors/create', [SettingsController::class, 'create_sponsor'])->name('settings.sponsor');

    Route::get('/settings/adding-students', [SettingsController::class, 'adding'])->name('settings.adding');
    Route::post('/settings/adding-students/store', [SettingsController::class, 'store_student'])->name('settings.store');

    Route::get('/settings/scholarship-forms', [SettingsController::class, 'scholarship_forms'])->name('settings.scholarship_forms');

});

// CASHIER -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:cashier'])->group(function () {

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');


    // Scholarship_Payouts
    Route::get('/cashier/scholarships', [CashierController::class, 'scholarships'])->name('cashier.active_scholarships');

    Route::get('/cashier/scholarships/{scholarship}', [CashierController::class, 'payout_batches'])->name('cashier.payout_batches');

    Route::get('/cashier/scholarships/{scholarshipId}/batch/{batchId}', [CashierController::class, 'student_payouts'])->name('cashier.payouts');



    Route::post('/cashier/verify-qr', [CashierController::class, 'verify_qr'])->name('cashier.verify_qr');


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

// Staff and Cashier Profile -------------------------------------------------------------------------------------------------------------------------------------------------------

    Route::get('/account/profile', [ProfileController::class, 'view_profile'])->name('view.profile');


// STUDENT -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:student', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // scholarship
    Route::get('/student/scholarship', [StudentController::class, 'scholarship'])->name('student.scholarship');
    Route::post('/student/application/re-upload', [StudentController::class, 'applicationReupload'])->name('student.application.reupload');

    Route::get('/student/scholarship/confirmation', [StudentController::class, 'confirmation'])->name('student.confirmation');
    Route::post('/student/application/upload', [StudentController::class, 'applicationUpload'])->name('student.application.upload');

    //profile
    Route::get('/myProfile', [StudentController::class, 'profile'])->name('student.profile');
    Route::get('/myProfile/generate/{urscholar_id}', [StudentController::class, 'generate'])->name('qrcode.generate');

    // Route::get('/student/scholarship', [StudentController::class, 'scholarship'])->name('student.scholarships');

    // application
    // Route::get('/student/application', [StudentController::class, 'application'])->name('student.application');



    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');
    Route::post('/verify-account/verifying', [StudentController::class, 'verifyingAccount'])->name('student.verify-account.verifying');


    Route::get('/available-scholarships', [ApplicationController::class, 'index'])->name('available.index');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('application.store');
    ;
});

Route::middleware(['auth'])->group(function () {

    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');

    // qrcode
    Route::get('/generate-qr', [QrCodeController::class, 'show']);
    Route::get('/download-qr', [QrCodeController::class, 'download'])->name('download.qr');

});


// Landing Page -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/applying-scholarship', [LandingPageController::class, 'scholarship_apply_details'])->name('landing_page.scholarship_apply_details');

require __DIR__ . '/auth.php';
