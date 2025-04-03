<?php

use App\Http\Controllers\GroupPageController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\ScholarshipController;
use App\Http\Controllers\Staff\ScholarController;
use App\Http\Controllers\Staff\MessageController;
use App\Http\Controllers\Staff\PayoutsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MISController;
use App\Http\Controllers\Staff\SettingsController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Sponsor\SponsorController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\NotificationController;
use App\Events\TestEvent;
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

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    Route::get('/notifications/test', [NotificationController::class, 'createTestNotification']);
});

// MASTER ADMIN -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:system_admin'])->group(function () {

    Route::get('/system_admin/dashboard', [SystemAdminController::class, 'dashboard'])->name('system_admin.dashboard');

    // portal branding
    Route::get('/system_admin/univ-settings/portal-branding', [SystemAdminController::class, 'portal_branding'])->name('sa.portal_branding');

    // user settings
    Route::get('/system_admin/user-settings/system-users_roles', [SystemAdminController::class, 'system_user_roles'])->name('sa.user_roles');
    Route::get('/system_admin/user-settings/users', [SystemAdminController::class, 'system_users'])->name('sa.users');
    Route::post('/system_admin/user-settings/users/create', [SystemAdminController::class, 'create_users'])->name('sa.users_create');
    Route::put('/system_admin/user-settings/users/{user}/update', [SystemAdminController::class, 'update_users'])->name('sa.users_update');
    Route::get('/system_admin/user-settings/activity-logs', [SystemAdminController::class, 'activity_logs'])->name('sa.activity_logs');

    // univ settings
    Route::get('/system_admin/univ-settings/courses', [SystemAdminController::class, 'courses'])->name('sa.courses');
    Route::get('/system_admin/univ-settings/course/config/{campuses}', [SystemAdminController::class, 'course_config'])->name('sa.course_config');
    Route::post('/system_admin/univ-settings/course/config/{campuses}/store', [SystemAdminController::class, 'store_course_config'])->name('sa.course_config_store');

    Route::get('/system_admin/univ-settings/campuses', [SystemAdminController::class, 'campuses'])->name('sa.campuses');
    Route::post('/system_admin/univ-settings/campuses/store', [SystemAdminController::class, 'store_campus'])->name('sa.store_campus');
    Route::post('/system_admin/univ-settings/campuses/update', [SystemAdminController::class, 'update_campus'])->name('sa.update_campus');
    Route::post('/system_admin/univ-settings/campuses/assign', [SystemAdminController::class, 'assign_campus'])->name('sa.assign_campus');

    Route::get('/system_admin/univ-settings/schoolyear-term', [SystemAdminController::class, 'sy_and_term'])->name('sa.sy_term');

    // security and backup
    Route::get('/system_admin/security-and-backup/archives', [SystemAdminController::class, 'backup_and_restore'])->name('sa.archives');

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
    Route::get('/scholarships/{scholarshipId}/batch/{batchId}/payroll', [ScholarshipController::class, 'student_payouts'])->name('scholarship.payrol');


    Route::post('/scholarships/{scholarship}/manual-upload', [ScholarController::class, 'manual'])->name('scholars.manual');

    Route::post('/scholarships/{scholarship}/checking-upload', [ScholarController::class, 'checking'])->name('scholars.checking');
    Route::post('/scholarships/{scholarship}/upload', [ScholarController::class, 'upload'])->name('scholars.upload');
    Route::get('/scholarships/{scholarship}/batch/{batch}/report', [ScholarshipController::class, 'downloadBatchReport']);

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'calendar'])->name('calendar.calendar');


    // Messaging
    Route::get('/group-page', [MessageController::class, 'index'])->name('messaging.index');
    Route::post('/group-page/message', [MessageController::class, 'oldstore'])->name('messaging.store');
    Route::get('/group-page/{scholarship}', [MessageController::class, 'show'])->name('messaging.show');

    // Route::get('/group-pagee', [GroupPageController::class, 'index'])->name('grouppage.index');
    // Route::get('/group-pagee/{scholarship}', [GroupPageController::class, 'show'])->name('grouppage.show');
    // Route::post('/group-pagee/{scholarship}/messages', [MessageController::class, 'store'])->name('grouppage.store');

    //One-time Payment Applicants
    Route::get('/scholarships/{scholarshipId}/applicant', [ScholarshipController::class, 'onetime_list'])->name('scholarship.onetime_list');
    Route::get('/scholarships/one-time/scholars', [ScholarshipController::class, 'onetime_scholars'])->name('scholarship.onetime_scholars');

    Route::get('/scholarships/{scholarshipId}/applicant/{id}', [ScholarshipController::class, 'applicant_details'])->name('scholarship.applicant_details');

    //Settings
    Route::get('/settings/sponsors', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/sponsors/create', [SettingsController::class, 'create_sponsor'])->name('settings.sponsor');

    Route::get('/settings/adding-students', [SettingsController::class, 'adding'])->name('settings.adding');
    Route::post('/settings/adding-students/store', [SettingsController::class, 'store_student'])->name('settings.store');

    // Eligibility & Conditions Routes
    Route::get('/settings/eligibilities-forms', [SettingsController::class, 'eligibilities_forms'])->name('settings.eligibilities_forms');
    Route::post('/settings/eligibilities', [SettingsController::class, 'eligibilities_store']);
    Route::put('/settings/eligibilities/{eligibility}', [SettingsController::class, 'eligibilities_update']);
    Route::delete('/settings/eligibilities/{eligibility}', [SettingsController::class, 'eligibilities_destroy']);

    Route::post('/settings/conditions', [SettingsController::class, 'conditions_store']);
    Route::put('/settings/conditions/{condition}', [SettingsController::class, 'conditions_update']);
    Route::delete('/settings/conditions/{condition}', [SettingsController::class, 'conditions_destroy']);

    Route::get('/settings/verification-forms', [SettingsController::class, 'verification_forms'])->name('settings.verification_forms');

    // Scholarship Forms
    Route::post('/settings/scholarship-forms', [SettingsController::class, 'store'])->name('scholarship.forms.store');
    Route::put('/settings/scholarship-forms{scholarshipForm}', [SettingsController::class, 'update'])->name('scholarship.forms.update');
    Route::delete('/scholarship-forms/{scholarshipForm}', [SettingsController::class, 'destroy'])->name('scholarship.forms.destroy');

    // Scholarship Form Data/Criteria
    Route::post('/settings/scholarship-forms/data', [SettingsController::class, 'storeData'])->name('scholarship.form.data.store');
    Route::put('/settings/scholarship-forms/data{scholarshipFormData}', [SettingsController::class, 'updateData'])->name('scholarship.form.data.update');
    Route::delete('/scholarship-form-data/{scholarshipFormData}', [SettingsController::class, 'destroyData'])->name('scholarship.form.data.destroy');

    Route::get('/payouts', [PayoutsController::class, 'payouts_index'])->name('payouts_index.payouts');
    Route::get('/payouts/list', [PayoutsController::class, 'payouts_list'])->name('payouts_list.payouts');


    // Reports
    Route::get('/scholarships/{scholarshipId}/batch/{batchId}/report', [ReportsController::class, 'generateReport']);
});

