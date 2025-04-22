<?php

use App\Http\Controllers\FeedController;
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
use App\Http\Controllers\MessageController;
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
    Route::post('/system_admin/univ-settings/portal-branding/store', [SystemAdminController::class, 'portal_branding_store'])->name('sa.portal_branding_store');


    // user settings
    Route::get('/system_admin/user-settings/system-users_roles', [SystemAdminController::class, 'system_user_roles'])->name('sa.user_roles');
    Route::get('/system_admin/user-settings/users', [SystemAdminController::class, 'system_users'])->name('sa.users');
    Route::post('/system_admin/user-settings/users/create', [SystemAdminController::class, 'create_users'])->name('sa.users_create');
    Route::put('/system_admin/user-settings/users/{user}/update', [SystemAdminController::class, 'update_users'])->name('sa.users_update');
    Route::put('/system_admin/user-settings/users/{id}/deactivate', [SystemAdminController::class, 'deactivateUser'])
        ->name('users.deactivate');
    Route::put('/system_admin/user-settings/users/{id}/activivate', [SystemAdminController::class, 'activateUser'])
        ->name('users.activateUser');
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
    Route::post('/academic/create-semester', [SystemAdminController::class, 'createAcademicSemester'])->name('sa.create-semester');

    // security and backup
    Route::get('/system_admin/security-and-backup/archives', [SystemAdminController::class, 'backup_and_restore'])->name('sa.archives');

    Route::get('/system_admin/my-account', [SystemAdminController::class, 'account'])->name('sa.account');

});

Route::middleware(['auth', 'usertype:super_admin,coordinator,cashier,student,sponsor,head_cashier'])->group(function () {
    // Main messaging index
    Route::get('/messaging', [MessageController::class, 'index'])->name('messaging.index');

    // Show specific batch or staff group
    Route::get('/messaging/scholarship/{scholarshipGroup}', [MessageController::class, 'showScholarshipGroup'])->name('messaging.scholarship');
    Route::get('/messaging/staff/{staffGroup}', [MessageController::class, 'showStaffGroup'])->name('messaging.staff');
    // Add this to your existing routes
    Route::get('/messaging/conversation/{userId}', [MessageController::class, 'showConversation'])->name('messaging.conversation');
    // Add this to your routes/web.php file

    Route::post('/messaging/get-members', [MessageController::class, 'getGroupMembers'])->name('messaging.getMembers');



    // Store new message
    Route::post('/messaging/send', [MessageController::class, 'storeMessage'])->name('messaging.store');

    //Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');

    Route::get('/student/feed', [FeedController::class, 'grantee_feed'])->name('feed.grantee_feed');

    Route::post('/posts', [FeedController::class, 'createPost'])->name('posts.create');
    Route::get('/posts', [FeedController::class, 'getPosts']);


    //Reports
    // Reports
    Route::get('/scholarships/{scholarship}/batch/{batch}/report', [ScholarshipController::class, 'downloadBatchReport']);
    Route::get('/scholarships/{scholarship}/enrollees-summary', [ReportsController::class, 'EnrolleesSummaryReport'])
        ->name('scholarships.enrollees-summary');
    Route::get('/scholarships/{scholarship}/enrolled-scholars', [ReportsController::class, 'EnrolledListReport'])
        ->name('scholarships.enrolled-scholars');
    Route::get('/scholarships/{scholarship}/graduate-scholars', [ReportsController::class, 'GraduateSummaryReport'])
        ->name('scholarships.graduate-scholars');
    Route::get('/scholarships/{scholarship}/transferred-grantees', [ReportsController::class, 'TransferredSummaryReport'])
        ->name('scholarships.transferred-scholars');
    Route::get('/scholarships/{scholarship}/payroll-report', [ReportsController::class, 'PayrollReport'])
        ->name('scholarships.payroll-report');

});


