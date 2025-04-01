<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Campus;
use App\Models\Course;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Mail\SendEmail;
use App\Mail\SendUser;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SystemAdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('MIS/Dashboard');
    }


    // system config ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function portal_branding()
    {
        return Inertia::render('MIS/System_Config/Portal_Branding');
    }

    // univ settings ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function courses()
    {
        $campuses = Campus::with('courses')->get();

        return Inertia::render('MIS/Univ_Settings/Course', [
            'campuses' => $campuses,
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

        return redirect()->route('sa.campuses');
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

        return redirect()->back()
            ->with('message', 'Campus updated successfully');
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

        return redirect()->back()
            ->with('message', 'Focal persons assigned successfully');
    }

    public function campuses()
    {
        $campuses = Campus::all();

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
        ]);
    }

    // course ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function course_config(Campus $campuses)
    {

        $course = $campuses->courses;

        return Inertia::render('MIS/Univ_Settings/CourseConfig', [
            'campuses' => $campuses,
            'courses' => $course,
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

        return back();
    }

    public function sy_and_term()
    {
        $scholar_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        return Inertia::render('MIS/Univ_Settings/SY_Term', [
            'scholar_year' => $scholar_year,
        ]);
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
        return Inertia::render('MIS/User_Roles/Roles');
    }

    public function system_users()
    {
        $currentUser = Auth::user();

        $campuses = Campus::all();
        $users = User::whereNotIn('usertype', ['student'])
            ->where('id', '!=', $currentUser->id) // Exclude current user
            ->with('campus') // This eager loads the campus relationship
            ->get();

        return Inertia::render('MIS/User_Roles/Users', [
            'campuses' => $campuses,
            'users' => $users,
        ]);
    }

    public function create_users(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'campus_id' => 'required',
            'role' => 'required',
        ]);

        // dd($request);
        $userExists = User::where('email', $request->email)->exists();

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
                "https://youtu.be/cHSRG1mGaAo?si=pl0VL7UAJClvoNd5\n\n"
        ];


        Mail::to($request->email)->send(new SendUser($mailData));

        return back();

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

        return back()->with('success', 'User updated successfully');
    }


    public function activity_logs()
    {
        $activity = ActivityLog::with('user')->get();
        return Inertia::render('MIS/User_Roles/Activity_Logs', [
            'activity' => $activity,
        ]);
    }


    // security and backup ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function backup_and_restore()
    {
        return Inertia::render('MIS/Security&Backup/Backup_Restore');
    }

    // public function roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

}
