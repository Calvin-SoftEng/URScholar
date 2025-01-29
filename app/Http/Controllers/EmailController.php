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

        return Inertia::render('Super_Admin/Scholarships/SendingAccess', [
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

        Requirements::create([
            'scholarship_id' => $scholarship->id,
            'requirements' => $request['requirements'],
            'application_start' => $request['application'],
            'deadline' => $request['deadline'],
        ]);
        
        // Create the same requirement for all scholars
        foreach ($scholars as $scholar) {

            $userExists = User::where('email', $scholar['email'])->exists();

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
                'title' => 'Your Scholarship Application Login Details',
                'body' => "You have been successfully registered for the scholarship application. Your login credentials are:\n\n" .
                    "Email: " . $scholar['email'] . "\n" .
                    "Password: " . $password . "\n\n" .
                    "Please log in and complete the application process."
            ];

            Mail::to($scholar->email)->send(new SendEmail($mailData));
        }

        return redirect()->route('requirements.index', $scholarship->id)->with('success', 'Messages has been sent to scholars');
    }
}
