<?php

namespace App\Http\Controllers;

use App\Models\EducationRecord;
use App\Models\FamilyRecord;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\StudentRecord;
use App\Models\Student;
use App\Models\SubmittedRequirements;
use App\Models\User;
use App\Models\Scholar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

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
            //Personal Information
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['required', 'string', 'max:255'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
            'birthdate' => ['required', 'date'],
            'birthplace' => ['required', 'string', 'max:255'],
            'age' => ['required', 'numeric'],
            'gender' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:255'],

            //Educaiton Information
            'education.elementary.name' => ['required', 'string'],
            'education.elementary.years' => ['', 'numeric'],
            'education.elementary.honors' => ['', 'string'],
            'junior.name' => ['', 'string',],
            'junior.years' => ['', 'numeric'],
            'junior.honors' => ['', 'string'],
            'senior.name' => ['', 'string'],
            'senior.years' => ['', 'numeric'],
            'senior.honors' => ['', 'string'],
            'college.name' => ['', 'string'],
            'college.years' => ['', 'numeric'],
            'college.honors' => ['', 'string'],
            'vocational.name' => ['', 'string'],
            'vocational.years' => ['', 'numeric'],
            'vocational.honors' => ['', 'string'],
            'postgrad.name' => ['', 'string'],
            'postgrad.years' => ['', 'numeric'],
            'postgrad.honors' => ['', 'string'],

            //Family Information
            'mother.first_name' => ['', 'string'],
            'mother.middle_name' => ['', 'string'],
            'mother.last_name' => ['', 'string'],
            'mother.age' => ['', 'string'],
            'mother.address' => ['', 'string'],
            'mother.citizenship' => ['', 'string'],
            'mother.occupation' => ['', 'string'],
            'mother.education' => ['', 'string'],
            'mother.batch' => ['string'],

            'father.first_name' => ['', 'string'],
            'father.middle_name' => ['', 'string'],
            'father.last_name' => ['', 'string'],
            'father.age' => ['', 'string'],
            'father.address' => ['', 'string'],
            'father.citizenship' => ['', 'string'],
            'father.occupation' => ['', 'string'],
            'father.education' => ['', 'string'],
            'father.batch' => ['string'],

            'marital_status' => ['required', 'string'],
            'monthly_income' => ['required', 'string'],
            'other_income' => ['required', 'string'],
            'family_housing' => ['required', 'string'],

            'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imgName' => 'required|string',
        ]);

        // Store the logo file in the local directory with a known path
        $logoFile = $request->file('img');

        // $logoFileName = $request->imgName;
        $originalFileName = $logoFile->getClientOriginalName();


        Storage::disk('public')->putFileAs('user/profile', $logoFile, $originalFileName);

        $education = [
            'elementary' => [
                'name' => $request->education['elementary']['name'],
                'years' => $request->education['elementary']['years'],
                'honors' => $request->education['elementary']['honors'],
            ],
            'junior' => [
                'name' => $request->education['junior']['name'],
                'years' => $request->education['junior']['years'],
                'honors' => $request->education['junior']['honors'],
            ],
            'senior' => [
                'name' => $request->education['senior']['name'],
                'years' => $request->education['senior']['years'],
                'honors' => $request->education['senior']['honors'],
            ],
            'college' => [
                'name' => $request->education['college']['name'],
                'years' => $request->education['college']['years'],
                'honors' => $request->education['college']['honors'],
            ],
            'vocational' => [
                'name' => $request->education['vocational']['name'],
                'years' => $request->education['vocational']['years'],
                'honors' => $request->education['vocational']['honors'],
            ],
            'postgrad' => [
                'name' => $request->education['postgrad']['name'],
                'years' => $request->education['postgrad']['years'],
                'honors' => $request->education['postgrad']['honors'],
            ],
        ];


        $mother = [
            'first_name' => $request->mother['first_name'],
            'middle_name' => $request->mother['middle_name'],
            'last_name' => $request->mother['last_name'],
            'age' => $request->mother['age'],
            'address' => $request->mother['address'],
            'citizenship' => $request->mother['citizenship'],
            'occupation' => $request->mother['occupation'],
            'education' => $request->mother['education'],
            'batch' => $request->mother['batch'],
        ];

        $father = [
            'first_name' => $request->father['first_name'],
            'middle_name' => $request->father['middle_name'],
            'last_name' => $request->father['last_name'],
            'age' => $request->father['age'],
            'address' => $request->father['address'],
            'citizenship' => $request->father['citizenship'],
            'occupation' => $request->father['occupation'],
            'education' => $request->father['education'],
            'batch' => $request->father['batch'],
        ];

        $password = Hash::make($request->password);

        $user = User::where('email', $request->email)->first();

        $user->update([
            'picture' => $originalFileName,
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
            'birthdate' => $request->birthdate,
            'placebirth' => $request->birthplace,
            'age' => $request->age,
            'gender' => $request->gender,
            'civil' => $request->civil_status,
            'religion' => $request->religion,
            'guardian' => $request->guardian_name,
            'relationship' => $request->relationship,
        ]);

        $studentrecord = StudentRecord::where('user_id', $user->id)->get();

        $studentrecordID = $studentrecord->pluck('id')->first();


        EducationRecord::create([
            'student_record_id' => $studentrecordID,
            'elementary' => json_encode($education['elementary']),
            'junior' => json_encode($education['junior']),
            'senior' => json_encode($education['senior']),
            'college' => json_encode($education['college']),
            'vocational' => json_encode($education['vocational']),
            'postgrad' => json_encode($education['postgrad']),
        ]);

        FamilyRecord::create([
            'student_record_id' => $studentrecordID,
            'mother' => json_encode($mother),
            'father' => json_encode($father),
            'marital_status' => $request->marital_status,
            'monthly_income' => $request->monthly_income,
            'other_income' => $request->other_income,
            'family_housing' => $request->family_housing,
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

        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->first();
        if (!$submittedRequirements) {
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
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $scholar = Scholar::where('email', Auth::user()->email)->first();

        return Inertia::render('Student/Profile/Scholar-Profile', [
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'scholar' => $scholar
        ]);
    }

    public function generate($urscholar_id)
    {
        // Find the scholar record
        $scholar = Scholar::where('urscholar_id', $urscholar_id)->firstOrFail();

        // Check if the scholar already has a QR code
        if ($scholar->qr_code) {
            return response()->json([
                'message' => 'QR code already exists.',
                'path' => asset('storage/qr_codes/' . $scholar->urscholar_id . '.png'),
                'filename' => $urscholar_id . '.png'
            ]);
        }

        // Set up QR code options
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 10,
            'imageBase64' => false,
        ]);

        // Data to encode in the QR code
        $qrData = json_encode([
            'id' => $scholar->urscholar_id,
            'name' => $scholar->first_name . ' ' . $scholar->last_name,
            'timestamp' => now()->timestamp,
        ]);

        // Generate the QR code
        $qrcode = (new QRCode($options))->render($qrData);

        // Define the file path
        $filename = $scholar->urscholar_id . '.png';

        // Save the QR code to storage
        Storage::disk('public')->put('qr_codes/' . $filename, $qrcode);

        // Update the scholar record with the QR code path
        $scholar->qr_code = $filename;
        $scholar->save();

        return response()->json([
            'message' => 'QR code generated successfully.',
            'path' => asset('storage/' . $filename),
            'filename' => $urscholar_id . '.png'
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

        return redirect()->route('student.scholarship');
    }

    public function register()
    {

        $student = StudentRecord::where('user_id', Auth::user()->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $scholar = Scholar::where('email', Auth::user()->email)->first();

        return Inertia::render('Student/Profile/Scholar-Profile', [
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'scholar' => $scholar
        ]);
    }
}