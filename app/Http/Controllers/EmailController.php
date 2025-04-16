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
use App\Models\ScholarshipTemplate;
use App\Models\Payout;
use App\Models\ScholarshipGroup;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{
    public function index(Scholarship $scholarship, Request $request)
    {
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->get();

        $selectedYear = $request->input('selectedYear', '');
        $selectedSem = $request->input('selectedSem', '');
        // dd($semester, $schoolYear);

        // Initialize an empty collection for all scholars
        $allScholars = collect();

        // Loop through each batch and collect their scholars
        foreach ($batches as $batch) {
            $batchScholars = $scholarship->grantees()
                ->whereIn('status', ['Pending', 'Active'])
                ->where('batch_id', $batch->id)
                ->where('school_year_id', $selectedYear)
                ->where('semester', $selectedSem)
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
            'semester' => $selectedSem,
            'school_year' => $selectedYear,
        ]);
    }

    public function send(Scholarship $scholarship, Request $request)
    {
        // Validation rules
        $messages = [
            'required' => 'This field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'requirements' => 'required',
            'application' => 'required|date',
            'deadline' => 'required|date',
            'semester' => 'required',
            'school_year' => 'required',
            'templates.*' => 'nullable|file|max:10240', // Allow up to 10MB per file
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle requirements - may come as JSON string if using FormData
        $requirements = is_string($request->requirements)
            ? json_decode($request->requirements, true)
            : $request->requirements;

        // Find batches matching the specified semester and school year
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->school_year)
            ->get();

        if ($batches->isEmpty()) {
            return back()->with('flash', [
                'type' => 'error',
                'message' => "No batches found for the specified semester and school year.",
            ]);
        }

        // Process uploaded files
        $uploadedFiles = [];
        $storagePaths = [];

        if ($request->hasFile('templates')) {
            foreach ($request->file('templates') as $file) {
                // Generate a unique filename
                $filename =  $file->getClientOriginalName();

                // Store file in public storage for easy access - alternatively use private storage
                $path = $file->storeAs('scholarship_templates/' . $scholarship->id, $filename, 'public');

                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'storage_path' => storage_path('app/public/' . $path),
                    'mime' => $file->getMimeType(),
                ];

                $storagePaths[] = $path;
            }
        }

        // Create the requirements for the scholarship
        $req = [];
        foreach ($requirements as $requirement) {
            $req[] = [
                'scholarship_id' => $scholarship->id,
                'requirements' => $requirement,
                'date_start' => $request->application,
                'date_end' => $request->deadline,
                'total_scholars' => 0, // We'll update this after counting all scholars
            ];
        }

        // Store the requirements and get the IDs
        $requirementIds = [];
        foreach ($req as $requirement) {
            $newRequirement = Requirements::create($requirement);
            $requirementIds[] = $newRequirement->id;
        }

        // Store file information in database if needed
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                // Create a model to track uploaded templates
                ScholarshipTemplate::create([
                    'scholarship_id' => $scholarship->id,
                    'filename' => $file['name'],
                    'path' => $file['path'],
                    'mime_type' => $file['mime'],
                    'requirement_id' => $requirementIds[0], // Associate with first requirement or loop through requirements
                ]);
            }
        }

        $totalScholarsProcessed = 0;
        $emailsSent = 0;

        // Process each batch
        foreach ($batches as $batch) {

            $batch->status = 'Pending';
            $batch->save();

            // Retrieve scholars through grantees with necessary relationships
            $scholars = $scholarship->grantees()
                ->where('batch_id', $batch->id)
                ->where('semester', $request->semester)
                ->where('school_year_id', $request->school_year)
                ->whereIn('status', ['Pending', 'Active'])
                ->with('scholar.user', 'scholar.campus', 'scholar.course')
                ->get()
                ->map(fn($grantee) => $grantee->scholar)
                ->filter(); // Remove null scholars (if any)


            $totalScholarsProcessed += $scholars->count();

            // Process each scholar in the batch
            foreach ($scholars as $scholar) {
                if ($scholar && $scholar->email) {
                    $userExists = User::where('email', $scholar->email)->first();
                    $password = null;

                    if (!$userExists) {
                        $password = Str::random(8);
                        $user = User::create([
                            'name' => $scholar->first_name . ' ' . $scholar->last_name,
                            'email' => $scholar->email,
                            'first_name' => $scholar->first_name,
                            'last_name' => $scholar->last_name,
                            'password' => bcrypt($password),
                        ]);

                        // Update scholar's user_id
                        $scholar->update([
                            'user_id' => $user->id
                        ]);

                        // Check if scholarship group already exists
                        $scholarshipGroupExists = ScholarshipGroup::where('user_id', $user->id)
                            ->where('scholarship_id', $scholarship->id)
                            ->exists();

                        if (!$scholarshipGroupExists) {
                            ScholarshipGroup::create([
                                'user_id' => $user->id,
                                'scholarship_id' => $scholarship->id,
                                'batch_id' => $batch->id,
                                'campus_id' => $batch->campus_id
                            ]);
                        }
                    } else {
                        // If user already exists, update the scholar with existing user's ID if needed
                        if (!$scholar->user_id) {
                            $scholar->update([
                                'user_id' => $userExists->id
                            ]);
                        }

                        // Check if scholarship group already exists
                        $scholarshipGroupExists = ScholarshipGroup::where('user_id', $userExists->id)
                            ->where('scholarship_id', $scholarship->id)
                            ->exists();

                        if (!$scholarshipGroupExists) {
                            ScholarshipGroup::create([
                                'user_id' => $userExists->id,
                                'scholarship_id' => $scholarship->id,
                            ]);
                        }
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

                    // Create mailable instance
                    $email = new SendEmail($mailData);

                    // Attach files to the email
                    foreach ($uploadedFiles as $file) {
                        $email->attach($file['storage_path'], [
                            'as' => $file['name'],
                            'mime' => $file['mime'],
                        ]);
                    }

                    // Mail::to($scholar->email)->send(new SendEmail($mailData));
                    // Send email with attachments
                    Mail::to($scholar->email)->send($email);
                    $emailsSent++;
                }
            }
        }

        // Update the total scholars count for each requirement
        Requirements::whereIn('id', $requirementIds)->update(['total_scholars' => $totalScholarsProcessed]);

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Email',
            'description' => 'Scholar has been sent an email for scholarship ' . $scholarship->name,
        ]);

        return back()->with('flash', [
            'type' => 'success',
            'message' => "Successfully sent email to {$emailsSent} scholars out of {$totalScholarsProcessed} total scholars with " . count($uploadedFiles) . " attachment(s).",
        ]);
    }

    public function notify(Request $request, Scholarship $scholarship)
    {

        $validated = $request->validate([
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'payout_id' => 'required',
            'reminders' => 'required',
        ]);

        // dd($validated);
        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('id', $request['payout_id'])
            ->where('campus_id', Auth::user()->campus_id)
            ->first();

        $payout->status = 'Active';
        $payout->save();

        $disbursements = Disbursement::where('payout_id', $payout->id)->get();

        $scholarIds = $disbursements->pluck('scholar_id'); // Extract scholar IDs

        $scholars = Scholar::whereIn('id', $scholarIds)->get(); // Fetch scholars with matching IDs

        PayoutSchedule::create([
            'payout_id' => $request['payout_id'],
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
                        "urscholar.up.railway.app\n\n"
                ];

                Mail::to($scholar->email)->send(new SendEmail($mailData));
            }
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Email',
            'description' => 'Scholar has been sent an email for payouts ' . $scholarship->name,
        ]);

        return redirect()->route('cashier.payout_batches', ['payout' => $payout])->with('success', 'Schedule email sent successfully!');
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
                    "urscholar.up.railway.app\n\n"
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
                    "urscholar.up.railway.app\n\n"
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
