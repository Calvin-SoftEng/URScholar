<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\SubmittedRequirements;
use App\Models\User;
use App\Models\Scholar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Student/Dashboard/Feed');
    }
    public function verifyAccount()
    {
        return Inertia::render('Student/VerificationAccount/Verification');
    }

    public function verifyingAccount(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
            
        ]);

        // dd($request->all());

        $password = Hash::make($request->password);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => $password,
            'email_verified_at' => $user->markEmailAsVerified()
        ]);
        
        event(new Verified($user));
        
        return redirect()->route('student.dashboard');
    }

    public function scholarship()
    {
        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        $requirements = Requirements::where('id', $scholarship->id)->get();

        $reqID = $requirements->pluck('id')->first();

        $submitRequirements = SubmittedRequirements::where('id', $reqID)->exists();


        if ($submitRequirements) {
            return Inertia::render('Student/Grant-in/Grant-In', [
            'scholarship' => $scholarship,
            'scholar' => $scholar,
            'requirements' => $requirements,
        ]);
        } 
        else {
            return redirect()->route('student.confirmation');
        }

        
    }

    public function confirmation()
    {

        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        $requirements = Requirements::where('id', $scholarship->id)->get();

        $reqID = $requirements->pluck('id')->first();

        $submitRequirements = SubmittedRequirements::where('id', $reqID)->exists();


        return Inertia::render('Student/Grant-in/Grant-In-Confirmation', [
            'scholarship' => $scholarship,
            'scholar' => $scholar,
            'requirements' => $requirements,
        ]);
    
    }

    public function profile()
    {
        return Inertia::render('Student/Profile/Scholar-Profile');
    }
    public function application(User $user)
    {

        // $scholars = $user->scholars;
        $scholars = Scholar::where('email', Auth::user()->email)->with('scholarship')->get();

        $scholarshipIds = $scholars->pluck('scholarship_id')->unique();
        $scholarships = Scholarship::whereIn('id', $scholarshipIds)->with('requirements')->get();

        $requirements = Requirements::where('scholarship_id', $scholarships->first()->id)->get();

        return Inertia::render('Student/Application/Application', [
            'scholars' => $scholars,
            'scholarships' => $scholarships,
            'requirements' => $requirements
        ]);
    }

    public function applicationUpload(Request $request)
    {

        $request->validate([
            'files.*' => 'required|file|',
        ]);

        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        $requirements = Requirements::where('id', $scholarship->id)->get();

        $reqID = $requirements->pluck('id')->first();


        $uploadedFiles = [];

        foreach ($request->file('files') as $index => $file) {
            if ($file) {
                $path = $file->store('requirements/' . Auth::user()->name, 'public');
                $uploadedFiles = [
                    'requirement_index' => $index,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName()
                ];
            }
        }

        // $requirement->update([
        //     'submitted_requirements' => json_encode($uploadedFiles)
        // ]);

        $doneSubmit = SubmittedRequirements::create([
            'scholar_id' => $scholar->id,
            'requirement_id' => $reqID,
            'submitted_requirements' => $uploadedFiles
        ]);

        if ($doneSubmit) {
            return redirect()->route('student.scholarship');
        }

    }
}