// SPONSOR -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:sponsor'])->group(function () {

    Route::get('/sponsor/dashboard', [SponsorController::class, 'sponsor_dashboard'])->name('sponsor.dashboard');

    // view scholars
    Route::get('/sponsor/scholarships/', [SponsorController::class, 'view_scholars'])->name('sponsor.scholarship');
});

// CASHIER -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:cashier'])->group(function () {

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    // scheduling
    Route::get('/cashier/scholarships/{scholarship}/schedule', [CashierController::class, 'scheduling'])->name('cashier.scheduling');
    Route::post('/cashier/scholarships/{scholarship}/notify', [CashierController::class, 'notify'])->name('cashier.notify');


    // Scholarship_Payouts
    Route::get('/cashier/scholarships', [CashierController::class, 'scholarships'])->name('cashier.active_scholarships');

    Route::get('/cashier/scholarships/{scholarship}', [CashierController::class, 'payout_batches'])->name('cashier.payout_batches');

    Route::get('/cashier/scholarships/{scholarshipId}/batch/{batchId}', [CashierController::class, 'student_payouts'])->name('cashier.payouts');
    Route::post('/cashier/scholarships/{scholarshipId}/batch/{batchId}/submit-reason', [CashierController::class, 'submitReason'])->name('cashier.submit-reason');

    // Messaging
    Route::get('/cashier/group-page', [CashierController::class, 'messaging'])->name('cashier.messaging');
    Route::post('/cashier/group-page/message', [CashierController::class, 'oldstore'])->name('cashier.messaging.store');
    Route::get('/cashier/group-page/{scholarship}', [CashierController::class, 'show'])->name('cashier.messaging.show');




    Route::post('/cashier/verify-qr', [CashierController::class, 'verify_qr'])->name('cashier.verify_qr');

});

