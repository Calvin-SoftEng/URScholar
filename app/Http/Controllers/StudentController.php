<?php

namespace App\Http\Controllers;

use App\Models\EducationRecord;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\StudentRecord;
use App\Models\SubmittedRequirements;
use App\Models\User;
use App\Models\Scholar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
            'birthplace' => ['required', 'string', 'max:255'],
            'age' => ['required', 'numeric'],
            'gender' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:255'],
            'elementary.name' => ['', 'string'],
            'elementary.years' => ['', 'numeric'],
            'elementary.honors' => ['', 'string'],
            // 'junior.name' => ['required', 'string', 'max:255'],
            // 'junior.years' => ['required', 'numeric'],
            // 'junior.honors' => ['required', 'string', 'max:255'],
            // 'senior.name' => ['required', 'string', 'max:255'],
            // 'senior.years' => ['required', 'numeric'],
            // 'senior.honors' => ['required', 'string', 'max:255'],
            // 'college.name' => ['required', 'string', 'max:255'],
            // 'college.years' => ['required', 'numeric'],
            // 'college.honors' => ['required', 'string', 'max:255'],
            // 'vocational.name' => ['required', 'string', 'max:255'],
            // 'vocational.years' => ['required', 'numeric'],
            // 'vocational.honors' => ['required', 'string', 'max:255'],
            // 'postgrad.name' => ['required', 'string', 'max:255'],
            // 'postgrad.years' => ['required', 'numeric'],
            // 'postgrad.honors' => ['required', 'string', 'max:255'],
        ]);

        // dd($request->all());


        $elementary = [
            'name' => $request->elementary['name'],
            'year' => $request->elementary['years'],
            'honors' => $request->elementary['honors'],
        ];
        $password = Hash::make($request->password);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => $password,
            'email_verified_at' => $user->markEmailAsVerified()
        ]);

        StudentRecord::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'placebirth' => $request->birthplace,
            'age' => $request->age,
            'gender' => $request->gender,
            'civil' => $request->civil_status,
            'religion' => $request->religion,
            'guardian' => $request->guardian_name,
            'relationship' => $request->relationship,
        ]);

        $studentrecord = StudentRecord::where('user_id', $user->id)->get();

        EducationRecord::create([
            'student_record_id' => $studentrecord->pluck('id')->first(),
            'elem' => json_encode($elementary),
            // 'junior' => json_encode($request->junior),
            // 'senior' => json_encode($request->senior),
            // 'college' => json_encode($request->college),
            // 'vocational' => json_encode($request->vocational),
            // 'postgrad' => json_encode($request->postgrad),
        ]);

        event(new Verified($user));

        return redirect()->route('student.dashboard');
    }

    public function scholarship()
    {
        $scholar = Scholar::where('email', Auth::user()->email)->first();
        if (!$scholar) {
            return redirect()->route('student.confirmation');
        }

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();
        if (!$scholarship) {
            return redirect()->route('student.confirmation');
        }

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();
        $requirementIds = $requirements->pluck('id')->toArray();

        // Fetch only returned submitted requirements related to the scholarship
        $submitReq = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->where('status', 'Returned')
            ->whereIn('requirement_id', $requirementIds)
            ->get();

        // Map submitted requirements with their corresponding requirement details
        $returnedRequirements = $submitReq->map(function ($submitted) use ($requirements) {
            $requirement = $requirements->firstWhere('id', $submitted->requirement_id);
            return [
                'id' => $submitted->id,  // Submitted Requirement ID
                'requirement_id' => $requirement ? $requirement->id : null, // Requirement ID
                'requirement_name' => $requirement ? $requirement->requirements : 'Unknown Requirement',
                'status' => $submitted->status,
            ];
        });

        return Inertia::render('Student/Grant-in/Grant-In', [
            'scholarship' => $scholarship,
            'scholar' => $scholar,
            'submitReq' => $returnedRequirements,
        ]);
    }


    public function confirmation()
    {

        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

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

        $student = StudentRecord::where('user_id', Auth::user()->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();

        return Inertia::render('Student/Profile/Scholar-Profile', [
            'student' => $student,
            'education' => $education
        ]);
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
    
    public function applicationReupload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'requirements' => 'required|array',
            'requirements.*.id' => 'required|exists:submitted_requirements,id',
            'requirements.*.requirement' => 'required|string',
        ]);

        $scholar = Scholar::where('email', Auth::user()->email)->first();
        if (!$scholar) {
            return response()->json(['message' => 'Scholar not found'], 404);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $submissionId => $file) {
            $requirementData = collect($request->requirements)->firstWhere('id', $submissionId);
            if (!$requirementData) {
                continue;
            }

            $existingSubmission = SubmittedRequirements::where([
                'id' => $submissionId,
                'scholar_id' => $scholar->id,
                'status' => 'Returned'
            ])->first();

            if (!$existingSubmission) {
                continue;
            }

            // Delete old file if it exists
            if ($existingSubmission->path && Storage::disk('public')->exists($existingSubmission->path)) {
                Storage::disk('public')->delete($existingSubmission->path);
            }

            // Store the new file
            $path = $file->store('requirements/' . $scholar->id, 'public');

            // Update the existing record
            $existingSubmission->update([
                'submitted_requirements' => $file->getClientOriginalName(),
                'path' => $path,
                'status' => 'Pending',
                'message' => null,
            ]);

            $uploadedFiles[] = [
                'id' => $existingSubmission->id,
                'requirement_id' => $existingSubmission->requirement_id,
                'file_name' => $file->getClientOriginalName(),
                'status' => 'Pending',
            ];
        }

        if (empty($uploadedFiles)) {
            return response()->json([
                'message' => 'No valid returned requirements found to update',
            ], 422);
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }


    public function applicationUpload(Request $request)
    {

        $request->validate([
            'files.*' => 'required|file|',
            'req' => 'array'
        ]);



        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        $requirements = Requirements::where('id', $scholarship->id)->get();

        $reqID = $requirements->pluck('id')->first();



        $uploadedFiles = [];


        foreach ($request->file('files') as $index => $file) {

            $path = $file->store('requirements/' . $scholar->id, 'public');

            $uploadedFile = SubmittedRequirements::create([
                'scholar_id' => $scholar->id,
                'requirement_id' => $reqID,
                'submitted_requirements' => $file->getClientOriginalName(),
                'path' => $path
            ]);

            $uploadedFiles[] = $uploadedFile;
        }
    }
}