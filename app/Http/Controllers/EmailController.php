<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Disbursement;
use App\Models\PayoutSchedule;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\User;
use App\Models\Scholar;
use App\Models\Batch;
use Inertia\Inertia;
use App\Mail\SendEmail;
use App\Models\ActivityLog;
use App\Models\Payout;
use App\Models\ScholarshipGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{
    public function index(Scholarship $scholarship)
    {
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->with(['scholars.campus', 'scholars.course', 'scholars.user'])
            ->get();

        // Initialize an empty collection for all scholars
        $allScholars = collect();

        // Loop through each batch and collect their scholars
        foreach ($batches as $batch) {
            $batchScholars = $scholarship->grantees()
                ->where('batch_id', $batch->id)
                ->with('scholar.user', 'scholar.campus', 'scholar.course')
                ->get()
                ->map(fn($grantee) => $grantee->scholar)
                ->filter(); // Remove null scholars (if any)

            $allScholars = $allScholars->concat($batchScholars);
        }

        // Fetch requirements related to the scholarship
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        return Inertia::render('Staff/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'scholars' => $allScholars,
            'requirements' => $requirements,
        ]);
    }

    public function send(Scholarship $scholarship, Request $request)
    {
        // Validation rules
        $messages = [
            'required' => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'requirements' => 'required|array',
            'application' => 'required|date',
            'deadline' => 'required|date'
        ], $messages);

        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->with(['scholars.campus', 'scholars.course', 'scholars.user']) // Added user relationship
            ->first();


        // Retrieve scholars through grantees with necessary relationships
        $scholars = $scholarship->grantees()
            ->where('batch_id', $batch->id)
            ->with('scholar.user', 'scholar.campus', 'scholar.course')
            ->get()
            ->map(fn($grantee) => $grantee->scholar)
            ->filter(); // Remove null scholars (if any)

        // Create the requirements for the scholarship
        $req = [];
        foreach ($request['requirements'] as $requirement) {
            $req[] = [
                'scholarship_id' => $scholarship->id,
                'requirements' => $requirement,
                'date_start' => $request['application'],
                'date_end' => $request['deadline'],
                'total_scholars' => $scholars->count(),
            ];
        }

        Requirements::insert($req);

        // Create the same requirement for all scholars
        foreach ($scholars as $scholar) {
            if ($scholar->email) {
                $userExists = User::where('email', $scholar->email)->first();

                $password = Str::random(8);

                if (!$userExists) {
                    $user = User::create([
                        'name' => $scholar['first_name'] . ' ' . $scholar['last_name'],
                        'email' => $scholar['email'],
                        'first_name' => $scholar['first_name'],
                        'last_name' => $scholar['last_name'],
                        'password' => bcrypt($password),
                    ]);

                    // Update scholar's user_id
                    $scholar->update([
                        'user_id' => $user->id
                    ]);

                    ScholarshipGroup::create([
                        'user_id' => $user->id,
                        'scholarship_id' => $scholarship->id, // Assuming you have the scholarship
                    ]);
                } else {
                    // If user already exists, update the scholar with existing user's ID
                    $scholar->update([
                        'user_id' => $userExists->id
                    ]);
                }

                // Sending Emails
                $mailData = [
                    'title' => 'Welcome to the Scholarship Program – Your Login Credentials',
                    'body' => "Dear " . $scholar['first_name'] . ",\n\n" .
                        "Congratulations! You have been successfully registered for the scholarship application program.\n\n" .
                        "Here are your login credentials:\n\n" .
                        "*Email: " . $scholar['email'] . "\n" .
                        "*Password: " . $password . "\n\n" .
                        "*Next Steps:\n" .
                        " - Log in to your account using the details above.\n" .
                        " - Complete your application by submitting the required documents.\n" .
                        " - Stay updated with announcements and notifications regarding your application status.\n\n" .
                        "*Application Deadline: " . $request['deadline'] . "\n\n" .
                        "Click the following link to access your portal: " .
                        "https://urscholar.up.railway.app\n\n"
                ];

                Mail::to($scholar->email)->send(new SendEmail($mailData));
            }
        }

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Email',
            'description' => 'Scholar has been sent an email for scholarship ' . $scholarship->name,
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => "Successfully sent email to all scholars",
        ]);
    }

    public function notify(Request $request, Scholarship $scholarship)
    {

        $validated = $request->validate([
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'reminders' => 'required',
        ]);

        // dd($validated);
        $payout = Payout::where('scholarship_id', $scholarship->id)->first();

        $payout->status = 'Active';
        $payout->save();

        $disbursements = Disbursement::where('payout_id', $payout->id)->get();

        $scholarIds = $disbursements->pluck('scholar_id'); // Extract scholar IDs

        $scholars = Scholar::whereIn('id', $scholarIds)->get(); // Fetch scholars with matching IDs

        PayoutSchedule::create([
            'payout_id' => $payout->id,
            'scheduled_date' => $request['scheduled_date'],
            'scheduled_time' => $request['scheduled_time'],
            'reminders' => $request['reminders'],
        ]);


        // Create the same requirement for all scholars
        foreach ($scholars as $scholar) {
            if ($scholar->email) {
                //Sending Emails
                $mailData = [
                    'title' => 'Welcome to the Scholarship Program – Your Login Credentials',
                    'body' => "Dear " . $scholar['first_name'] . ",\n\n" .
                        "Congratulations! You have been successfully registered for the scholarship application program.\n\n" .
                        "Here are your login credentials:\n\n" .
                        "*Email: " . $scholar['email'] . "\n" .
                        "*Next Steps:\n" .
                        " - Log in to your account using the details above.\n" .
                        " - Complete your application by submitting the required documents.\n" .
                        " - Stay updated with announcements and notifications regarding your application status.\n\n" .
                        "*Bigayan  Date: " . $request['scheduled_date'] . "\n\n" .
                        "*Bigayan Oras: " . $request['scheduled_time'] . "\n\n" .
                        "*Reminders be: " . $request['reminders'] . "\n\n" .
                        "Click the following link to access your portal: " .
                        "https://youtu.be/cHSRG1mGaAo?si=pl0VL7UAJClvoNd5\n\n"
                ];

                Mail::to($scholar->email)->send(new SendEmail($mailData));
            }
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Email',
            'description' => 'Scholar has been sent an email for payouts ' . $scholarship->name,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'campus' => 'required',
        ]);

        $campus = Campus::where('id', $request['campus'])->first();

        // Check if student exists with the provided email
        $studentEmail = Student::where('email', $request->email)->first();

        // Check if student exists and belongs to the selected campus
        if ($studentEmail || $studentEmail->campus_id !== $campus->id) {
            // Generate random password
            $password = Str::random(8);

            $userExists = User::where('email', $studentEmail->email)->exists();

            if ($userExists) {
                return back()->withErrors([
                    'existing' => 'You have already registered for the scholarship program. Please check your email for login credentials.',
                ])->withInput();
            }

            // Create user account
            User::create([
                'name' => $studentEmail['first_name'] . ' ' . $studentEmail['last_name'],
                'email' => $studentEmail['email'],
                'first_name' => $studentEmail['first_name'],
                'last_name' => $studentEmail['last_name'],
                'campus' => $campus->id,
                'password' => Hash::make($password),
            ]);

            $mailData = [
                'title' => 'Welcome to the Scholarship Program – Your Login Credentials',
                'body' => "Dear " . $studentEmail['first_name'] . ",\n\n" .
                    "Congratulations! You have been successfully registered for the scholarship application program.\n\n" .
                    "Here are your login credentials:\n\n" .
                    "*Email: " . $studentEmail['email'] . "\n" .
                    "*Password: " . $password . "\n\n" .
                    "*Next Steps:\n" .
                    " - Log in to your account using the details above.\n" .
                    " - Complete your application by submitting the required documents.\n" .
                    " - Stay updated with announcements and notifications regarding your application status.\n\n" .
                    "*Application Deadline: " . ($request->has('deadline') ? $request['deadline'] : 'Please check the website for details') . "\n\n" .
                    "Click the following link to access your portal: " .
                    "https://youtu.be/cHSRG1mGaAo?si=pl0VL7UAJClvoNd5\n\n"
            ];

            // Send email
            Mail::to($studentEmail->email)->send(new SendEmail($mailData));

            // Update or create user account here if needed
            // You might want to save the hashed password to your users table

            //return redirect(route('dashboard', absolute: false))->with('success', 'Registration email sent successfully!');
            return back()->with('success', 'Registration email sent successfully!');
        } else {
            // If email and campus don't match, return with error message
            return back()->withErrors([
                'email' => 'The provided email and campus do not match our records.',
            ])->withInput();
        }
    }

    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'campus' => 'required',
        ]);

        dd($validated);

        // Check if student exists with the provided email
        $student = Student::where('email', $validated['email'])->first();

        // Check if student exists and belongs to the selected campus
        if ($student && $student->campus === $validated['campus']) {
            // Generate random password
            $password = Str::random(8);

            // Find and update user account if it exists
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                $user->update([
                    'password' => Hash::make($password),
                ]);
            } else {
                // Create new user if doesn't exist
                User::create([
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'password' => Hash::make($password),
                    // Add any other required fields
                ]);
            }

            $mailData = [
                'title' => 'Welcome to the Scholarship Program – Your Login Credentials',
                'body' => "Dear " . $student->first_name . ",\n\n" .
                    "Congratulations! You have been successfully registered for the scholarship application program.\n\n" .
                    "Here are your login credentials:\n\n" .
                    "Email: " . $student->email . "\n" .
                    "Password: " . $password . "\n\n" .
                    "Next Steps:\n" .
                    " - Log in to your account using the details above.\n" .
                    " - Complete your application by submitting the required documents.\n" .
                    " - Stay updated with announcements and notifications regarding your application status.\n\n" .
                    "Application Deadline: " . ($request->has('deadline') ? $request->deadline : 'Please check the website for details') . "\n\n" .
                    "Click the following link to access your portal: " .
                    "https://yourscholarshipportal.com/login\n\n"
            ];

            // Send email
            Mail::to($student->email)->send(new SendEmail($mailData));

            return back()->with('success', 'Registration email sent successfully!');
        } else {
            // If email and campus don't match, return with error message
            return back()->withErrors([
                'credentials' => 'The provided email and campus do not match our records.',
            ])->withInput();
        }
    }
}
