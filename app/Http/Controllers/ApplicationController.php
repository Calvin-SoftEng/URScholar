<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholar;
use App\Models\Applicant;
use App\Models\Scholarship;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        $scholarships = Scholarship::all()->map(function ($scholarship) use ($user) {
            $isEligible = Scholar::where('email', $user->email)
                ->where('scholarship_id', $scholarship->id)
                ->exists();

            $scholarship->isEligible = $isEligible;
            return $scholarship;
        });


        return Inertia::render('Student/Scholarships', [
            'scholarships' => $scholarships,
            'userEmail' => Auth::user()->email,
        ]);
    }
    public function show(Scholarship $scholarship)
    {
        $applicants = $scholarship->applicants;

        return Inertia::render('Coordinator/Scholarships/Applicants', [
            'scholarship' => $scholarship,
            'applicants' => $applicants,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'course' => 'required|string|max:255',
            'file' => 'required|file|max:2048', // Max 2MB file
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        $filePath = $request->file('file')->store('applications');

        Applicant::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'course' => $request->course,
            'file_path' => $filePath,
            'scholarship_id' => $request->scholarship_id,
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
}