// Staff and Cashier Profile -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/account/profile', [ProfileController::class, 'view_profile'])->name('view.profile');


// STUDENT -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:student', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // scholarship
    Route::get('/student/scholarship/{scholarship}', [StudentController::class, 'scholarship_apply_details'])->name('student.scholarship');
    Route::post('/student/application/re-upload', [StudentController::class, 'applicationReupload'])->name('student.application.reupload');

    Route::get('/student/confirmation', [StudentController::class, 'confirmation'])->name('student.confirmation');
    Route::post('/student/application/upload', [StudentController::class, 'applicationUpload'])->name('student.application.upload');

    //profile
    Route::get('/myProfile', [StudentController::class, 'profile'])->name('student.profile');
    Route::get('/myProfile/generate/{urscholar_id}', [StudentController::class, 'generate'])->name('qrcode.generate');

    // Messaging
    Route::get('/group-chat', [StudentController::class, 'messaging'])->name('student.messaging');
    Route::post('/group-chat/message', [StudentController::class, 'oldstore'])->name('student.messaging.store');
    Route::get('/group-chat/{scholarship}', [StudentController::class, 'show'])->name('student.messaging.show');


    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');
    Route::post('/verify-account/verifying', [StudentController::class, 'verifyingAccount'])->name('student.verify-account.verifying');


    Route::get('/available-scholarships', [ApplicationController::class, 'index'])->name('available.index');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('application.store');

    //Non -Scholars Scholarship Application

    //Listing
    Route::get('/available-scholarships', [StudentController::class, 'scholarships'])->name('scholarship.scholarships');

    //Details
    Route::get('/student/applying-scholarship/{scholarship}', [StudentController::class, 'scholarship_details'])->name('scholarship.details');

    // Application
    Route::get('/student/applying-scholarship/{scholarship}/application', [StudentController::class, 'scholarship_application'])->name('scholarship.application');
    Route::post('/student/applying-scholarship/{scholarship}/apply', [StudentController::class, 'submitApplication'])->name('scholarship.apply');
});

Route::middleware(['auth'])->group(function () {

    //VerifyAccount
    Route::get('/verify-account', [StudentController::class, 'verifyAccount'])->name('student.verify-account');

    // qrcode
    Route::get('/generate-qr', [QrCodeController::class, 'show']);
    Route::get('/download-qr', [QrCodeController::class, 'download'])->name('download.qr');

});


// Landing Page -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/applying-scholarship/{scholarship}', [LandingPageController::class, 'scholarship_apply_details'])->name('landing_page.scholarship_apply_details');

require __DIR__ . '/auth.php';
