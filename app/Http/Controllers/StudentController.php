<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\NewNotification;
use App\Models\EducationRecord;
use App\Models\FamilyRecord;
use App\Models\Grade;
use App\Models\OrgRecord;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\SiblingRecord;
use App\Models\StudentRecord;
use App\Models\Criteria;
use App\Models\CampusRecipients;
use App\Models\Batch;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Student;
use App\Models\SubmittedRequirements;
use App\Models\User;
use App\Models\Scholar;
use App\Models\SchoolYear;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $scholar = Scholar::where('email', Auth::user()->email)->first();
        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        if ($scholarship) {
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

            $submitPending = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->where('status', 'Pending')
            ->whereIn('requirement_id', $requirementIds)
            ->get();

            $submitApproved = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->where('status', 'Approved')
            ->whereIn('requirement_id', $requirementIds)
            ->get();

            return Inertia::render('Student/Dashboard/Dashboard', [
                'scholarship' => $scholarship,
                'scholar' => $scholar,
                'submitReq' => $returnedRequirements,
                'submitPending' => $submitPending,
                'submitApproved' => $submitApproved,
            ]);
        }

        $scholarships = Scholarship::where('scholarshipType', 'One-time Payment')->get();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();

        return Inertia::render('Student/Dashboard/Dashboard', [
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'schoolyears' => $schoolyear,
        ]);
    }

    public function scholarship_apply_details(Scholarship $scholarship)
    {

        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();

        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->first();

        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();
        $grade = Criteria::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Student/Scholarships/ScholarshipDetails', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
        ]);
    }
    public function verifyAccount()
    {
        $user = User::where('email', Auth::user()->email)->first();
        $scholar = Scholar::where('email', Auth::user()->email)->first();

        if ($scholar) {
            $batch = Batch::where('scholarship_id', $scholar->scholarship_id)->first();

            // Get the batch semester logic
            $batch_semester = null;
            $batch_school_year = null;

            if ($batch) {
                if ($batch->semester == '2nd') {
                    $batch_semester = '1st';
                    $school_year = $batch->school_year; // Keep the same school year
                } else if ($batch->semester == '1st') {
                    $batch_semester = '2nd';

                    if ($batch->school_year == 1) {
                        $batch_school_year = 1; // First school year
                    } else {
                        $batch_school_year = $batch->school_year - 1; // Previous school year
                    }
                }
            }

            $school_year = SchoolYear::where('id', $batch_school_year)->first();
        } else {
            $batch = null;
            $batch_semester = null;
            $school_year = null;
        }


        // if ($school_year){
        //     $school_year = SchoolYear::where('id', $batch->school_year)->first();
        // }
        // else {
        //     $school_year = 'N/A';
        // }

        return Inertia::render('Student/VerificationAccount/Verification', [
            'user' => $user,
            'scholar' => $scholar,
            'batch' => $batch,
            'batch_semester' => $batch_semester,
            'school_year' => $school_year,
        ]);
    }

    public function verifyingAccount(Request $request)
    {
        // $messages = [
        //     'education.elementary.name.required' => 'The elementary school name field is required.',
        //     'education.elementary.years.required' => 'The elementary school years field is required.',
        //     'education.elementary.honors.required' => 'The elementary school honors field is required.',
        //     // Add more custom messages as needed
        // ];

        $messages = [
            'required' => 'This field is required.', // Generic for all required fields
            'confirm_password.same' => 'Passwords must match.',
        ];

        $validator = Validator::make($request->all(), [
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
            'street' => ['required', 'string', 'max:255'],
            'municipality' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'religion' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:255'],

            //Grade Information
            'grade' => [''],
            'cog' => [''],
            'school_year' => [''],
            'semester' => [''],

            //Educaiton Information
            'education.elementary.name' => ['required', 'string'],
            'education.elementary.years' => ['required', 'string'],
            'elementary.honors' => ['string'],
            'education.junior.name' => ['required', 'string',],
            'education.junior.years' => ['required', 'string'],
            'education.senior.name' => ['', 'string'],
            'education.senior.years' => ['', 'string'],
            'education.senior.honors' => ['string'],
            'education.college.name' => ['', 'string'],
            'education.college.years' => ['', 'string'],
            'education.college.honors' => ['string'],
            'education.vocational.name' => ['string'],
            'education.vocational.years' => ['string'],
            'education.vocational.honors' => ['string'],
            'education.postgrad.name' => ['string'],
            'education.postgrad.years' => ['string'],
            'education.postgrad.honors' => ['string'],

            //Family Information
            'mother.first_name' => ['required', 'string'],
            'mother.middle_name' => ['required', 'string'],
            'mother.last_name' => ['required', 'string'],
            'mother.age' => ['', 'string'],
            'mother.address' => ['', 'string'],
            'mother.citizenship' => ['', 'string'],
            'mother.occupation' => ['', 'string'],
            'mother.education' => ['', 'string'],
            'mother.batch' => [''],

            'father.first_name' => ['', 'string'],
            'father.middle_name' => ['', 'string'],
            'father.last_name' => ['', 'string'],
            'father.age' => ['', 'string'],
            'father.address' => ['', 'string'],
            'father.citizenship' => ['', 'string'],
            'father.occupation' => ['', 'string'],
            'father.education' => ['', 'string'],
            'father.batch' => [''],

            'siblings' => [''],
            'siblings.*' => [''],

            'marital_status' => ['required', 'string'],
            'monthly_income' => ['required', 'string'],
            'other_income' => [''],
            'family_housing' => ['required', 'string'],

            'organizations' => [''],
            'organizations.*' => [''],


            'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imgName' => 'required|string',
        ], $messages);


        // dd($request->all());

        // Custom error message handling to combine related fields
        if ($validator->fails()) {
            $errors = $validator->errors();

            // Check if any elementary education fields failed validation
            if (
                $errors->has('education.elementary.name') ||
                $errors->has('education.elementary.years')
            ) {

                // Create a single combined error message
                $errorMessage = 'Please complete all elementary education information.';

                // Remove the individual error messages
                $errors->forget('education.elementary.name');
                $errors->forget('education.elementary.years');

                // Add the combined error
                $errors->add('education.elementary', $errorMessage);
            }

            //junior
            if (
                $errors->has('education.junior.name') ||
                $errors->has('education.junior.years')
            ) {

                // Create a single combined error message
                $errorMessage = 'Please complete all junior education information.';

                // Remove the individual error messages
                $errors->forget('education.junior.name');
                $errors->forget('education.junior.years');

                // Add the combined error
                $errors->add('education.junior', $errorMessage);
            }

            //senior
            if (
                $errors->has('education.senior.name') ||
                $errors->has('education.senior.years')
            ) {

                // Create a single combined error message
                $errorMessage = 'Please complete all senior education information.';

                // Remove the individual error messages
                $errors->forget('education.senior.name');
                $errors->forget('education.senior.years');

                // Add the combined error
                $errors->add('education.senior', $errorMessage);
            }

            //college
            if (
                $errors->has('education.college.name') ||
                $errors->has('education.college.years')
            ) {

                // Create a single combined error message
                $errorMessage = 'Please complete all college education information.';

                // Remove the individual error messages
                $errors->forget('education.college.name');
                $errors->forget('college.years');

                // Add the combined error
                $errors->add('education.college', $errorMessage);
            }

            // Apply the generic required message to all fields dynamically
            foreach ($errors->messages() as $field => $messages) {
                if (in_array("The $field field is required.", $messages)) {
                    $errors->forget($field);
                    $errors->add($field, 'This field is required.');
                }
            }

            // Return with the modified errors
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        $scholar = Scholar::where('email', $request->email)->first();

        $file = $request->file('cog');

        // dd($file);
        // dd($request['grade']);

        if ($file) {
            $originalFileName = $request->file('cog')->getClientOriginalName();
            $extension = $request->file('cog')->getClientOriginalExtension();
            $newFileName = $scholar->urscholar_id . $extension;

            $filePath = $request->file('cog')->storeAs('scholar/grade', $newFileName, 'public');

            $testing = Grade::create([
                'scholar_id' => $scholar->id,
                'grade' => $request->grade,
                'cog' => $originalFileName,
                'path' => $filePath,
                'school_year' => $request->school_year,
                'semester' => $request->semester,
            ]);
        } else {

        }

        // Store the logo file in the local directory with a known path
        $logoFile = $request->file('img');

        // $logoFileName = $request->imgName;
        $originalFileName = $logoFile->getClientOriginalName();

        //Scholar creation

        if (!$scholar) {

            $student = Student::where('email', $user->email)->first();

            // Get the next available urscholar_id
            $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
                ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
                ->value('urscholar_id');

            $nextId = 1; // Default starting number
            if ($highestId) {
                $currentNumber = (int) substr($highestId, 4);
                $nextId = $currentNumber + 1;
            }

            // Generate urscholar_id with leading zeros (URS-0001 format)
            $urscholarId = 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            $nextId++;


            Scholar::create([
                'user_id' => $user->id,
                'hei_name' => 'University of Rizal System',
                'campus_id' => $student->campus_id,
                'course_id' => $student->course_id,
                'urscholar_id' => $urscholarId,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'sex' => $request->gender,
                'birthdate' => $request->birthdate,
                'year_level' => $student->year_level,
                'street' => $request->street,
                'municipality' => $request->municipality,
                'province' => $request->province,
                'email' => $request->email,
                'status' => 'Verified',
            ]);
        }



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

        $familyID = FamilyRecord::where('student_record_id', $studentrecordID)->get();


        foreach ($request->siblings as $index => $sibling) {
            // Skip this iteration if all inputs are null
            if (
                is_null($sibling['first_name']) && is_null($sibling['last_name']) && is_null($sibling['middle_name'])
                && is_null($sibling['age']) && is_null($sibling['occupation'])
            ) {
                continue;
            }

            SiblingRecord::create([
                'family_record_id' => $familyID->pluck('id')->first(),
                'first_name' => $sibling['first_name'],
                'last_name' => $sibling['last_name'],
                'middle_name' => $sibling['middle_name'],
                'age' => $sibling['age'],
                'occupation' => $sibling['occupation'],
            ]);
        }

        foreach ($request->organizations as $index => $org) {
            OrgRecord::create([
                'student_record_id' => $studentrecordID,
                'name' => $org['name'],
                'year' => $org['membership_dates'],
                'position' => $org['position'],
            ]);
        }

        event(new Verified($user));

        if ($scholar) {
            return redirect()->route('student.confirmation');
        } else {
            return redirect()->route('student.dashboard');
        }

    }

    public function scholarship()
    {
        $scholar = Scholar::where('email', Auth::user()->email)->first();
        if (!$scholar) {
            return redirect()->route('scholarship.scholarships');
        }

        $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();
        if (!$scholarship) {
            return redirect()->route('scholarship.scholarships');
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

    public function scholarships()
    {
        $scholarships = Scholarship::where('scholarshipType', 'One-time Payment')->get();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();

        return Inertia::render('Student/Scholarships/Scholarships', [
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'schoolyears' => $schoolyear,
        ]);
    }

    public function profile()
    {
        $student = StudentRecord::where('user_id', Auth::user()->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $scholar = Scholar::where('email', Auth::user()->email)->with('course', 'campus')->first();

        if ($scholar) {
            $grade = Grade::where('scholar_id', $scholar->id)->first();
        } else {
            $grade = null;
            $scholar = null;
        }



        return Inertia::render('Student/Profile/Scholar-Profile', [
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'scholar' => $scholar,
            'grade' => $grade
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

        return back()->with('success', 'QR code generated successfully.');

        // return response()->json([
        //     'message' => 'QR code generated successfully.',
        //     'path' => asset('storage/' . $filename),
        //     'filename' => $urscholar_id . '.png'
        // ]);
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

        return back();
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

        return redirect()->route('student.dashboard');
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

    // public function scholarship_application()
    // {
    //     return Inertia::render('Student/Application/Scholar_Application');
    // }

    public function scholarship_details(Scholarship $scholarship)
    {
        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();

        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->first();

        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();
        $grade = Criteria::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Student/Scholarships/ScholarshipDetails', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
        ]);
    }

    public function scholarship_application(Scholarship $scholarship)
    {
        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();

        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->first();

        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();

        $grade = Criteria::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Student/Scholarships/ScholarshipApplication', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
        ]);
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'essay' => 'required|string',
            'files.*' => 'required|file|',
            'req' => 'array'
        ]);

        $scholar = Scholar::where('email', Auth::user()->email)->first();

        $scholarship = Scholarship::where('id', $request['scholarship_id'])->first();

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

        return back()
            ->with('success', 'Your scholarship application has been submitted successfully!');
    }

    public function messaging(User $user)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Return the chat page using Inertia
        return Inertia::render('Student/Messaging/Messaging', [
            'messages' => [],
            'currentUser' => $currentUser,
            'scholarships' => $scholarships,
            'selectedScholarship' => [],
        ]);
    }

    public function show(Scholarship $scholarship)
    {

        // Get all messages with the user who sent them (eager loading)
        $messages = Message::with(['user', 'scholarship'])
            ->where('scholarship_id', $scholarship->id)
            ->latest()
            ->get();

        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        $selectedScholarship = $scholarship;


        // Return the chat page using Inertia, passing the messages and user data
        return Inertia::render('Student/Messaging/Messaging', [
            'messages' => $messages,
            'currentUser' => Auth::user(),
            'scholarships' => $scholarships,
            'selectedScholarship' => $selectedScholarship,
        ]);
    }

    public function oldstore(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
            'scholarship_id' => 'required'
        ]);

        // dd($request);
        $user = Auth::user()->id;

        $message = Message::create([
            'user_id' => $user,
            'scholarship_id' => $request->scholarship_id,
            'content' => $request->content,
        ]);

        // MessageSent::dispatch($message);
        broadcast(new MessageSent($message))->toOthers();

        //Notifs
        // $user = Auth::user();

        // $notification = Notification::create([
        //     'creator_id' => $user->id,
        //     'title' => 'New Message',
        //     'message' => 'May nag text ngani ' . now()->format('H:i:s'),
        //     'type' => 'info',
        // ]);

        $scholarship = Scholarship::find($request->scholarship_id);

        $notification = Notification::create([
            'title' => 'New Group Chat Message!',
            'message' => 'You have a new message in the group chat' . $scholarship->name,
            'type' => 'group_chat',
        ]);

        $scholarshipId = $scholarship->id;

        // Get users who belong to the specified scholarship group
        $users = User::whereIn('id', function ($query) use ($scholarshipId) {
            $query->select('user_id')
                ->from('scholarship_groups')
                ->where('scholarship_id', $scholarshipId);
        })
            ->where('id', '!=', Auth::user()->id) // Add this line to exclude the current user
            ->get();

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));


        event(new NewNotification($notification));

        return back();
    }
}