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
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'campus' => 'required',
        ]);

        // Check if student exists with the provided email
        $studentEmail = Student::where('email', $request->email)->first();

        // Check if student exists and belongs to the selected campus
        if ($studentEmail && $studentEmail->campus === $request->campus) {
            // Generate random password
            $password = Str::random(8);

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

            return redirect(route('dashboard', absolute: false))->with('success', 'Registration email sent successfully!');
        } else {
            // If email and campus don't match, return with error message
            return back()->withErrors([
                'email' => 'The provided email and campus do not match our records.',
            ])->withInput();
        }
    }
}
