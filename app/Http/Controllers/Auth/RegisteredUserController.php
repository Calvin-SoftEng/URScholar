<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendEmail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $campus = Campus::all();

        return Inertia::render('Auth/Register', [
            'campus' => $campus,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function checkCredentials(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'campus' => 'required',
        ]);

        $campus = Campus::where('id', $request['campus'])->first();

        // Check if student exists with the provided email
        $studentEmail = Student::where('email', $request->email)->first();

        // Check if the campus exists
        if (!$campus) {
            return back()->withErrors([
                'campus' => 'The selected campus was not found.',
            ])->withInput();
        }

        // Check if student exists and belongs to the selected campus
        if (!$studentEmail || $studentEmail->campus_id !== $campus->id) {

            // If email doesn't exist or campus doesn't match, return with error message
            return back()->withErrors([
                'credentials' => 'The provided email and campus combination was not found in our system. Please verify your information or contact your campus administrator for assistance.',
            ])->withInput();
        }

        // If credentials are valid, proceed with the registration process
        return redirect()->route('register.proceed', [
            'email' => $request->email,
            'campus' => $campus
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