// SCHOLARSHIP STAFF -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:super_admin,coordinator'])->group(function () {

    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])
        ->name('staff.dashboard');
    Route::get('/staff/account-verify', [StaffController::class, 'verify_account'])
        ->name('staff.verify_account');
    // In web.php or routes file
    Route::post('/staff/verify-account/verifying', [StaffController::class, 'verifyAccount'])->name('staff_verify_account');

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


    //Scholarship One-Time
    Route::get('/scholarships/batch', [ScholarshipController::class, 'one_timebatch'])->name('scholarships.one_timebatch');

    // expand
    Route::get('/scholarships/submitted-requirements', [ScholarController::class, 'expand_requirements'])->name('requirements.expand_requirements');

    Route::post('/scholarships/{scholarship}/send-access/send', [EmailController::class, 'send'])->name('requirements.send');

    //ScholarshipsTabs
    Route::get('/scholarships/{scholarship}/requirements', [ScholarshipController::class, 'requirementsTab'])->name('requirementsTab.requirements');
    Route::get('/scholarships/{scholarship}/send-access', [EmailController::class, 'index'])->name('requirements.index');

    Route::post('/scholarship/forward-batches', [ScholarshipController::class, 'forward'])->name('scholars.forward');

    Route::get('/scholarships/scholar={id}', [ScholarController::class, 'scholar'])->name('scholarships.scholar_scholarship_details');
    Route::post('/scholarships/scholar={scholarID}/notify', [ScholarController::class, 'notifier'])->name('scholarships.scholar_notifier');
    Route::post('/scholarships/scholar/update-requirements', [ScholarController::class, 'updateStatus'])->name('scholarships.updateStatus');


    //Scholars
    Route::get('/urs-scholars', [ScholarController::class, 'scholars'])->name('scholars.show');

    Route::get('/urs-scholars/scholar-information/{scholar}', [ScholarController::class, 'scholar_information'])->name('scholars.scholar_information');

    Route::get('/scholarships/{scholarship}/adding-scholars', [ScholarController::class, 'adding'])->name('scholars.adding');

    Route::get('/scholarships/{scholarship}', [ScholarshipController::class, 'show'])->name('scholarship.show');
    Route::post('scholarships/{scholarshipId}/forward', [ScholarshipController::class, 'forward_coor'])->name('scholarship.forward_coor');
    Route::post('scholarships/{scholarshipId}/forward-sponsor', [ScholarshipController::class, 'forward_sponsor'])->name('scholarship.forward_sponsor');
    Route::post('scholarships/{scholarshipId}/forward-validate', [ScholarshipController::class, 'forward_validate'])->name('scholarship.forward_validate');



    Route::get('/scholarships/{scholarshipId}/batch/{batchId}', [ScholarshipController::class, 'batch'])->name('scholarship.batch');
    Route::post('/scholars/{id}/update-status', [ScholarController::class, 'updateStudent'])->name('scholars.update-status');
    Route::get('/scholarships/{scholarshipId}/batch/{batchId}/payroll', [ScholarshipController::class, 'student_payouts'])->name('scholarship.payrol');


    Route::post('/scholarships/{scholarship}/manual-upload', [ScholarController::class, 'manual'])->name('scholars.manual');

    Route::post('/scholarships/{scholarship}/checking-upload', [ScholarController::class, 'checking'])->name('scholars.checking');
    Route::post('/scholarships/{scholarship}/upload', [ScholarController::class, 'upload'])->name('scholars.upload');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'calendar'])->name('calendar.calendar');


    // Route::get('/group-pagee', [GroupPageController::class, 'index'])->name('grouppage.index');
    // Route::get('/group-pagee/{scholarship}', [GroupPageController::class, 'show'])->name('grouppage.show');
    // Route::post('/group-pagee/{scholarship}/messages', [MessageController::class, 'store'])->name('grouppage.store');

    //One-time Payment Applicants
    Route::get('/scholarships/{scholarshipId}/applicant', [ScholarshipController::class, 'onetime_list'])->name('scholarship.onetime_list');
    Route::post('/scholarships/{scholarshipId}/one-time/publish-applicants', [ScholarshipController::class, 'publishApplicantList'])->name('scholarships.publish-applicants');

    Route::get('/scholarships/{scholarshipId}/one-time/{batchId}', [ScholarshipController::class, 'showBatch'])->name('scholarship.onetime_batch');
    Route::get('/scholarships/one-time/scholars', [ScholarshipController::class, 'onetime_scholars'])->name('scholarship.onetime_scholars');

    Route::get('/scholarships/scholar={id}/one-time', [ScholarController::class, 'scholar_onetime'])->name('scholarship.applicant_details');
    Route::post('/scholarships/scholar/update-applicant', [ScholarController::class, 'updateApplicant'])->name('scholarships.updateApplicant');

    //Settings
    Route::get('/settings/sponsors', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/sponsors/create', [SettingsController::class, 'create_sponsor'])->name('settings.sponsor');
    Route::post('/settings/sponsors/moa', [SettingsController::class, 'storeMOA'])->name('sponsors.moa.store');
    Route::put('/settings/sponsors/{id}', [SettingsController::class, 'sponsor_update'])->name('settings.sponsors.update');

    Route::get('/settings/adding-students', [SettingsController::class, 'adding'])->name('settings.adding');
    Route::post('/settings/adding-students/store', [SettingsController::class, 'store_student'])->name('settings.store');

    Route::get('/settings/archives', [SettingsController::class, 'archives'])->name('settings.archives');
    Route::get('/settings/user-activities', [SettingsController::class, 'user_activities'])->name('settings.user_activities');

    // Eligibility & Conditions Routes
    Route::get('/settings/eligibilities-forms', [SettingsController::class, 'eligibilities_forms'])->name('settings.eligibilities_forms');
    Route::post('/settings/eligibilities', [SettingsController::class, 'eligibilities_store']);
    Route::put('/settings/eligibilities/{eligibility}', [SettingsController::class, 'eligibilities_update']);
    Route::delete('/settings/eligibilities/{eligibility}', [SettingsController::class, 'eligibilities_destroy']);


    // Restore routes for conditions and eligibilities
    Route::put('/settings/conditions/{condition}/restore', [SettingsController::class, 'conditions_restore'])->name('conditions.restore');
    Route::put('/settings/eligibilities/{eligibility}/restore', [SettingsController::class, 'eligibilities_restore'])->name('eligibilities.restore');

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
    Route::get('/payouts/{scholarshipId}/batch/{batchId}', [PayoutsController::class, 'student_payouts'])->name('payouts.payroll');
    Route::get('/payouts/list', [PayoutsController::class, 'payouts_list'])->name('payouts_list.payouts');

    // Route::get('/scholarships/{scholarship}/batch/{batch}/scholar-summary', [ScholarshipController::class, 'ScholarSummaryReport']);
    // Route::get('/scholarships/{scholarship}/batch/{batch}/grantee-summary', [ReportsController::class, 'GranteeSummaryReport']);
    Route::get('/scholarships/{scholarship}/batch/{batch}/enrollees-summary', [ReportsController::class, 'EnrolleesSummaryReport']);
    Route::get('/scholarships/{scholarship}/batch/{batch}/enrolled-scholars', [ReportsController::class, 'EnrolledListReport']);
    Route::get('/scholarships/{scholarship}/batch/{batch}/graduate-scholars', [ReportsController::class, 'GraduateSummaryReport']);
    Route::get('/scholarships/{scholarship}/batch/{batch}/payroll-report', [ReportsController::class, 'PayrollReport']);


});

