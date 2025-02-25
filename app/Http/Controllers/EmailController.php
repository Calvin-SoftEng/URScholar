<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\User;
use App\Models\Scholar;
use Inertia\Inertia;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    public function index(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Staff/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function send(Scholarship $scholarship, Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'requirements' => 'required|array',
            'application' => 'required|date',
            'deadline' => 'required|date'
        ]);

        // dd($request->all());
        $scholars = Scholar::where('scholarship_id', $scholarship->id)->get();

        // Create the requirements for the scholarship
        $req = [];
        foreach ($request['requirements'] as $requirement) {

            $req[] = [
                'scholarship_id' => $scholarship->id,
                'requirements' => $requirement,
                'application_start' => $request['application'],
                'deadline' => $request['deadline'],
            ];

        }

        Requirements::insert($req);


        // Create the same requirement for all scholars
        foreach ($scholars as $scholar) {

            if ($scholar->email) {

                $userExists = User::where('email', $scholar->email)->exists();

                $password = Str::random(8);

                if (!$userExists) {
                    User::create([
                        'name' => $scholar['first_name'] . ' ' . $scholar['last_name'],
                        'email' => $scholar['email'],
                        'first_name' => $scholar['first_name'],
                        'last_name' => $scholar['last_name'],
                        'password' => bcrypt($password),
                    ]);
                }

                //Sending Emails
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
                        "https://youtu.be/cHSRG1mGaAo?si=pl0VL7UAJClvoNd5\n\n"
                ];


                Mail::to($scholar->email)->send(new SendEmail($mailData));

            }

        }

        return redirect()->route('requirements.index', $scholarship->id)->with('success', 'Messages has been sent to scholars');
    }
}
