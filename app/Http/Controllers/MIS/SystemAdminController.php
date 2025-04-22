<?php

namespace App\Http\Controllers\MIS;

use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\Campus;
use App\Models\Course;
use App\Models\User;
use App\Models\Scholar;
use App\Models\PortalBranding;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Mail\SendEmail;
use App\Mail\SendUser;
use App\Models\Notification;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SystemAdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('MIS/Dashboard');
    }


    // system config ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function portal_branding()
    {

        $branding = PortalBranding::where('status', 'Active')->first();
        return Inertia::render('MIS/System_Config/Portal_Branding', [
            'branding' => $branding,
        ]);
    }

    public function portal_branding_store(Request $request)
    {
        $request->validate([
            'brandingName' => 'nullable|string|max:255',
            'logoLight' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logoDark' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
            'status' => 'required|in:Active,Inactive',
        ]);


        // Find existing active branding or create new
        $branding = PortalBranding::where('status', 'Active')->first();
        if (!$branding) {
            $branding = new PortalBranding();
        }

        // Update branding name
        $branding->branding_name = $request->brandingName;
        $branding->status = $request->status;

        // Handle the light mode logo
        if ($request->hasFile('logoLight')) {
            if ($branding->light_path && Storage::exists('public/' . $branding->light_path)) {
                Storage::delete('public/' . $branding->light_path);
            }

            $logoLightFile = $request->file('logoLight');
            $logoLightOriginalName = $logoLightFile->getClientOriginalName();
            $logoLightPath = $logoLightFile->storeAs('branding/logos', $logoLightOriginalName, 'public');

            $branding->logo_light = $logoLightOriginalName;
            $branding->light_path = $logoLightPath;
        }


        if ($request->hasFile('logoDark')) {
            if ($branding->dark_path && Storage::exists('public/' . $branding->dark_path)) {
                Storage::delete('public/' . $branding->dark_path);
            }

            $logoDarkFile = $request->file('logoDark');
            $logoDarkOriginalName = $logoDarkFile->getClientOriginalName();
            $logoDarkPath = $logoDarkFile->storeAs('branding/logos', $logoDarkOriginalName, 'public');

            $branding->logo_dark = $logoDarkOriginalName;
            $branding->dark_path = $logoDarkPath;
        }

        if ($request->hasFile('favicon')) {
            if ($branding->favicon_path && Storage::exists('public/' . $branding->favicon_path)) {
                Storage::delete('public/' . $branding->favicon_path);
            }

            $faviconFile = $request->file('favicon');
            $faviconOriginalName = $faviconFile->getClientOriginalName();
            $faviconPath = $faviconFile->storeAs('branding/favicons', $faviconOriginalName, 'public');

            $branding->favicon = $faviconOriginalName;
            $branding->favicon_path = $faviconPath;
        }


        $branding->save();

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Updated branding settings',
            'description' => 'Branding settings updated successfully.',
        ]);

        return redirect()->back()->with('success', 'Branding settings updated successfully');
    }

    // univ settings ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function courses()
    {
        $campuses = Campus::with('courses')->get();
        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('MIS/Univ_Settings/Course', [
            'campuses' => $campuses,
            'branding' => $branding,
        ]);
    }

    // campus ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function store_campus(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
        ]);


        Campus::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);


        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Created campus',
            'description' => 'Campus ' . $request->name . ' has been created.',
        ]);

        return redirect()->back()->with('success', 'Campus created successfully');
    }

    public function update_campus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:campuses,id',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $campus = Campus::findOrFail($request->id);

        $campus->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Updated campus',
            'description' => 'Campus ' . $request->name . ' has been updated.',
        ]);

        return redirect()->back()->with('success', 'Campus updated successfully');
    }

    public function assign_campus(Request $request, Campus $campus)
    {

        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'coor_id' => '',
            'cashier_id' => '',
        ]);

        $campus = Campus::findOrFail($request['campus_id']);

        $campus->update([
            'coordinator_id' => $request['coor_id'],
            'cashier_id' => $request['cashier_id']
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Assigned campus',
            'description' => 'Campus ' . $campus->name,
        ]);

        return redirect()->back()
            ->with('message', 'Focal persons assigned successfully');
    }

    public function campuses()
    {
        $campuses = Campus::all();
        $branding = PortalBranding::where('status', 'Active')->first();

        // Get coordinators that are only assigned to specific campuses
        $coor = User::where('usertype', 'coordinator')
            ->whereNotNull('campus_id')  // Only include users with a campus assignment
            ->orderBy('first_name')
            ->with('campus')
            ->get();

        // Get cashiers that are only assigned to specific campuses
        $cashier = User::where('usertype', 'cashier')
            ->whereNotNull('campus_id')  // Only include users with a campus assignment
            ->orderBy('first_name')
            ->with('campus')
            ->get();

        return Inertia::render('MIS/Univ_Settings/Campus', [
            'campuses' => Campus::with(['coordinator', 'cashier'])->get(),
            'coor' => $coor,
            'cashier' => $cashier,
            'branding' => $branding,
        ]);
    }

    // course ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function course_config(Campus $campuses)
    {

        $course = $campuses->courses;

        $branding = PortalBranding::where('status', 'Active')->first();
        return Inertia::render('MIS/Univ_Settings/CourseConfig', [
            'campuses' => $campuses,
            'courses' => $course,
            'branding' => $branding,
        ]);
    }

    public function store_course_config(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'abbreviation' => 'required|string',
        ]);

        Course::create([
            'campus_id' => $request->id,
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Created course',
            'description' => 'Course ' . $request->name . ' has been created.',
        ]);

        return redirect()->back()->with('success', 'Course created successfully');
    }

    public function sy_and_term()
    {
        $scholar_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('MIS/Univ_Settings/SY_Term', [
            'scholar_year' => $scholar_year,
            'branding' => $branding,
        ]);
    }

    /**
     * Create a new semester for the given school year and set it as active
     * 
     */
    public function createAcademicSemester(Request $request)
    {
        $request->validate([
            'schoolYearId' => 'required|exists:school_years,id',
        ]);

        $schoolYearId = $request->schoolYearId;
        $schoolYear = SchoolYear::with('academic_year')->findOrFail($schoolYearId);

        try {
            // First, set all existing academic years to inactive
            $activeAcademicYears = AcademicYear::where('status', 'Active')->get();

            foreach ($activeAcademicYears as $activeYear) {
                $activeYear->status = 'Inactive';
                $activeYear->save();
            }

            // Check if academic_year is null or empty
            if (!$schoolYear->academic_year || count($schoolYear->academic_year) === 0) {
                // Create 1st semester if no semesters exist
                $academicYear = new AcademicYear();
                $academicYear->school_year_id = $schoolYearId;
                $academicYear->semester = '1st';
                $academicYear->status = 'Active'; // Set as active
                $academicYear->save();

                // Reset all scholars' status to Unverified and student_status to Unenrolled
                Scholar::query()->update([
                    'status' => 'Unverified',
                    'student_status' => 'Unenrolled'
                ]);

                $notification = Notification::create([
                    'title' => 'New Academic Semester',
                    'message' => "You can now upload a new set of Students",
                    'type' => 'new_semester',
                ]);

                // Only get super_admin and coordinator users
                $users = User::whereIn('usertype', ['super_admin', 'coordinator'])
                    ->where('id', '!=', Auth::user()->id) // Exclude current user if they're an admin
                    ->get();

                // Attach users to the notification
                $notification->users()->attach($users->pluck('id'));

                event(new NewNotification($notification));

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'Created 1st semester',
                    'description' => '1st semester created successfully.',
                ]);

                return back()->with('success', '1st semester created successfully');
            }

            // Check if 1st semester exists but 2nd doesn't
            $firstSemesterExists = $schoolYear->academic_year->contains('semester', '1st');
            $secondSemesterExists = $schoolYear->academic_year->contains('semester', '2nd');

            if ($firstSemesterExists && !$secondSemesterExists) {
                // Create 2nd semester
                $academicYear = new AcademicYear();
                $academicYear->school_year_id = $schoolYearId;
                $academicYear->semester = '2nd';
                $academicYear->status = 'Active'; // Set as active
                $academicYear->save();

                // Reset all scholars' status to Unverified and student_status to Unenrolled
                Scholar::query()->update([
                    'status' => 'Unverified',
                    'student_status' => 'Unenrolled'
                ]);

                $notification = Notification::create([
                    'title' => 'New Academic Semester',
                    'message' => "You can now upload a new set of Students",
                    'type' => 'new_semester',
                ]);

                // Only get super_admin and coordinator users
                $users = User::whereIn('usertype', ['super_admin', 'coordinator'])
                    ->where('id', '!=', Auth::user()->id) // Exclude current user if they're an admin
                    ->get();

                // Attach users to the notification
                $notification->users()->attach($users->pluck('id'));

                event(new NewNotification($notification));

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'Created 2nd semester',
                    'description' => '2nd semester created successfully.',
                ]);


                return back()->with('success', '2nd semester created successfully');
            }

            // Both semesters already exist
            return response()->json([
                'success' => false,
                'message' => 'Both semesters already exist for this school year',
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create semester: ' . $e->getMessage(),
            ], 500);
        }
    }

    // users ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // public function user_roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

    // public function roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

    public function system_user_roles()
    {
        $branding = PortalBranding::where('status', 'Active')->first();
        return Inertia::render('MIS/User_Roles/Roles', [
            'branding' => $branding,
        ]);
    }

    public function system_users()
    {
        $currentUser = Auth::user();
        $branding = PortalBranding::where('status', 'Active')->first();

        $campuses = Campus::all();
        $users = User::where('id', '!=', $currentUser->id) // Exclude current user
            ->where('status', '!=', 'Inactive') // Exclude inactive users
            ->with('campus') // This eager loads the campus relationship
            ->get();

        return Inertia::render('MIS/User_Roles/Users', [
            'campuses' => $campuses,
            'users' => $users,
            'branding' => $branding,
        ]);
    }

    public function deactivateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 'Inactive';
            $user->save();

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Deactivated user',
                'description' => 'User ' . $user->name . ' has been deactivated.',
            ]);


            return back()->with('success', 'Deactivated user successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to deactivate user: ' . $e->getMessage());
        }
    }

    public function activateUser(Request $request, $id)
    {

        try {
            $user = User::findOrFail($id);

            $user->status = 'Active';
            $user->save();


            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Activated user',
                'description' => 'User ' . $user->name . ' has been activated.',
            ]);


            return back()->with('success', 'Activated user successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to deactivate user: ' . $e->getMessage());
        }
    }

    public function create_users(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'campus_id' => 'required',
            'role' => 'required',
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid string.',
            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid string.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'campus_id.required' => 'Please select a campus.',
            'role.required' => 'Please select a user role.',
        ]);

        // Check if the email already exists
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'The email has already been taken.'])->withInput();
        }

        $password = Str::random(8);

        $name = $request['first_name'] . " " . $request['last_name'];

        User::create([
            'email' => $request['email'],
            'name' => $name,
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'password' => bcrypt($password),
            'usertype' => $request['role'],
            'campus_id' => $request['campus_id'],
        ]);


        //Sending Emails
        $mailData = [
            'title' => 'Welcome to the URScholar Stakeholders sheesh – Your Login Credentials',
            'body' => "Dear " . $request['first_name'] . ",\n\n" .
                "Congratulations! You have been successfully registered for the scholarship application program.\n\n" .
                "Here are your login credentials:\n\n" .
                "*Email: " . $request['email'] . "\n" .
                "*Password: " . $password . "\n\n" .
                "*Next Steps:\n" .
                " - Log in to your account using the details above.\n" .
                " - Complete your application by submitting the required documents.\n" .
                " - Stay updated with announcements and notifications regarding your application status.\n\n" .
                "*Application Deadline: " . $request['deadline'] . "\n\n" .
                "Click the following link to access your portal: " .
                "urscholar.up.railway.app\n\n"
        ];


        Mail::to($request->email)->send(new SendUser($mailData));


        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Created user',
            'description' => 'User ' . $name . ' has been created.',
        ]);

        return back()->with('success', 'User created successfully');
    }

    public function update_users(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'campus_id' => 'required|exists:campuses,id',
            'role' => 'required|string|in:system_admin,super_admin,coordinator,cashier',
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'campus_id' => $validated['campus_id'],
            'usertype' => $validated['role'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Updated user',
            'description' => 'User ' . $validated['first_name'] . ' ' . $validated['last_name'] . ' has been updated.',
        ]);

        return back()->with('success', 'User updated successfully');
    }


    public function activity_logs()
    {
        $activity = ActivityLog::with('user')->get();
        $branding = PortalBranding::where('status', 'Active')->first();
        return Inertia::render('MIS/User_Roles/Activity_Logs', [
            'activity' => $activity,
            'branding' => $branding,
        ]);
    }


    // security and backup ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function backup_and_restore()
    {
        $currentUser = Auth::user();

        // Get system stakeholders (all user types except students)
        $stakeholders = User::where('id', '!=', $currentUser->id)
            ->where('status', '=', 'Inactive') // Get only inactive users for archive
            ->where('usertype', '!=', 'student') // Exclude students
            ->with('campus')
            ->get();

        // Get scholars (only student user type)
        $scholars = User::where('id', '!=', $currentUser->id)
            ->where('status', '=', 'Inactive') // Get only inactive users for archive
            ->where('usertype', '=', 'student') // Only students
            ->with('campus')
            ->get();

        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('MIS/Security&Backup/Backup_Restore', [
            'branding' => $branding,
            'stakeholders' => $stakeholders,
            'scholars' => $scholars,
        ]);
    }
    // public function roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

    public function account()
    {
        $user = Auth::user();
        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('MIS/Account_Settings', [
            'user' => $user,
            'branding' => $branding,
        ]);
    }

}