// SPONSOR -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:sponsor'])->group(function () {

    Route::get('/sponsor/dashboard', [SponsorController::class, 'sponsor_dashboard'])->name('sponsor.dashboard');

    // view scholars
    Route::get('/sponsor/scholarships/{scholarship}', [SponsorController::class, 'view_scholars'])->name('sponsor.scholars');
    Route::get('/sponsor/scholarships/scholar/{id}', [SponsorController::class, 'sponsor_scholar'])->name('sponsor.sponsor_scholar');

    Route::get('/sponsor/my-account', [SponsorController::class, 'account'])->name('sponsor.account');
});

// CASHIER -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:cashier,head_cashier'])->group(function () {

    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    // scheduling
    Route::get('/cashier/scholarships/{scholarship}/schedule', [CashierController::class, 'scheduling'])->name('cashier.scheduling');
    Route::post('/cashier/scholarships/{scholarship}/notify', [EmailController::class, 'notify'])->name('cashier.notify');


    // Scholarship_Payouts
    // Route::get('/cashier/payout/active_scholarships', [CashierController::class, 'scholarships'])->name('cashier.active_scholarships');

    // Route::get('/cashier/scholarships', [CashierController::class, 'view_scholarship'])->name('cashier.view_scholarship');
    // Route::get('/cashier/scholarships/{scholarship}', [CashierController::class, 'all_payouts'])->name('cashier.all_payouts');
    // Route::post('/cashier/scholarship/forward-batches', [CashierController::class, 'forward'])->name('cashier.forward');

    // Route::get('/cashier/payouts/{payout}', [CashierController::class, 'payout_batches'])->name('cashier.payout_batches');
    // Route::post('/cashier/scholarships/{scholarshipId}/forward', [CashierController::class, 'forward_payout'])->name('cashier.forward_payout');

    // Route::get('/cashier/scholarships/{scholarshipId}/batch/{batchId}', [CashierController::class, 'student_payouts'])->name('cashier.payouts');
    // Route::post('/cashier/scholarships/{scholarshipId}/batch/{batchId}/submit-reason', [CashierController::class, 'submitReason'])->name('cashier.submit-reason');
    // Route::post('/cashier/scholarships/{scholarshipId}/batch/{batchId}/manual-claim', [CashierController::class, 'manualClaim'])->name('cashier.manual-claim');

    // Route::post('/cashier/verify-qr', [CashierController::class, 'verifyQr'])->name('cashier.verify_qr');
    // Route::post('/cashier/confirm-claim', [CashierController::class, 'confirmClaim'])->name('cashier.confirmClaim');
    // Route::post('/cashier/get-scholar-info', [CashierController::class, 'getScholarInfo'])->name('cashier.getScholarInfo');

    // Route::get('/cashier/scholarships/payroll', [CashierController::class, 'payrolls'])->name('cashier.payroll');

    // Route::get('/cashier/payouts', [CashierController::class, 'payouts_index'])->name('cashier.payouts_index');
    // Route::get('/cashier/payout/{scholarshipId}/batch/{batchId}', [CashierController::class, 'payouts_disbursement'])->name('cashier.payouts_disbursement');

    // Route::get('/cashier/pending-payouts/{scholarshipId}/batch/{batchId}', [CashierController::class, 'pending_payouts'])->name('cashier.pending_payouts');


    Route::get('/cashier/payouts/active_scholarships', [CashierController::class, 'scholarships'])->name('cashier.active_scholarships');

    Route::get('/cashier/scholarships', [CashierController::class, 'view_scholarship'])->name('cashier.view_scholarship');
    Route::get('/cashier/scholarships/{scholarship}', [CashierController::class, 'all_payouts'])->name('cashier.all_payouts');
    Route::post('/cashier/scholarship/forward-batches', [CashierController::class, 'forward'])->name('cashier.forward');

    Route::get('/cashier/payouts/{payout}', [CashierController::class, 'payout_batches'])->name('cashier.payout_batches');
    Route::post('/cashier/scholarships/{scholarshipId}/forward', [CashierController::class, 'forward_payout'])->name('cashier.forward_payout');

    Route::get('/cashier/scholarships/{scholarshipId}/batch/{batchId}', [CashierController::class, 'student_payouts'])->name('cashier.payouts');
    Route::post('/cashier/scholarships/{scholarshipId}/batch/{batchId}/submit-reason', [CashierController::class, 'submitReason'])->name('cashier.submit-reason');
    Route::post('/cashier/scholarships/{scholarshipId}/batch/{batchId}/manual-claim', [CashierController::class, 'manualClaim'])->name('cashier.manual-claim');

    Route::post('/cashier/verify-qr', [CashierController::class, 'verifyQr'])->name('cashier.verify_qr');
    Route::post('/cashier/confirm-claim', [CashierController::class, 'confirmClaim'])->name('cashier.confirmClaim');
    Route::post('/cashier/get-scholar-info', [CashierController::class, 'getScholarInfo'])->name('cashier.getScholarInfo');

    Route::get('/cashier/scholarships/payroll', [CashierController::class, 'payrolls'])->name('cashier.payroll');

    Route::get('/cashier/payrolls', [CashierController::class, 'payouts_index'])->name('cashier.payouts_index');
    // Route::get('/cashier/payrolls/{scholarshipId}/batch/{batchId}', [CashierController::class, 'payouts_disbursement'])->name('cashier.payouts_disbursement');

    Route::get('/cashier/payrolls/{scholarshipId}/batch/{batchId}', [CashierController::class, 'pending_payouts'])->name('cashier.pending_payouts');

});

