<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\NewNotification;
use App\Models\EducationRecord;
use App\Models\FamilyRecord;
use App\Models\Grade;
use App\Models\Applicant;
use App\Models\OrgRecord;
use App\Models\Scholarship;
use App\Models\Requirements;
use App\Models\SiblingRecord;
use App\Models\StudentRecord;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\Criteria;
use App\Models\ActivityLog;
use App\Models\CampusRecipients;
use App\Models\ApplicantTrack;
use App\Models\Campus;
use App\Models\Eligible;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Message;
use App\Models\Notifier;
use App\Models\ScholarshipTemplate;
use App\Models\Notification;
use App\Models\Student;
use App\Models\SubmittedRequirements;
use App\Models\User;
use App\Models\Scholar;
use App\Models\SchoolYear;
use App\Models\AcademicYear;
use App\Models\Sponsor;
use App\Models\Disbursement;
use App\Models\Payout;
use App\Models\PayoutSchedule;
use App\Models\Grantees;
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

        $scholar = Scholar::where('email', Auth::user()->email)
            ->with('campus')
            ->with('course')
            ->first();
        $grantee = Grantees::where('scholar_id', $scholar->id)
            ->with('school_year')
            ->first();

        $activity = ActivityLog::where('user_id', Auth::user()->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $academic_year = AcademicYear::where('status', 'Active')->first();

        if ($grantee) {
            $scholarship = Scholarship::where('id', $grantee->scholarship_id)->with('sponsor')->first();

            $disbursement = Disbursement::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first() ?? null;

            $payout = null;
            $payout_schedule = null;

            if ($disbursement && $disbursement->payout_id) {
                $payout = Payout::where('id', $disbursement->payout_id)->first();
            }

            if ($payout) {
                $payout_schedule = PayoutSchedule::where('payout_id', $payout->id)->first();
            }

            $oldestGrantee = Grantees::where('id', $grantee->id)
                ->orderBy('created_at', 'asc')
                ->with('school_year')
                ->first();

            $historygrantee = Grantees::where('scholar_id', $scholar->id)
                ->with(['school_year', 'scholar.disbursements'])
                ->get()
                ->map(function ($grantee) {
                    // Get the disbursement for this scholar
                    $disbursement = $grantee->scholar->disbursements->first();

                    // Fix: Add null check before accessing claimed_by property
                    $user = null;
                    if ($disbursement && isset($disbursement->claimed_by)) {
                        $user = User::where('id', $disbursement->claimed_by)->first();
                    }

                    return [
                        'id' => $grantee->id,
                        'scholar_id' => $grantee->scholar_id,
                        'scholarship_id' => $grantee->scholarship_id,
                        'school_year' => $grantee->school_year->year ?? 'N/A',
                        'semester' => $grantee->semester ?? 'N/A',
                        'batch_name' => $grantee->batch ? $grantee->batch->batch_name : 'N/A',
                        'dibursement_status' => $disbursement ? $disbursement->status : 'No Disbursement',
                        'claimed_at' => $disbursement ? $disbursement->claimed_at : null,
                        'claimed_by' => $user,
                        'reasons_of_not_claimed' => $disbursement ? $disbursement->reasons_of_not_claimed : null,
                        // Add any other fields you need from grantee or disbursement
                    ];
                });

            if ($scholarship) {
                $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->first();
                if (!$submittedRequirements) {
                    return redirect()->route('student.confirmation');
                }

                $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();
                $reqDeadline = $requirements->first();

                $requirementIds = $requirements->pluck('id')->toArray();

                // Fetch only returned submitted requirements related to the scholarship
                $submitReq = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->where('status', 'Returned')
                    ->whereIn('requirement_id', $requirementIds)
                    ->get();

                // Map submitted requirements with their corresponding requirement details
                $returnedRequirements = $submitReq->map(function ($submitted) use ($requirements) {
                    $requirement = $requirements->firstWhere('id', $submitted->requirement_id);
                    $subReq = SubmittedRequirements::where('requirement_id', $requirement->id)->first();

                    return [
                        'id' => $submitted->id,  // Submitted Requirement ID
                        'requirement_id' => $requirement ? $requirement->id : null, // Requirement ID
                        'requirement_name' => $requirement ? $requirement->requirements : 'Unknown Requirement',
                        'message' => $subReq ? $subReq->message : 'None',
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

                $total_subreq = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->whereIn('requirement_id', $requirementIds)
                    ->get();


                return Inertia::render('Student/Dashboard/Dashboard', [
                    'grantee' => $grantee,
                    'oldestGrantee' => $oldestGrantee,
                    'historygrantee' => $historygrantee,
                    'disbursement' => $disbursement,
                    'scholarship' => $scholarship,
                    'scholar' => $scholar,
                    'submitReq' => $returnedRequirements,
                    'submitPending' => $submitPending,
                    'submitApproved' => $submitApproved,
                    'total_subreq' => $total_subreq,
                    'payout_schedule' => $payout_schedule,
                    'reqDeadline' => $reqDeadline,
                    'academic_year' => $academic_year,
                    'activity' => $activity,
                ]);
            }
        }

        // For non-grantees
        // Get the scholar's latest grade
        $grade = Grade::where('scholar_id', $scholar->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get all active scholarships
        $scholarships = Scholarship::where('scholarshipType', 'One-time Payment')
            ->with('requirements')
            ->where('status', 'Active')
            ->get();

        // Get all criteria for these scholarships
        $criteria = Criteria::whereIn('scholarship_id', $scholarships->pluck('id'))->get();

        // Get campus recipients for these scholarships
        $campusRecipients = CampusRecipients::whereIn('scholarship_id', $scholarships->pluck('id'))->get();

        // Add criteria to each scholarship
        foreach ($scholarships as $scholarship) {
            $scholarship->criteriaData = $criteria->where('scholarship_id', $scholarship->id)->first();
            $scholarship->campusRecipients = $campusRecipients->where('scholarship_id', $scholarship->id)->values();
        }

        // Get all campuses and courses
        $campuses = Campus::all();
        $courses = Course::all();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();
        $applicant = Applicant::where('scholar_id', $scholar->id)
            ->first() ?? null;

        if ($applicant) {
            $scholarship = Scholarship::where('id', $applicant->scholarship_id)->with('sponsor')->first();

            if ($scholarship) {
                $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->first();
                if (!$submittedRequirements) {
                    return redirect()->route('student.confirmation');
                }

                $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();
                $reqDeadline = $requirements->first();

                $requirementIds = $requirements->pluck('id')->toArray();

                // Fetch only returned submitted requirements related to the scholarship
                $submitReq = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->where('status', 'Returned')
                    ->whereIn('requirement_id', $requirementIds)
                    ->get();

                // Map submitted requirements with their corresponding requirement details
                $returnedRequirements = $submitReq->map(function ($submitted) use ($requirements) {
                    $requirement = $requirements->firstWhere('id', $submitted->requirement_id);
                    $subReq = SubmittedRequirements::where('requirement_id', $requirement->id)->first();

                    return [
                        'id' => $submitted->id,  // Submitted Requirement ID
                        'requirement_id' => $requirement ? $requirement->id : null, // Requirement ID
                        'requirement_name' => $requirement ? $requirement->requirements : 'Unknown Requirement',
                        'message' => $subReq ? $subReq->message : 'None',
                        'status' => $submitted->status,
                    ];
                });

                $submitPending = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->where('status', 'Pending')
                    ->whereIn('requirement_id', $requirementIds)
                    ->get();

                $approvedCount = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->where('status', 'Approved')
                    ->whereIn('requirement_id', $requirementIds)
                    ->count();

                $totalCount = count($requirementIds);

                $submitApproved = $approvedCount === $totalCount;

                $total_subreq = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->whereIn('requirement_id', $requirementIds)
                    ->get();


                return Inertia::render('Student/Dashboard/Dashboard', [
                    'scholarships' => $scholarships,
                    'sponsors' => $sponsors,
                    'schoolyears' => $schoolyear,
                    'scholar' => $scholar,
                    'applicant' => $applicant,
                    'grade' => $grade,
                    'campuses' => $campuses,
                    'courses' => $courses,
                    'submitReq' => $returnedRequirements,
                    'submitPending' => $submitPending,
                    'submitApproved' => $submitApproved,
                    'reqDeadline' => $reqDeadline,
                    'total_subreq' => $total_subreq,
                    'academic_year' => $academic_year,
                    'activity' => $activity,
                ]);
            }
        }


        return Inertia::render('Student/Dashboard/Dashboard', [
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'schoolyears' => $schoolyear,
            'scholar' => $scholar,
            'applicant' => $applicant,
            'grade' => $grade,
            'campuses' => $campuses,
            'courses' => $courses,
            'academic_year' => $academic_year,
            'activity' => $activity,
        ]);
    }

    public function scholarship_details(Scholarship $scholarship)
    {
        $scholar = Scholar::where('email', Auth::user()->email)
            ->with('campus')
            ->with('course')
            ->first();

        // Get the student record
        $studentRecord = StudentRecord::where('scholar_id', $scholar->id)->first();

        // Get the family record
        $familyRecord = null;
        if ($studentRecord) {
            $familyRecord = FamilyRecord::where('student_record_id', $studentRecord->id)->first();
        }

        // Map all data to the scholar object
        if ($studentRecord) {
            $scholar->first_name = $studentRecord->first_name;
            $scholar->middle_name = $studentRecord->middle_name;
            $scholar->last_name = $studentRecord->last_name;
            $scholar->suffix_name = $studentRecord->suffix_name;
            $scholar->birthdate = $studentRecord->birthdate;
            $scholar->placebirth = $studentRecord->placebirth;
            $scholar->age = $studentRecord->age;
            $scholar->gender = $studentRecord->gender;
            $scholar->civil = $studentRecord->civil;
            $scholar->religion = $studentRecord->religion;
            $scholar->guardian = $studentRecord->guardian;
            $scholar->relationship = $studentRecord->relationship;
        }

        // Map family record data if available
        if ($familyRecord) {
            $scholar->mother = $familyRecord->mother;
            $scholar->father = $familyRecord->father;
            $scholar->marital_status = $familyRecord->marital_status;
            $scholar->monthly_income = $familyRecord->monthly_income;
            $scholar->other_income = $familyRecord->other_income;
            $scholar->family_housing = $familyRecord->family_housing;
        }

        // Rest of your code
        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();
        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();
        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->get();
        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();
        $grade = Grade::where('scholar_id', $scholar->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $eligibles = Eligible::where('scholarship_id', $scholarship->id)
            ->with('condition')
            ->get();

        return Inertia::render('Student/Dashboard/Non_Scholar/ScholarshipDetail', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
            'scholar' => $scholar,
            'eligibles' => $eligibles,
        ]);
    }

    public function verifyAccount()
    {
        $user = User::where('email', Auth::user()->email)->first();
        $scholar = Scholar::where('user_id', Auth::user()->id)->first();
        $studentData = Student::where('email', Auth::user()->email)->first();

        // Add scholarship forms here
        $scholarship_form = ScholarshipForm::all();
        $scholarship_form_data = ScholarshipFormData::all();

        // Rest of your existing code
        if ($scholar) {
            $grantee = Grantees::where('scholar_id', $scholar->id)->first();

            // Get the batch semester logic
            $grantee_semester = null;
            $grantee_school_year_id = null;

            if ($grantee) {
                if ($grantee->semester == '2nd') {
                    $grantee_semester = '1st';
                    $grantee_school_year_id = $grantee->school_year_id; // Keep the same school year

                } elseif ($grantee->semester == '1st') {
                    $grantee_semester = '2nd';

                    // Adjust the school year based on the current year
                    if ($grantee->school_year_id == 1) {
                        $grantee_school_year_id = 1; // First school year
                    } else {
                        $grantee_school_year_id = $grantee->school_year_id - 1; // Previous school year
                    }
                }
            }

            // Fetch the school year from the database using the calculated school year ID
            $school_year = SchoolYear::where('id', $grantee_school_year_id)->first();
        } else {
            // If no scholar is found, get the most recent academic year
            $academic_year = AcademicYear::orderBy('id', 'desc')->first();

            // Apply similar logic as for scholars to determine semester and school year
            $grantee_semester = null;
            $grantee_school_year_id = null;

            if ($academic_year) {
                if ($academic_year->semester == '2nd') {
                    $grantee_semester = '1st';
                    $grantee_school_year_id = $academic_year->school_year_id; // Keep the same school year

                } elseif ($academic_year->semester == '1st') {
                    $grantee_semester = '2nd';

                    // Adjust the school year based on the current year
                    if ($academic_year->school_year_id == 1) {
                        $grantee_school_year_id = 1; // First school year
                    } else {
                        $grantee_school_year_id = $academic_year->school_year_id - 1; // Previous school year
                    }
                }
            }

            // Fetch the school year from the database using the calculated school year ID
            $school_year = $grantee_school_year_id ? SchoolYear::find($grantee_school_year_id) : null;
        }

        return Inertia::render('Student/VerificationAccount/Verification', [
            'user' => $user,
            'scholar' => $scholar,
            'batch_semester' => $grantee_semester,
            'school_year' => $school_year ?? 'N/A', // Default to 'N/A' if no school year found
            'studentData' => $studentData,
            'scholarship_form' => $scholarship_form, // Add scholarship forms to the view
            'scholarship_form_data' => $scholarship_form_data,
        ]);
    }
    public function uploadGrade($urscholar_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => [''],
            'cog' => [''],
            'school_year' => [''],
            'semester' => [''],
        ]);

        $scholar = Scholar::where('id', $urscholar_id)->first();

        $originalFileName = $request->file('cog')->getClientOriginalName();
        $extension = $request->file('cog')->getClientOriginalExtension();
        // Format: URS-0001[1st(2024-2025)]
        $newFileName = $scholar->urscholar_id . '[' . $request->semester . '(' . $request->school_year . ')].' . $extension;


        $filePath = Storage::disk('public')->putFileAs(
            'scholar/grade',
            $request->file('cog'),
            $newFileName
        );

        $academic_year = AcademicYear::where('status', 'Active')->first();

        $testing = Grade::create([
            'scholar_id' => $scholar->id,
            'academic_year_id' => $academic_year->id,
            'grade' => $request->grade,
            'cog' => $originalFileName,
            'path' => $filePath,
            'school_year_id' => $request->school_year,
            'semester' => $request->semester,
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Upload Grade',
            'description' => 'Uploaded GWA and Certificate of Grade',
        ]);

        return redirect()->back()->with('success', 'Grade Uploaded Successfully');

    }

    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // Personal Information
            'first_name' => ['string', 'max:255'],
            'middle_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'suffix' => ['string', 'max:255'],
            'birthdate' => ['date'],
            'birthplace' => ['string', 'max:255'],
            'age' => ['numeric'],
            'gender' => ['string', 'max:255'],
            'civil_status' => ['string', 'max:255'],
            'street' => ['string', 'max:255'],
            'municipality' => ['string', 'max:255'],
            'province' => ['string', 'max:255'],
            'religion' => ['string', 'max:255'],
            'guardian_name' => ['string', 'max:255'],
            'relationship' => ['string', 'max:255'],

            // Education Information
            'education.elementary.name' => ['string'],
            'education.elementary.years' => ['string'],
            'elementary.honors' => ['string'],
            'education.junior.name' => ['string'],
            'education.junior.years' => ['string'],
            'education.senior.name' => ['string'],
            'education.senior.years' => ['string'],
            'education.senior.honors' => ['string'],
            'education.college.name' => ['string'],
            'education.college.years' => ['string'],
            'education.college.honors' => ['string'],
            'education.vocational.name' => ['string'],
            'education.vocational.years' => ['string'],
            'education.vocational.honors' => ['string'],
            'education.postgrad.name' => ['string'],
            'education.postgrad.years' => ['string'],
            'education.postgrad.honors' => ['string'],

            // Family Information
            'mother.first_name' => ['string'],
            'mother.middle_name' => ['string'],
            'mother.last_name' => ['string'],
            'mother.age' => ['string'],
            'mother.address' => ['string'],
            'mother.citizenship' => ['string'],
            'mother.occupation' => ['string'],
            'mother.education' => ['string'],
            'mother.batch' => [],

            'father.first_name' => ['string'],
            'father.middle_name' => ['string'],
            'father.last_name' => ['string'],
            'father.age' => ['string'],
            'father.address' => ['string'],
            'father.citizenship' => ['string'],
            'father.occupation' => ['string'],
            'father.education' => ['string'],
            'father.batch' => [],

            'siblings' => [],
            'siblings.*' => [],

            'marital_status' => ['string'],
            'monthly_income' => ['string'],
            'other_income' => [],
            'family_housing' => ['string'],

            'img' => 'image|mimes:jpeg,png,jpg|max:2048',
            'imgName' => 'string',
        ]);

        // Get the authenticated user's scholar record
        $scholar = Scholar::where('user_id', Auth::user()->id)->first();

        $scholar->update([
            'street' => $request->input('street'),
            'municipality' => $request->input('municipality'),
            'province' => $request->input('province'),
        ]);

        // Update or create student record
        $studentRecord = StudentRecord::updateOrCreate(
            ['scholar_id' => $scholar->id],
            [
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'suffix_name' => $request->input('suffix'),
                'birthdate' => $request->input('birthdate'),
                'placebirth' => $request->input('birthplace'),
                'age' => $request->input('age'),
                'gender' => $request->input('gender'),
                'civil' => $request->input('civil_status'),
                'religion' => $request->input('religion'),
                'guardian' => $request->input('guardian_name'),
                'relationship' => $request->input('relationship')
            ]
        );

        // Update or create education record
        $educationData = [
            'elementary' => [
                'name' => $request->input('education.elementary.name'),
                'years' => $request->input('education.elementary.years'),
                'honors' => $request->input('education.elementary.honors')
            ],
            'junior' => [
                'name' => $request->input('education.junior.name'),
                'years' => $request->input('education.junior.years'),
                'honors' => $request->input('education.junior.honors')
            ],
            'senior' => [
                'name' => $request->input('education.senior.name'),
                'years' => $request->input('education.senior.years'),
                'honors' => $request->input('education.senior.honors')
            ],
            'college' => [
                'name' => $request->input('education.college.name'),
                'years' => $request->input('education.college.years'),
                'honors' => $request->input('education.college.honors')
            ],
            'vocational' => [
                'name' => $request->input('education.vocational.name'),
                'years' => $request->input('education.vocational.years'),
                'honors' => $request->input('education.vocational.honors')
            ],
            'postgrad' => [
                'name' => $request->input('education.postgrad.name'),
                'years' => $request->input('education.postgrad.years'),
                'honors' => $request->input('education.postgrad.honors')
            ]
        ];

        EducationRecord::updateOrCreate(
            ['student_record_id' => $studentRecord->id],
            [
                'elementary' => json_encode($educationData['elementary']),
                'junior' => json_encode($educationData['junior']),
                'senior' => json_encode($educationData['senior']),
                'college' => json_encode($educationData['college']),
                'vocational' => json_encode($educationData['vocational']),
                'postgrad' => json_encode($educationData['postgrad'])
            ]
        );

        // Update or create family record
        $familyRecord = FamilyRecord::updateOrCreate(
            ['student_record_id' => $studentRecord->id],
            [
                'mother' => json_encode($request->input('mother')),
                'father' => json_encode($request->input('father')),
                'marital_status' => $request->input('marital_status'),
                'monthly_income' => $request->input('monthly_income'),
                'other_income' => $request->input('other_income'),
                'family_housing' => $request->input('family_housing')
            ]
        );

        // Update siblings
        if ($request->has('siblings')) {
            // First remove existing siblings
            SiblingRecord::where('family_record_id', $familyRecord->id)->delete();

            // Then add new siblings
            foreach ($request->input('siblings') as $sibling) {
                SiblingRecord::create([
                    'family_record_id' => $familyRecord->id,
                    'first_name' => $sibling['first_name'] ?? null,
                    'middle_name' => $sibling['middle_name'] ?? null,
                    'last_name' => $sibling['last_name'] ?? null,
                    'age' => $sibling['age'] ?? null,
                    'occupation' => $sibling['occupation'] ?? null
                ]);
            }
        }

        // Update organization records
        if ($request->has('org_records')) {
            // First remove existing org records
            OrgRecord::where('student_record_id', $studentRecord->id)->delete();

            // Then add new org records
            foreach ($request->input('org_records') as $org) {
                OrgRecord::create([
                    'student_record_id' => $studentRecord->id,
                    'name' => $org['name'] ?? null,
                    'year' => $org['year'] ?? null,
                    'position' => $org['position'] ?? null
                ]);
            }
        }

        // Handle profile image upload if present
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profile_images', $imageName);

            // Update user's profile image

            $originalFileName = $request->file('img')->getClientOriginalName();
            $user = User::where('id', Auth::user()->id)->first();

            $user->update([
                'picture' => $originalFileName,
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update Profile',
            'description' => 'Updated Profile Information',
        ]);


        return redirect()->back()->with('success', 'Profile Updated Successfully');
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
            'civil_status' => ['required', 'string', 'max:255'],
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

        // $request->validate([
        //     'files.*' => 'required|file|',
        //     'req' => 'array'
        // ]);


        // $scholar = Scholar::where('email', Auth::user()->email)->first();

        // $scholarship = Scholarship::where('id', $scholar->scholarship_id)->first();

        // $requirements = Requirements::where('id', $scholarship->id)->get();

        // $reqID = $requirements->pluck('id')->first();



        // $uploadedFiles = [];


        // foreach ($request->file('files') as $index => $file) {

        //     $path = $file->store('requirements/' . $scholar->id, 'public');

        //     $uploadedFile = SubmittedRequirements::create([
        //         'scholar_id' => $scholar->id,
        //         'requirement_id' => $reqID,
        //         'submitted_requirements' => $file->getClientOriginalName(),
        //         'path' => $path
        //     ]);

        //     $uploadedFiles[] = $uploadedFile;
        // }

        if ($file) {
            $originalFileName = $request->file('cog')->getClientOriginalName();
            $extension = $request->file('cog')->getClientOriginalExtension();
            // Format: URS-0001[1st(2024-2025)]
            $newFileName = $scholar->urscholar_id . '[' . $request->semester . '(' . $request->school_year . ')].' . $extension;


            $filePath = Storage::disk('public')->putFileAs(
                'scholar/grade',
                $request->file('cog'),
                $newFileName
            );

            $academic_year = AcademicYear::where('status', 'Active')->first();

            $testing = Grade::create([
                'scholar_id' => $scholar->id,
                'academic_year_id' => $academic_year->id,
                'grade' => $request->grade,
                'cog' => $originalFileName,
                'path' => $filePath,
                'school_year_id' => $request->school_year,
                'semester' => $request->semester,
            ]);
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



            Scholar::insert([
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
                'student_status' => 'Enrolled',
                'created_at' => now(),
                'updated_at' => now(),
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

        $newScholar = Scholar::where('user_id', $user->id)->first();

        StudentRecord::create([
            'scholar_id' => $newScholar->id,
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

        $studentrecord = StudentRecord::where('scholar_id', $newScholar->id)->get();

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
            if (
                is_null($org['name']) && is_null($org['membership_dates']) && is_null($org['position'])
            ) {
                continue;
            }

            OrgRecord::create([
                'student_record_id' => $studentrecordID,
                'name' => $org['name'],
                'year' => $org['membership_dates'],
                'position' => $org['position'],
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Verify Account',
            'description' => 'Successfully Verified Account',
        ]);


        event(new Verified($user));

        if ($scholar) {
            $grantee = Grantees::where('scholar_id', $scholar->id)->first();

            if ($grantee) {
                return redirect()->route('student.confirmation');
            } else {
                return redirect()->route('student.dashboard');
            }
        } else {
            return redirect()->route('student.dashboard'); // or any default fallback
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

            $subReq = SubmittedRequirements::where('requirement_id', $requirement->id)->first();

            return [
                'id' => $submitted->id,  // Submitted Requirement ID
                'requirement_id' => $requirement ? $requirement->id : null, // Requirement ID
                'requirement_name' => $requirement ? $requirement->requirements : 'Unknown Requirement',
                'message' => $subReq ? $subReq->message : 'None',
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

        $grantee = Grantees::where('scholar_id', $scholar->id)->first();

        $scholarship = Scholarship::where('id', $grantee->scholarship_id)->first();

        // Get all requirements for this scholarship
        $allRequirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // Get requirements that this scholar has already submitted
        $submittedRequirementIds = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->pluck('requirement_id')
            ->toArray();

        // Filter out requirements that have already been submitted
        $pendingRequirements = $allRequirements->reject(function ($requirement) use ($submittedRequirementIds) {
            return in_array($requirement->id, $submittedRequirementIds);
        })->values();

        $templates = ScholarshipTemplate::where('scholarship_id', $scholarship->id)->get();

        return Inertia::render('Student/Grant-in/Grant-In-Confirmation', [
            'scholarship' => $scholarship,
            'scholar' => $scholar,
            'requirements' => $pendingRequirements, // Pass only pending requirements
            'templates' => $templates,
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
        $scholar = Scholar::where('user_id', Auth::user()->id)->with('course', 'campus')->first();
        $student = StudentRecord::where('scholar_id', $scholar->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $siblings = SiblingRecord::where('family_record_id', $family->id)->get();
        $academic_year = AcademicYear::where('status', 'Active')->first();



        if ($scholar) {
            $grades = Grade::where('scholar_id', $scholar->id)->with('school_year')->get();
            $latestgrade = Grade::where('scholar_id', $scholar->id)
                ->where('academic_year_id', '>=', $academic_year->id)
                ->with('school_year')
                ->latest()
                ->first();


            //generate qr_code
            // Check if the scholar already has a QR code
            if ($scholar->qr_code) {

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
        } else {
            $grade = null;
            $scholar = null;
        }

        if ($scholar) {
            $grantee = Grantees::where('scholar_id', $scholar->id)
                ->where('status', '!=', 'Inactive')
                ->first();

            // Get the batch semester logic
            $grantee_semester = null;
            $grantee_school_year_id = null;

            if ($grantee) {
                // if ($grantee->semester == '2nd') {
                //     $grantee_semester = '1st';
                //     $grantee_school_year_id = $grantee->school_year_id; // Keep the same school year

                // } elseif ($grantee->semester == '1st') {
                //     $grantee_semester = '2nd';

                //     // Adjust the school year based on the current year
                //     if ($grantee->school_year_id == 1) {
                //         $grantee_school_year_id = 1; // First school year
                //     } else {
                //         $grantee_school_year_id = $grantee->school_year_id - 1; // Previous school year
                //     }
                // }
                // If no scholar is found, get the most recent academic year
                $academic_year = AcademicYear::orderBy('id', 'desc')->first();

                // Apply similar logic as for scholars to determine semester and school year
                $grantee_semester = null;
                $grantee_school_year_id = null;

                if ($academic_year) {
                    if ($academic_year->semester == '2nd') {
                        $grantee_semester = '1st';
                        $grantee_school_year_id = $academic_year->school_year_id; // Keep the same school year

                    } elseif ($academic_year->semester == '1st') {
                        $grantee_semester = '2nd';

                        // Adjust the school year based on the current year
                        if ($academic_year->school_year_id == 1) {
                            $grantee_school_year_id = 1; // First school year
                        } else {
                            $grantee_school_year_id = $academic_year->school_year_id - 1; // Previous school year
                        }
                    }
                }

                // Fetch the school year from the database using the calculated school year ID
                $school_year = $grantee_school_year_id ? SchoolYear::find($grantee_school_year_id) : null;
            } else {
                // If no scholar is found, get the most recent academic year
                $academic_year = AcademicYear::orderBy('id', 'desc')->first();

                // Apply similar logic as for scholars to determine semester and school year
                $grantee_semester = null;
                $grantee_school_year_id = null;

                if ($academic_year) {
                    if ($academic_year->semester == '2nd') {
                        $grantee_semester = '1st';
                        $grantee_school_year_id = $academic_year->school_year_id; // Keep the same school year

                    } elseif ($academic_year->semester == '1st') {
                        $grantee_semester = '2nd';

                        // Adjust the school year based on the current year
                        if ($academic_year->school_year_id == 1) {
                            $grantee_school_year_id = 1; // First school year
                        } else {
                            $grantee_school_year_id = $academic_year->school_year_id - 1; // Previous school year
                        }
                    }
                }

                // Fetch the school year from the database using the calculated school year ID
                $school_year = $grantee_school_year_id ? SchoolYear::find($grantee_school_year_id) : null;
            }

            // Fetch the school year from the database using the calculated school year ID
            $school_year = SchoolYear::where('id', $grantee_school_year_id)->first();
        }

        // Fetch the school year from the database using the calculated school year ID
        $school_year = $grantee_school_year_id ? SchoolYear::find($grantee_school_year_id) : null;

        $notify = Notifier::where('user_id', $scholar->user_id)->first();

        if ($notify) {
            $notify->update([
                'read' => true,
            ]);
        }

        return Inertia::render('Student/Profile/Scholar-Profile', [
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'siblings' => $siblings,
            'scholar' => $scholar,
            'grades' => $grades,
            'latestgrade' => $latestgrade,
            'semesterGrade' => $grantee_semester,
            'schoolyear_grade' => $school_year,
            'notify' => $notify,
            'academic_year' => $academic_year,
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

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Reupload Requirements',
            'description' => 'User reuploaded their scholarship requirements for review.',
        ]);


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
            'files.*' => 'required|file',
            'requirements' => 'array'
        ]);

        $scholar = Scholar::where('email', Auth::user()->email)->first();

        // Process each uploaded file
        foreach ($request->file('files') as $requirementId => $file) {
            // Store the file in the public storage
            $path = $file->store('requirements/' . $scholar->id, 'public');

            // Create the submitted requirement record
            SubmittedRequirements::create([
                'scholar_id' => $scholar->id,
                'requirement_id' => $requirementId, // This now uses the correct requirement ID
                'submitted_requirements' => $file->getClientOriginalName(),
                'message' => null,
                'approved_date' => now(),
                'path' => $path,
                'status' => 'Pending'
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Upload Requirements',
            'description' => 'User uploaded their scholarship requirements',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Requirements submitted successfully');
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

    public function account()
    {
        $user = Auth::user();
        return Inertia::render('Student/Profile/Account_Settings', [
            'user' => $user,
        ]);
    }

    public function application(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'essay' => 'required|string',
            'files.*' => 'required|file|',
            'req' => 'array'
        ]);



        return back()
            ->with('success', 'Your scholarship application has been submitted successfully!');
    }

    public function scholarship_application(Scholarship $scholarship)
    {
        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();

        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->first();

        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();

        $grade = Criteria::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Student/Dashboard/Non_Scholar/ScholarshipApplication', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
        ]);
    }

    public function submitApplication(Request $request, Scholarship $scholarship)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'essay' => 'required|string',
            'files.*' => 'required|file|',
            'req' => 'array'
        ]);

        $scholar = Scholar::where('user_id', Auth::user()->id)->first();


        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->first();


        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $reqID = $requirements->pluck('id')->first();

        $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->where('status', 'Active')
            ->first();

        Applicant::create([
            'scholarship_id' => $scholarship->id,
            'applicant_track_id' => $applicantTrack->id,
            'scholar_id' => $scholar->id,
            'school_year_id' => $applicantTrack->school_year_id,
            'essay' => $request->essay,
            'semester' => $applicantTrack->semester,
        ]);

        // Process each uploaded file
        foreach ($request->file('files') as $requirementId => $file) {
            // Store the file in the public storage
            $path = $file->store('requirements/' . $scholar->id, 'public');

            // Create the submitted requirement record
            SubmittedRequirements::insert([
                'scholar_id' => $scholar->id,
                'requirement_id' => $requirementId, // This now uses the correct requirement ID
                'submitted_requirements' => $file->getClientOriginalName(),
                'path' => $path,
                'status' => 'Pending'
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Upload Requirements',
            'description' => 'User uploaded their scholarship requirements',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Requirements submitted successfully');
    }
}