// Staff and Cashier Profile -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::get('/account/profile', [ProfileController::class, 'view_profile'])->name('view.profile');


// New routes for email verification process
Route::post('/profile/send-old-email-code', [ProfileController::class, 'sendOldEmailCode'])->name('profile.send.old.email.code');
Route::post('/profile/verify-old-email', [ProfileController::class, 'verifyOldEmail'])->name('profile.verify.old.email');
Route::post('/profile/send-new-email-code', [ProfileController::class, 'sendNewEmailCode'])->name('profile.send.new.email.code');
Route::post('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.update.email');

// Password change routes
Route::post('/profile/send-password-code', [ProfileController::class, 'sendPasswordVerificationCode'])->name('profile.send.password.code');
Route::post('/profile/verify-password-code', [ProfileController::class, 'verifyPasswordCode'])->name('profile.verify.password.code');
Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');

Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/update/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.update.picture');


// STUDENT -------------------------------------------------------------------------------------------------------------------------------------------------------

Route::middleware(['auth', 'usertype:student', 'verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    // scholarship
    Route::get('/student/scholarship/{scholarship}', [StudentController::class, 'scholarship_apply_details'])->name('student.scholarship');
    Route::post('/student/application/re-upload', [StudentController::class, 'applicationReupload'])->name('student.application.reupload');

    Route::get('/student/confirmation', [StudentController::class, 'confirmation'])->name('student.confirmation');
    Route::get('/student/resubmission', [StudentController::class, 'resubmission'])->name('student.resubmission');
    Route::post('/student/application/upload', [StudentController::class, 'applicationUpload'])->name('student.application.upload');

    //profile
    Route::get('/myProfile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/myProfile/update', [StudentController::class, 'updateProfile'])->name('student.updateProfile');
    Route::get('/myProfile/generate/{urscholar_id}', [StudentController::class, 'generate'])->name('qrcode.generate');
    Route::post('/myProfile/{urscholar_id}/upload-grade', [StudentController::class, 'uploadGrade'])->name('student.uploadgrade');

    Route::get('/myAccount', [StudentController::class, 'account'])->name('student.account');


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
