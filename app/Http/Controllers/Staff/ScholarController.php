<?php

namespace App\Http\Controllers\Staff;

use App\Events\NewNotification;
use App\Models\Notifier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Applicant;
use App\Models\Grantees;
use App\Models\Scholar;
use App\Models\Grade;
use App\Models\StudentRecord;
use App\Models\EducationRecord;
use App\Models\FamilyRecord;
use App\Models\SiblingRecord;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\Requirements;
use App\Models\ScholarshipGroup;
use App\Models\AcademicYear;
use App\Models\OrgRecord;
use App\Models\SubmittedRequirements;
use App\Models\Page;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ScholarController extends Controller
{
    public function scholars()
    {
        if (Auth::user()->usertype === 'coordinator') {
            $grantees = Grantees::with('scholar', 'scholar.user', 'scholar.campus', 'scholar.course', 'batch')
                ->where('status', 'Accomplished')
                ->whereHas('scholar', function ($query) {
                    $query->where('status', 'Verified')
                        ->where('student_status', 'Enrolled')
                        ->where('campus_id', Auth::user()->campus_id);
                })
                ->get();
        } else {
            $grantees = Grantees::with('scholar', 'scholar.user', 'scholar.campus', 'scholar.course', 'batch')
                ->whereIn('status', ['Accomplished', 'Active'])
                ->get();
        }

        // Instead of transforming grantees to scholars collection,
        // we'll keep the grantees collection and pass it directly

        $academicYear = AcademicYear::with('school_year')->get();
        $campus = Campus::all();

        return Inertia::render('Staff/Scholars/Scholars', [
            'grantees' => $grantees, // Changed from 'scholars' to 'grantees'
            'userType' => Auth::user()->usertype,
            'coordinatorCampus' => Auth::user()->usertype === 'coordinator' ? Auth::user()->campus : null,
            'academicYear' => $academicYear,
            'campus' => $campus,
        ]);
    }

    public function scholar($id)
    {
        $scholar = Scholar::with([
            'user',
            'campus',
            'course',
        ])->findOrFail($id);

        $student = StudentRecord::where('scholar_id', $scholar->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $siblings = SiblingRecord::where('family_record_id', $family->id)->get();

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

        $grade = Grade::where('scholar_id', $scholar->id)
            ->where('school_year_id', $grantee_school_year_id)
            ->where('semester', $grantee_semester)
            ->first();

        $scholarship = $grantee->scholarship;
        $batch = Batch::where('id', $grantee->batch_id)->first();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // Get the submitted requirements for this scholar
        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->with('requirement')
            ->get();

        $notify = Notifier::where('user_id', $scholar->user_id)
            ->where('read', 0)
            ->first();

        return Inertia::render('Staff/Scholarships/Scholar_Scholarship-Details', [
            'scholar' => $scholar,
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'siblings' => $siblings,
            'scholarship' => $scholarship,
            'batch' => $batch,
            'requirements' => $requirements,
            'submittedRequirements' => $submittedRequirements,
            'grade' => $grade,
            'notify' => $notify,
        ]);
    }

    public function notifier($scholarID)
    {
        $scholar = Scholar::findOrFail($scholarID);

        Notifier::create([
            'user_id' => $scholar->user_id,
            'title' => 'Grade Notification',
            'message' => 'You need to update your grade.',
            'type' => 'scholarship_notification',
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Notify',
            'description' => 'Notified scholar with ID: ' . $scholarID,
        ]);

        return back()->with('success', 'Notify scholar successfully!');
    }

    public function scholar_onetime($id)
    {
        $scholar = Scholar::with([
            'user',
            'campus',
            'course',
        ])->findOrFail($id);

        $student = StudentRecord::where('scholar_id', $scholar->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();
        $siblings = SiblingRecord::where('family_record_id', $family->id)->get();

        if ($scholar) {
            $grantee = Applicant::where('scholar_id', $scholar->id)->first();

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

        $grade = Grade::where('scholar_id', $scholar->id)
            ->with('school_year')
            ->where('school_year_id', $grantee_school_year_id)
            ->where('semester', $grantee_semester)
            ->first();

        $scholarship = $grantee->scholarship;
        $batch = Batch::where('id', $grantee->batch_id)->first();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // Get the submitted requirements for this scholar
        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
            ->with('requirement')
            ->get();

        $notify = Notifier::where('user_id', $scholar->user_id)
            ->where('read', 0)
            ->first();

        $hasGrantee = Grantees::where('scholar_id', $scholar->id)->exists();

        $applicant = Applicant::where('scholar_id', $scholar->id)
            ->first();


        return Inertia::render('Staff/Scholarships/One-Time/Applicant-Details', [
            'scholar' => $scholar,
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'siblings' => $siblings,
            'scholarship' => $scholarship,
            'batch' => $batch,
            'requirements' => $requirements,
            'submittedRequirements' => $submittedRequirements,
            'grade' => $grade,
            'notify' => $notify,
            'hasGrantee' => $hasGrantee,
            'applicant' => $applicant,
        ]);
    }

    public function updateStudent(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'status' => 'required|in:Dropped,Graduated,Transferred,Enrolled,Unenrolled',
            'batch_id' => 'required',
        ]);


        // Find the scholar by ID
        $scholar = Scholar::findOrFail($id);
        $batch = Batch::findOrFail($validated['batch_id']);
        $originalStatus = $scholar->student_status; // Store original status for comparison
        $academicYear = AcademicYear::where('school_year_id', $batch->school_year_id)
            ->where('semester', $batch->semester)
            ->first();

        if ($scholar->student_status == 'Unenrolled' && ($validated['status'] == 'Enrolled' || $validated['status'] == 'Transferred')) {
            // Get all students

            $students = Student::where('academic_year_id', $academicYear->id)
                ->get();


            // if ($students->isEmpty()) {
            //     return back()->withErrors([
            //         'no_match' => 'No students found for the selected academic year and semester.',
            //     ])->withInput();
            // }

            // Check if scholar has matching student data
            $hasMatchingStudent = false;
            $matchedStudent = null;

            foreach ($students as $student) {
                // Match based on student_number if available
                if ($scholar->student_number && $scholar->student_number == $student->student_number) {
                    $hasMatchingStudent = true;
                    $matchedStudent = $student;
                    break;
                }

                // Alternative matching based on name and other identifiers if student_number is not available
                if (
                    !$scholar->student_number &&
                    strtolower($scholar->first_name) == strtolower($student->first_name) &&
                    strtolower($scholar->last_name) == strtolower($student->last_name) &&
                    $scholar->campus_id == $student->campus_id
                ) {
                    $hasMatchingStudent = true;
                    $matchedStudent = $student;
                    break;
                }
            }

            // If validated status is 'Transferred' and there's matching student data
            if ($validated['status'] == 'Transferred') {

                if ($hasMatchingStudent) {

                    // Update the scholar's status for other cases
                    $grantee = Grantees::where('scholar_id', $scholar->id)
                        ->where('status', 'Pending')
                        ->where('student_status', 'Unenrolled')
                        ->first();

                    if ($grantee) {
                        $grantee->status = 'Active';
                        $grantee->student_status = $validated['status'];
                        $grantee->save();
                    }


                    // Update scholar's course and campus with matched student data
                    $scholar->course_id = $matchedStudent->course_id;
                    $scholar->campus_id = $matchedStudent->campus_id;
                    $scholar->student_status = 'Transferred';
                    $scholar->status = 'Verified';
                    $scholar->save();
                } else {
                    // No matching student found for transfer
                    return back()->withErrors([
                        'no_match' => 'No matching student record found for transfer. Please verify the scholar data.',
                    ])->withInput();
                }
            } else {
                $scholar->student_status = $validated['status'];
                $scholar->save();
            }
        } elseif ($scholar->student_status == 'Enrolled' && $validated['status'] == 'Dropped') {
            // Update the scholar's status
            $grantee = Grantees::where('scholar_id', $scholar->id)->first();

            $grantee->status = 'Inactive';
            $grantee->student_status = $validated['status'];
            $grantee->save();

            $scholar->student_status = $validated['status'];
            $scholar->status = 'Unverified';
            $scholar->save();
        } else {
            // Update the scholar's status for other cases
            $grantee = Grantees::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first();

            if ($grantee) {
                $grantee->status = 'Inactive';
                $grantee->student_status = $validated['status'];
                $grantee->save();
            }

            $scholar->student_status = $validated['status'];
            $scholar->save();
        }

        // Get the grantee record if it wasn't retrieved earlier
        if (!isset($grantee)) {
            $grantee = Grantees::where('scholar_id', $scholar->id)->first();
        }

        // Skip batch checks if no grantee is found
        if (!$grantee) {
            return response()->json(['message' => 'Student status updated successfully']);
        }

        // Check batch status conditions
        $batch = Batch::find($grantee->batch_id);

        if ($batch) {
            // Decrease total_scholars count if status changed to Dropped or Graduated from any other status
            if (
                in_array($validated['status'], ['Dropped', 'Graduated']) &&
                $originalStatus !== 'Dropped' && $originalStatus !== 'Graduated'
            ) {
                // Only decrease if the total is greater than 0
                if ($batch->total_scholars > 0) {
                    $batch->total_scholars = $batch->total_scholars - 1;
                    $batch->sub_total = $batch->sub_total - 1;
                }
            }

            $totalGranteesInBatch = Grantees::where('batch_id', $batch->id)->count();

            // Check for "Inactive" condition - all grantees are either 'Graduated' or 'Dropped'
            $completedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Graduated', 'Dropped', 'Enrolled'])
                ->count();

            // Check for "validated" condition - all grantees are 'Graduated', 'Dropped', or 'Enrolled'
            $validatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Graduated', 'Dropped', 'Enrolled'])
                ->count();

            // If all grantees have either 'Graduated' or 'Dropped' status, set batch status to 'Inactive'
            if ($totalGranteesInBatch > 0 && $totalGranteesInBatch == $completedGrantees) {
                $batch->status = 'Inactive';

                if (Auth::user()->usertype == 'super_admin') {
                    $batch->validated = true;
                }

            }

            $batch->save();
        }

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update Status',
            'description' => 'Updated student status for scholar ID: ' . $scholar->urscholar_id,
        ]);

        return back()->with(['message' => 'Student status updated successfully']);
    }

    public function updateApplicant(Request $request)
    {
        $request->validate([
            'scholar_id' => 'required|exists:scholars,id',
            'status' => 'required|in:Approve,Reject',
        ]);

        $scholar = Scholar::where('id', $request['scholar_id'])->first();
        $applicant = Applicant::where('scholar_id', $scholar->id)
            ->where('status', 'Pending')
            ->first();

        $message = null;
        if ($request['status'] == 'Approve') {
            $applicant->status = 'Approved';
            $applicant->validated_date = now();
            $message = 'Approved';
        } else {
            $applicant->status = 'Rejected';
            $applicant->validated_date = now();
            $message = 'Rejected';
        }
        $applicant->save();

        // Optionally: Send notification about scholar status update
        $notification = Notification::create([
            'title' => 'Status Update',
            'message' => 'Your Applicant has been ' . $message,
            'type' => 'scholar_status_update',
        ]);

        $notification->users()->attach($scholar->user_id);

        broadcast(new NewNotification($notification))->toOthers();

        event(new NewNotification($notification));

        $statusMessage = $request->status === 'Approve'
            ? 'Application approved successfully!'
            : 'Application rejected successfully!';

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update Status',
            'description' => 'Updated application status for scholar ID: ' . $scholar->urscholar_id,
        ]);

        return back()->with('success', $statusMessage);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'submittedReq' => 'required|exists:submitted_requirements,id',
            'status' => 'required|in:Pending,Returned,Approved',
            'message' => 'nullable|string|max:255',
        ]);

        $requirement = SubmittedRequirements::findOrFail($request->submittedReq);
        $requirement->status = $request->status;
        $requirement->validated_date = now();

        // Save message for both Returned and Approved statuses
        if ($request->message) {
            $requirement->message = $request->message;
        } else {
            $requirement->message = null; // Clear message if not provided
        }

        $requirement->save();

        $scholar = Scholar::where('id', $requirement->scholar_id)->first();

        // Notify the specific user if the status is 'Returned'
        if ($request->status === 'Returned') {
            $userToNotify = $scholar->user_id; // Assuming 'user' is the relationship to fetch the user_id

            $notification = Notification::create([
                'title' => 'Requirement Returned',
                'message' => 'Your submitted requirement has been returned.',
                'type' => 'requirement_returned',
            ]);

            // Attach the user to the notification
            $notification->users()->attach($userToNotify);

            // Broadcast the notification
            broadcast(new NewNotification($notification))->toOthers();

            // Trigger event for new notification
            event(new NewNotification($notification));
        }

        // Check if all requirements are approved when a requirement is approved
        if ($request->status === 'Approved') {
            // Get all submitted requirements for this scholar
            $allRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)->get();
            $allApproved = true;

            // Check if all requirements are approved
            foreach ($allRequirements as $req) {
                if ($req->status !== 'Approved') {
                    $allApproved = false;
                    break;
                }
            }

            // If all requirements are approved, update scholar status to 'Active'
            if ($allApproved && count($allRequirements) > 0) {
                $grantee = Grantees::where('scholar_id', $scholar->id)->first();


                if ($grantee) {
                    $grantee->status = 'Active';
                    $grantee->save();
                }


                // Optionally: Send notification about scholar status update
                $notification = Notification::create([
                    'title' => 'Status Update',
                    'message' => 'Your scholarship status has been updated to Active.',
                    'type' => 'scholar_status_update',
                ]);

                $notification->users()->attach($scholar->user_id);
                broadcast(new NewNotification($notification))->toOthers();
                event(new NewNotification($notification));
            }
        }

        // Define status-specific messages for the response
        $statusMessage = $request->status === 'Approved'
            ? 'Requirement approved successfully!'
            : 'Requirement returned successfully!';

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update Status',
            'description' => 'Updated requirement status for scholar ID: ' . $scholar->urscholar_id,
        ]);

        return back()->with('success', $statusMessage);
    }


    public function show(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        $scholars = Scholar::with('batch')->get();

        $batch = Batch::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Staff/Scholarships/Scholars', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
            'batch' => $batch,
        ]);
    }

    public function adding(Request $request, Scholarship $scholarship)
    {

        $selectedYear = $request->input('selectedYear', '');
        $selectedSem = $request->input('selectedSem', '');

        $schoolyear = SchoolYear::where('id', $selectedYear)->first();

        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $selectedYear)
            ->get();

        $campuses = Campus::all();
        $course = Course::all();

        return Inertia::render(
            'Staff/Scholarships/AddingScholars',
            [
                'scholarship' => $scholarship,
                // 'scholars' => $scholars,
                'schoolyear' => $schoolyear,
                'selectedSem' => $selectedSem,
                'batch' => $batch,
                'campuses' => $campuses,
                'course' => $course,
            ]
        );
    }

    public function checking(Request $request, Scholarship $scholarship)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'semester' => 'required',
            'schoolyear' => 'required',
        ]);

        // dd($validator);

        $checkStudent = Student::where('semester', $request->semester)
            ->get();

        if (!$checkStudent) {
            return back()->withErrors([
                'student' => 'Update the student information first before adding scholars.',
            ])->withInput();
        } else {
            return redirect()->to("/scholarships/{$scholarship->id}?selectedSem={$request->semester}&selectedYear={$request->schoolyear}")
                ->with('flash', [
                    'type' => 'success',
                    'message' => "Successfully imported"
                ]);
        }

    }


    public function upload(Request $request, Scholarship $scholarship)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'semester' => 'required',
            'schoolyear' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors([
                'nofile' => 'Please upload a CSV file.',
            ])->withInput();
        }

        try {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $firstRecord = $csv->fetchOne();

            // Check if required columns exist in the CSV
            $requiredColumns = [
                'HEI NAME',
                'CAMPUS',
                'GRANT',
                'BATCH NO.',
                'APP NO',
                'AWARD NO.',
                'LASTNAME',
                'FIRSTNAME',
                'EXTNAME',
                'MIDDLENAME',
                'SEX',
                'BIRTHDATE',
                'COURSE/PROGRAM ENROLLED',
                'YEAR LEVEL',
                'TOTAL UNITS ENROLLED',
                'STREET',
                'MUNICIPALITY',
                'PROVINCE',
                'CLASSIFICATION OF PWD'
            ];

            $headers = array_keys($firstRecord);
            $missingColumns = array_diff($requiredColumns, $headers);

            if (!empty($missingColumns)) {
                return back()->withErrors([
                    'nofile' => 'CSV file is missing required columns: ' . implode(', ', $missingColumns),
                ])->withInput();
            }


            // Get all records
            $records = iterator_to_array($csv->getRecords());

            // Check for existing students in the system
            $duplicateStudents = [];
            foreach ($records as $record) {
                $existingStudent = Student::where('last_name', $record['LASTNAME'] ?? '')
                    ->where('first_name', $record['FIRSTNAME'] ?? '')
                    ->where('year_level', $record['YEAR LEVEL'] ?? '')
                    ->first();

                if ($existingStudent) {
                    $duplicateStudents[] = $existingStudent;
                }
            }

            // if (!empty($duplicateStudents)) {
            //     return back()->withErrors([
            //         'student' => 'Uy lods, outdated na yung student info. Paki-update naman 😅',
            //     ])->withInput();
            // }


            // Get all campuses for efficient lookup
            $campuses = Campus::all()->mapWithKeys(function ($campus) {
                return [strtolower($campus->name) => $campus->id];
            })->toArray();

            // Get all courses with their names and abbreviations
            $courses = Course::select('id', 'name', 'abbreviation')->get();

            // Map to standardize course names for comparison
            $standardizedCourseLookup = [];

            foreach ($courses as $course) {
                // Store the original course name
                $standardizedCourseLookup[strtolower($course->name)] = $course->id;

                // Store the abbreviation
                if (!empty($course->abbreviation)) {
                    $standardizedCourseLookup[strtolower($course->abbreviation)] = $course->id;
                }

                // Store without "Bachelor of Science in"
                $withoutPrefix = str_replace(
                    ['bachelor of science in ', 'bachelor of arts in ', 'bachelor of '],
                    ['', '', ''],
                    strtolower($course->name)
                );
                $standardizedCourseLookup[$withoutPrefix] = $course->id;

                // Store with BS/BA prefix
                $withBsPrefix = 'bs in ' . $withoutPrefix;
                $standardizedCourseLookup[$withBsPrefix] = $course->id;

                $withBsPrefix2 = 'bs ' . $withoutPrefix;
                $standardizedCourseLookup[$withBsPrefix2] = $course->id;
            }

            // Lists to track what we need to do with each record
            $existingScholarIds = [];
            $newScholarsData = [];
            $courseMatching = [];
            $duplicateGranteeIds = []; // For storing scholars who already have grantees

            // Get the next available urscholar_id
            $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
                ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
                ->value('urscholar_id');

            $nextId = 1; // Default starting number
            if ($highestId) {
                $currentNumber = (int) substr($highestId, 4);
                $nextId = $currentNumber + 1;
            }

            // Process each record from the CSV
            foreach ($records as $record) {
                // Check if scholar already exists
                $existingScholar = Scholar::where('last_name', $record['LASTNAME'] ?? '')
                    ->where('first_name', $record['FIRSTNAME'] ?? '')
                    ->where('middle_name', $record['MIDDLENAME'] ?? '')
                    ->first();

                if ($existingScholar) {
                    // Update the existing scholar with new data from CSV
                    $campusName = strtolower($record['CAMPUS'] ?? null);
                    $campusId = null;

                    if ($campusName && isset($campuses[$campusName])) {
                        $campusId = $campuses[$campusName];
                    }

                    // MULTIPLE APPROACH COURSE MATCHING
                    $csvCourseName = $record['COURSE/PROGRAM ENROLLED'] ?? null;
                    $courseId = null;

                    if ($csvCourseName) {
                        // 1. Try direct match with standardized lookup
                        $standardizedCsvName = strtolower(trim($csvCourseName));
                        if (isset($standardizedCourseLookup[$standardizedCsvName])) {
                            $courseId = $standardizedCourseLookup[$standardizedCsvName];
                        } else {
                            // 2. Try to find match with keywords (Information Technology)
                            $bestMatchScore = 0;
                            $bestMatchId = null;

                            foreach ($courses as $course) {
                                // Extract the core subject part (e.g., "Information Technology")
                                $coreSubject = preg_replace(
                                    '/^(bachelor of science in |bachelor of arts in |bs in |ba in |bs |ba )/',
                                    '',
                                    strtolower($course->name)
                                );

                                // Check if the core subject is in the CSV course name
                                if (!empty($coreSubject) && stripos($standardizedCsvName, $coreSubject) !== false) {
                                    // Use a scoring system based on length of match
                                    $score = strlen($coreSubject);
                                    if ($score > $bestMatchScore) {
                                        $bestMatchScore = $score;
                                        $bestMatchId = $course->id;
                                    }
                                }
                            }

                            if ($bestMatchId) {
                                $courseId = $bestMatchId;
                            }
                        }

                        // For debugging
                        $matchedCourse = $courseId ? Course::find($courseId)->name : 'No match found';
                        $courseMatching[$csvCourseName] = [
                            'original' => $csvCourseName,
                            'standardized' => $standardizedCsvName,
                            'matched_to' => $matchedCourse,
                            'course_id' => $courseId
                        ];
                    }

                    // Update the existing scholar with new information
                    $existingScholar->update([
                        'hei_name' => $record['HEI NAME'] ?? $existingScholar->hei_name,
                        'campus_id' => $campusId ?? $existingScholar->campus_id,
                        'course_id' => $courseId ?? $existingScholar->course_id,
                        'grant' => $record['GRANT'] ?? $existingScholar->grant,
                        'app_no' => $record['APP NO'] ?? $existingScholar->app_no,
                        'award_no' => $record['AWARD NO.'] ?? $existingScholar->award_no,
                        'extname' => $record['EXTNAME'] ?? $existingScholar->extname,
                        'sex' => $record['SEX'] ?? $existingScholar->sex,
                        'birthdate' => $record['BIRTHDATE'] ? date('Y-m-d', strtotime($record['BIRTHDATE'])) : $existingScholar->birthdate,
                        'year_level' => $record['YEAR LEVEL'] ?? $existingScholar->year_level,
                        'total_units' => $record['TOTAL UNITS ENROLLED'] ?? $existingScholar->total_units,
                        'street' => $record['STREET'] ?? $existingScholar->street,
                        'municipality' => $record['MUNICIPALITY'] ?? $existingScholar->municipality,
                        'province' => $record['PROVINCE'] ?? $existingScholar->province,
                        'pwd_classification' => $record['CLASSIFICATION OF PWD'] ?? $existingScholar->pwd_classification,
                        'updated_at' => now(),
                    ]);

                    // Check if this scholar already has a grantee record for this batch, semester, and school year
                    $existingGrantee = Grantees::where('scholar_id', $existingScholar->id)
                        ->where('scholarship_id', $scholarship->id)
                        ->where('school_year_id', $request->schoolyear)
                        ->where('semester', $request->semester)
                        ->first();

                    if ($existingGrantee) {
                        // Add to duplicate grantees list
                        $duplicateGranteeIds[] = $existingScholar->id;
                    } else {
                        // Simply collect the ID - we'll create a grantee entry later
                        $existingScholarIds[] = $existingScholar->id;
                    }
                } else {
                    // This is a new scholar - prepare data for insertion
                    // Determine campus_id from campus name
                    $campusName = strtolower($record['CAMPUS'] ?? null);
                    $campusId = null;

                    if ($campusName && isset($campuses[$campusName])) {
                        $campusId = $campuses[$campusName];
                    }

                    // MULTIPLE APPROACH COURSE MATCHING
                    $csvCourseName = $record['COURSE/PROGRAM ENROLLED'] ?? null;
                    $courseId = null;

                    if ($csvCourseName) {
                        // 1. Try direct match with standardized lookup
                        $standardizedCsvName = strtolower(trim($csvCourseName));
                        if (isset($standardizedCourseLookup[$standardizedCsvName])) {
                            $courseId = $standardizedCourseLookup[$standardizedCsvName];
                        } else {
                            // 2. Try to find match with keywords (Information Technology)
                            $bestMatchScore = 0;
                            $bestMatchId = null;

                            foreach ($courses as $course) {
                                // Extract the core subject part (e.g., "Information Technology")
                                $coreSubject = preg_replace(
                                    '/^(bachelor of science in |bachelor of arts in |bs in |ba in |bs |ba )/',
                                    '',
                                    strtolower($course->name)
                                );

                                // Check if the core subject is in the CSV course name
                                if (!empty($coreSubject) && stripos($standardizedCsvName, $coreSubject) !== false) {
                                    // Use a scoring system based on length of match
                                    $score = strlen($coreSubject);
                                    if ($score > $bestMatchScore) {
                                        $bestMatchScore = $score;
                                        $bestMatchId = $course->id;
                                    }
                                }
                            }

                            if ($bestMatchId) {
                                $courseId = $bestMatchId;
                            }
                        }

                        // For debugging
                        $matchedCourse = $courseId ? Course::find($courseId)->name : 'No match found';
                        $courseMatching[$csvCourseName] = [
                            'original' => $csvCourseName,
                            'standardized' => $standardizedCsvName,
                            'matched_to' => $matchedCourse,
                            'course_id' => $courseId
                        ];
                    }

                    // Generate urscholar_id with leading zeros (URS-0001 format)
                    $urscholarId = 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                    $nextId++;

                    // Add to new scholars data
                    $newScholarsData[] = [
                        'urscholar_id' => $urscholarId,
                        'hei_name' => $record['HEI NAME'] ?? null,
                        'campus_id' => $campusId,
                        'course_id' => $courseId,
                        'grant' => $record['GRANT'] ?? null,
                        'app_no' => $record['APP NO'] ?? null,
                        'award_no' => $record['AWARD NO.'] ?? null,
                        'last_name' => $record['LASTNAME'] ?? null,
                        'first_name' => $record['FIRSTNAME'] ?? null,
                        'extname' => $record['EXTNAME'] ?? null,
                        'middle_name' => $record['MIDDLENAME'] ?? null,
                        'sex' => $record['SEX'] ?? null,
                        'birthdate' => $record['BIRTHDATE'] ? date('Y-m-d', strtotime($record['BIRTHDATE'])) : null,
                        'year_level' => $record['YEAR LEVEL'] ?? null,
                        'total_units' => $record['TOTAL UNITS ENROLLED'] ?? null,
                        'street' => $record['STREET'] ?? null,
                        'municipality' => $record['MUNICIPALITY'] ?? null,
                        'province' => $record['PROVINCE'] ?? null,
                        'pwd_classification' => $record['CLASSIFICATION OF PWD'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // If there are duplicate grantees, return with error
            if (!empty($duplicateGranteeIds)) {
                $duplicateScholars = Scholar::whereIn('id', $duplicateGranteeIds)->get();
                $names = $duplicateScholars->map(function ($scholar) {
                    return $scholar->first_name . ' ' . $scholar->last_name;
                })->implode(', ');

                // return back()->withErrors([
                //     'student' => 'The following scholars already exist in this scholarship for the selected semester and school year: ' . $names
                // ])->withInput();
            }

            // Insert new scholars
            $createdScholars = [];
            if (!empty($newScholarsData)) {
                // Bulk insert new scholars
                $scholarcheck = Scholar::whereIn('urscholar_id', array_column($newScholarsData, 'urscholar_id'))
                    ->pluck('urscholar_id')
                    ->toArray();

                Scholar::insert($newScholarsData);

                // Get all newly created scholars by matching the inserted data
                foreach ($newScholarsData as $data) {
                    $scholar = Scholar::where('urscholar_id', $data['urscholar_id'])
                        ->where('last_name', $data['last_name'])
                        ->where('first_name', $data['first_name'])
                        ->first();

                    if ($scholar) {
                        $createdScholars[] = $scholar;
                    }
                }
            }

            // Student verification process for new scholars only
            $students = Student::all();
            $matchedCount = 0;
            $unmatchedCount = 0;
            $transferredCount = 0;
            $campusTransfers = [];
            $transferredScholars = []; // Track transferred scholars for special batch handling

            foreach ($createdScholars as $scholar) {
                // First try to find a match by name and year level regardless of campus/course
                $matchedStudent = $students->first(function ($student) use ($scholar) {
                    return $scholar->first_name == $student->first_name &&
                        $scholar->last_name == $student->last_name &&
                        $scholar->year_level == $student->year_level;
                });

                if ($matchedStudent) {
                    // Student exists in the system
                    if (
                        $matchedStudent->course_id === $scholar->course_id &&
                        $matchedStudent->campus_id === $scholar->campus_id
                    ) {
                        // Perfect match - set as Enrolled and Verified
                        $scholar->update([
                            'student_status' => 'Enrolled',
                            'status' => 'Verified',
                            'email' => $matchedStudent->email,
                            'student_number' => $matchedStudent->student_number
                        ]);
                        $matchedCount++;
                    } else {
                        // Student exists but has different campus or course - mark as Transferred
                        $scholar->update([
                            'student_status' => 'Transferred',
                            'status' => 'Verified',
                            'email' => $matchedStudent->email,
                            'student_number' => $matchedStudent->student_number,
                            'campus_id' => $matchedStudent->campus_id,
                            'course_id' => $matchedStudent->course_id,
                        ]);

                        // Add to transferredScholars array for separate batch handling
                        $transferredScholars[] = $scholar->id;

                        // Track this transfer for notifications
                        if ($matchedStudent->campus_id !== $scholar->campus_id) {
                            // Add to campusTransfers array
                            if (!isset($campusTransfers[$matchedStudent->campus_id])) {
                                $campusTransfers[$matchedStudent->campus_id] = [];
                            }
                            if (!isset($campusTransfers[$scholar->campus_id])) {
                                $campusTransfers[$scholar->campus_id] = [];
                            }

                            // Store transfer info for both campuses
                            $transferInfo = [
                                'scholar_id' => $scholar->id,
                                'name' => $scholar->first_name . ' ' . $scholar->last_name,
                                'from_campus_id' => $matchedStudent->campus_id,
                                'to_campus_id' => $scholar->campus_id,
                                'student_number' => $matchedStudent->student_number
                            ];

                            $campusTransfers[$matchedStudent->campus_id][] = $transferInfo; // Old campus
                            $campusTransfers[$scholar->campus_id][] = $transferInfo; // New campus
                        }

                        $transferredCount++;
                    }
                } else {
                    // No matching student found
                    $scholar->update(['status' => 'Unverified']);
                    $unmatchedCount++;
                }
            }

            // Check for existing scholars with Transferred status
            if (!empty($existingScholarIds)) {
                $existingTransferredScholars = Scholar::whereIn('id', $existingScholarIds)
                    ->where('student_status', 'Transferred')
                    ->pluck('id')
                    ->toArray();

                // Add existing transferred scholars to the list
                $transferredScholars = array_merge($transferredScholars, $existingTransferredScholars);
            }

            // Collect all scholar IDs (both existing and newly created)
            $allScholars = [];

            // Add existing scholars
            if (!empty($existingScholarIds)) {
                $existingScholars = Scholar::whereIn('id', $existingScholarIds)->get();
                foreach ($existingScholars as $scholar) {
                    $allScholars[] = $scholar->toArray();
                }
            }

            // Add newly created scholars
            if (!empty($createdScholars)) {
                foreach ($createdScholars as $scholar) {
                    $allScholars[] = $scholar->toArray();
                }
            }

            // Group scholars by campus (regular and transferred separately)
            $scholarsByCompus = [];
            $transferScholarsByCompus = [];

            foreach ($allScholars as $scholar) {
                $campusId = $scholar['campus_id'];
                $scholarId = $scholar['id'];

                if ($campusId) {
                    // Check if this is a transferred scholar
                    if (in_array((int) $scholarId, array_map('intval', $transferredScholars))) {
                        // Add to transfer batch
                        if (!isset($transferScholarsByCompus[$campusId])) {
                            $transferScholarsByCompus[$campusId] = [];
                        }
                        $transferScholarsByCompus[$campusId][] = $scholarId;
                    } else {
                        // Add to regular batch
                        if (!isset($scholarsByCompus[$campusId])) {
                            $scholarsByCompus[$campusId] = [];
                        }
                        $scholarsByCompus[$campusId][] = $scholarId;
                    }
                }
            }

            // Debug logging
            \Log::info('Transferred scholars detected: ' . count($transferredScholars));
            \Log::info('Transfer scholar groups by campus: ' . json_encode($transferScholarsByCompus));

            // Track campusBatches
            $campusBatches = [];
            $transferBatches = [];

            // Create or find batches for each campus (for regular scholars)
            foreach ($scholarsByCompus as $campusId => $scholarIds) {
                // Check if batch already exists for this campus
                $existingBatch = Batch::where('scholarship_id', $scholarship->id)
                    ->where('batch_no', $firstRecord['BATCH NO.'])
                    ->where('school_year_id', $request->schoolyear)
                    ->where('campus_id', $campusId)
                    ->first();

                if (!$existingBatch) {
                    // Create a new batch for this campus
                    $batch = Batch::create([
                        'scholarship_id' => $scholarship->id,
                        'batch_no' => $firstRecord['BATCH NO.'],
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'campus_id' => $campusId,
                        'total_scholars' => count($scholarIds), // Set initial count
                    ]);
                    $campusBatches[$campusId] = $batch;
                } else {
                    // Use existing batch and increase total_scholars count
                    $existingBatch->update([
                        'total_scholars' => $existingBatch->total_scholars + count($scholarIds)
                    ]);
                    $campusBatches[$campusId] = $existingBatch;
                }

                // Create grantees for regular scholars
                $granteesData = [];
                foreach ($scholarIds as $scholarId) {
                    // Fetch the scholar to get their student_status
                    $scholar = Scholar::find($scholarId);

                    $granteesData[] = [
                        'scholarship_id' => $scholarship->id,
                        'batch_id' => $campusBatches[$campusId]->id,
                        'scholar_id' => $scholarId,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'status' => 'Pending',
                        'student_status' => $scholar->student_status ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert grantees for this campus
                if (!empty($granteesData)) {
                    Grantees::insert($granteesData);
                }

                // Check if all grantees in this batch have Enrolled status
                if ($campusId == Auth::user()->campus_id) {
                    $batchGrantees = Grantees::where('batch_id', $campusBatches[$campusId]->id)->get();
                    $allEnrolled = true;

                    foreach ($batchGrantees as $grantee) {
                        if ($grantee->student_status !== 'Enrolled') {
                            $allEnrolled = false;
                            break;
                        }
                    }

                    if ($allEnrolled && count($batchGrantees) > 0) {
                        $campusBatches[$campusId]->update([
                            'validated' => true
                        ]);
                    }
                }
            }

            // Create separate batches for transferred scholars
            foreach ($transferScholarsByCompus as $campusId => $scholarIds) {
                // Create a new transfer batch with a special name
                $transferBatchNo = $firstRecord['BATCH NO.']; // Append T for Transfer

                // Check if a transfer batch already exists
                $existingTransferBatch = Batch::where('scholarship_id', $scholarship->id)
                    ->where('batch_no', $transferBatchNo)
                    ->where('school_year_id', $request->schoolyear)
                    ->where('campus_id', $campusId)
                    ->first();

                if (!$existingTransferBatch) {
                    // Create a new transfer batch
                    $transferBatch = Batch::create([
                        'scholarship_id' => $scholarship->id,
                        'batch_no' => $transferBatchNo,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'campus_id' => $campusId,
                        'total_scholars' => count($scholarIds),
                    ]);

                    $transferBatches[$campusId] = $transferBatch;
                } else {
                    // Use existing transfer batch
                    $existingTransferBatch->update([
                        'total_scholars' => $existingTransferBatch->total_scholars + count($scholarIds)
                    ]);
                    $transferBatches[$campusId] = $existingTransferBatch;
                }

                // Create grantees for transferred scholars
                $transferGranteesData = [];
                foreach ($scholarIds as $scholarId) {
                    $scholar = Scholar::find($scholarId);

                    $transferGranteesData[] = [
                        'scholarship_id' => $scholarship->id,
                        'batch_id' => $transferBatches[$campusId]->id,
                        'scholar_id' => $scholarId,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'status' => 'Pending', // Transferred students are automatically enrolled
                        'student_status' => 'Enrolled',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert grantees for transferred scholars
                if (!empty($transferGranteesData)) {
                    Grantees::insert($transferGranteesData);
                }
            }

            // Debug logging
            \Log::info('Transfer batches created: ' . count($transferBatches));

            // Verify and update scholar counts for transfer batches
            foreach ($transferBatches as $campusId => $batch) {
                $actualCount = Grantees::where('batch_id', $batch->id)->count();
                if ($batch->total_scholars != $actualCount) {
                    $batch->update(['total_scholars' => $actualCount]);
                }
            }

            // Send notifications to campus coordinators about transfers
            if (!empty($campusTransfers)) {
                foreach ($campusTransfers as $campusId => $transfers) {
                    // Get the campus details
                    $campus = Campus::find($campusId);
                    if (!$campus || !$campus->coordinator_id) {
                        continue; // Skip if campus doesn't exist or has no coordinator
                    }

                    // Get the coordinator for this campus
                    $coordinator = User::find($campus->coordinator_id);
                    if (!$coordinator) {
                        continue; // Skip if coordinator not found
                    }

                    // Create notification message based on transfer direction
                    $studentNames = collect($transfers)->pluck('name')->unique()->implode(', ');

                    // For each transfer, determine if this campus is the source or destination
                    $incomingTransfers = collect($transfers)->filter(function ($t) use ($campusId) {
                        return $t['to_campus_id'] == $campusId;
                    })->count();

                    $outgoingTransfers = collect($transfers)->filter(function ($t) use ($campusId) {
                        return $t['from_campus_id'] == $campusId;
                    })->count();

                    // Create appropriate notification message
                    $message = "Scholarship transfer alert for {$campus->name} campus: ";
                    if ($incomingTransfers > 0 && $outgoingTransfers > 0) {
                        $message .= "{$incomingTransfers} incoming and {$outgoingTransfers} outgoing transfers detected for students: {$studentNames}";
                    } elseif ($incomingTransfers > 0) {
                        $message .= "{$incomingTransfers} incoming transfers detected for students: {$studentNames}";
                    } else {
                        $message .= "{$outgoingTransfers} outgoing transfers detected for students: {$studentNames}";
                    }

                    // If there's a transfer batch, include batch information
                    if (isset($transferBatches[$campusId])) {
                        $message .= ". A new transfer batch '{$transferBatches[$campusId]->batch_no}' has been created.";
                    }

                    // Create notification
                    $notification = Notification::create([
                        'title' => 'Student Transfer Alert',
                        'message' => $message,
                        'type' => 'student_transfer',
                    ]);

                    // Attach coordinator to notification
                    $notification->users()->attach($coordinator->id);

                    // Also send detailed transfer information for each student
                    foreach ($transfers as $transfer) {
                        $fromCampus = Campus::find($transfer['from_campus_id'])->name ?? 'Unknown';
                        $toCampus = Campus::find($transfer['to_campus_id'])->name ?? 'Unknown';

                        $detailedMessage = "Transfer details for {$transfer['name']} (#{$transfer['student_number']}): ";
                        if ($transfer['from_campus_id'] == $campusId) {
                            $detailedMessage .= "Transferred OUT from {$fromCampus} to {$toCampus}";
                        } else {
                            $detailedMessage .= "Transferred IN from {$fromCampus} to {$toCampus}";
                        }

                        // Add batch information
                        if ($transfer['to_campus_id'] == $campusId && isset($transferBatches[$campusId])) {
                            $detailedMessage .= ". Added to transfer batch '{$transferBatches[$campusId]->batch_no}'.";
                        }

                        $detailNotification = Notification::create([
                            'title' => 'Transfer Details',
                            'message' => $detailedMessage,
                            'type' => 'student_transfer_detail',
                            'reference_id' => $transfer['scholar_id'],
                        ]);

                        $detailNotification->users()->attach($coordinator->id);
                    }
                }
            }

            // Update Scholarship Status
            $scholarship->update([
                'status' => 'Active'
            ]);

            // Merge regular batches and transfer batches for group creation
            $allBatches = $campusBatches;
            foreach ($transferBatches as $campusId => $batch) {
                if (!isset($allBatches[$campusId])) {
                    $allBatches[$campusId] = $batch;
                }
            }

            // Get campuses for all scholars
            $campusIds = array_keys($allBatches);
            $campusesForGroups = Campus::whereIn('id', $campusIds)->get();

            // Update for the current user - only create for matching campus/batch
            $currentUser = Auth::user();

            // Create groups for each campus batch
            foreach ($campusesForGroups as $campus) {
                // Only create groups for batches that exist for this campus
                if (isset($allBatches[$campus->id])) {
                    $batch = $allBatches[$campus->id];

                    // Create a group chat for this campus with batch number in the name
                    $groupName = "Batch " . $batch->batch_no;

                    // Check if this group already exists for this campus
                    $existingGroup = ScholarshipGroup::where('name', $groupName)
                        ->where('campus_id', $campus->id)
                        ->first();

                    if (!$existingGroup) {
                        // Create new scholarship group with name
                        $scholarshipGroup = ScholarshipGroup::create([
                            'name' => $groupName,
                            'campus_id' => $campus->id,
                            'user_id' => Auth::user()->id, // Creator of the group
                        ]);

                        // Add coordinator if they exist and their campus matches
                        $coordinator = User::find($campus->coordinator_id);
                        if ($coordinator && $coordinator->campus_id == $campus->id) {
                            $scholarshipGroup->users()->attach($coordinator->id);
                        }

                        // Add cashier if they exist and their campus matches
                        $cashier = User::find($campus->cashier_id);
                        if ($cashier && $cashier->campus_id == $campus->id) {
                            $scholarshipGroup->users()->attach($cashier->id);
                        }

                        // Create notification about new group chat
                        $notification = Notification::create([
                            'title' => 'New Group Chat Created',
                            'message' => 'You have been added to group chat ' . $groupName . ' for ' . $campus->name . ' campus',
                            'type' => 'group_chat_created',
                        ]);

                        // Notify all group members
                        $memberIds = $scholarshipGroup->users()->pluck('users.id')->toArray();
                        $notification->users()->attach($memberIds);
                    } else {
                        // If group exists, check if batch matches the group name
                        $batchNumber = $batch->batch_no;
                        if (strpos($existingGroup->name, $batchNumber) !== false) {
                            // Group exists and batch numbers match, add any missing members

                            // Add current user if not already in group
                            if ($currentUser && $currentUser->campus_id == $campus->id) {
                                if (!$existingGroup->users()->where('user_id', $currentUser->id)->exists()) {
                                    $existingGroup->users()->attach($currentUser->id);
                                }
                            }

                            // Add coordinator if not already in group
                            $coordinator = User::find($campus->coordinator_id);
                            if ($coordinator && $coordinator->campus_id == $campus->id) {
                                if (!$existingGroup->users()->where('user_id', $coordinator->id)->exists()) {
                                    $existingGroup->users()->attach($coordinator->id);
                                }
                            }

                            // Add cashier if not already in group
                            $cashier = User::find($campus->cashier_id);
                            if ($cashier && $cashier->campus_id == $campus->id) {
                                if (!$existingGroup->users()->where('user_id', $cashier->id)->exists()) {
                                    $existingGroup->users()->attach($cashier->id);
                                }
                            }
                        }
                    }
                }
            }

            $schoolyear = SchoolYear::find($request->schoolyear);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Upload Scholarship CSV',
                'description' => 'Scholars added to ' . $scholarship->name . ' for ' . $schoolyear->name . ' ' . $request->semester,
            ]);

            $existingScholarsCount = count($existingScholarIds);
            $newScholarsCount = count($createdScholars);
            $totalCampuses = count($allBatches);
            $transferBatchesCount = count($transferBatches);

            return redirect()->to("/scholarships/{$scholarship->id}?selectedSem={$request->semester}&selectedYear={$request->schoolyear}")
                ->with('success', 'Scholars successfully added!');

        } catch (\Exception $e) {
            // Catch any other exceptions that might occur
            return back()->withErrors([
                'error' => 'Error processing CSV file: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    public function manual(Request $request, Scholarship $scholarship)
    {
        $request->validate([
            'grant' => 'required',
            'batch_id' => 'required',
            'hei_name' => 'required',
            'campus_id' => 'required',
            'course_id' => 'required',
            'year' => 'required',
            'app_no' => 'required',
            'award_no' => 'required',
            'last_name' => 'required',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'sex' => 'required',
            'birthdate' => 'required|date',
            'province' => 'required',
            'municipality' => 'required',
            'street' => 'required',
            'semester' => 'required',
            'schoolyear' => 'required',
        ]);


        $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
            ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
            ->value('urscholar_id');

        $nextId = 1; // Default starting number
        if ($highestId) {
            $currentNumber = (int) substr($highestId, 4);
            $nextId = $currentNumber + 1;
        }

        $student = Student::where('first_name', $request->first_name)
            ->where('last_name', $request->last_name)
            ->where('course_id', $request->course_id)
            ->where('campus_id', $request->campus_id)
            ->where('year_level', $request->year)
            ->first();

        if ($student) {
            // dd($student);

            $scholar = Scholar::where('first_name', $request->first_name)
                ->where('last_name', $request->last_name)
                ->where('course_id', $request->course_id)
                ->where('campus_id', $request->campus_id)
                ->where('year_level', $request->year)
                ->first();
            if ($scholar) {
                return back()->withErrors([
                    'student' => 'Scholar already exists in the system. Please check the details and try again.',
                ])->withInput();
            } else {
                $scholarData = [];
                // Merge the request data with student data (student data takes priority if available)
                $scholarData = [
                    'student_number' => $student->student_number,
                    'grant' => $request->grant,
                    'hei_name' => $request->hei_name,
                    'urscholar_id' => 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
                    'campus_id' => $request->campus_id,
                    'course_id' => $request->course_id,
                    'year_level' => $request->year,
                    'app_no' => $request->app_no,
                    'award_no' => $request->award_no,
                    'last_name' => $request->last_name,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'sex' => $request->sex,
                    'birthdate' => $request->birthdate,
                    'province' => $request->province,
                    'municipality' => $request->municipality,
                    'street' => $request->street,
                    'email' => $student->email,
                    'student_status' => 'Enrolled',
                    'status' => 'Verified',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                Scholar::insert($scholarData);

                $batch = Batch::where('batch_no', $request->batch_id)
                    ->where('school_year_id', $request->schoolyear)
                    ->where('semester', $request->semester)
                    ->where('campus_id', $request->campus_id)
                    ->first();

                $scholar = Scholar::where('first_name', $request->first_name)
                    ->where('last_name', $request->last_name)
                    ->where('course_id', $request->course_id)
                    ->where('campus_id', $request->campus_id)
                    ->where('year_level', $request->year)
                    ->first();

                if ($batch) {
                    // Increment the total_scholars count
                    $batch->increment('total_scholars');

                    Grantees::create([
                        'scholarship_id' => $scholarship->id,
                        'batch_id' => $batch->id,
                        'scholar_id' => $scholar->id,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'status' => 'Pending',
                        'student_status' => 'Enrolled',
                    ]);


                } else {
                    $batch = Batch::create([
                        'scholarship_id' => $scholarship->id,
                        'batch_no' => $request->batch_id,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'campus_id' => $request->campus_id,
                        'total_scholars' => 1,
                        'status' => 'Pending',
                    ]);


                    Grantees::create([
                        'scholarship_id' => $scholarship->id,
                        'batch_id' => $batch->id,
                        'scholar_id' => $scholar->id,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'status' => 'Pending',
                        'student_status' => 'Enrolled',
                    ]);
                }

                ActivityLog::create([
                    'user_id' => Auth::user()->id,
                    'activity' => 'Create Manual Scholar',
                    'description' => 'Added ' . $request->first_name . ' ' . $request->last_name . ' to ' . $scholarship->name,
                ]);

                return redirect()->back()->with('success', 'Scholar added successfully!');
            }
        } else {
            return back()->withErrors([
                'student' => 'No matching student found in the system. Please check the details and try again.',
            ])->withInput();
        }
    }

    public function send(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;


        return Inertia::render('Staff/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function expand_requirements()
    {
        return Inertia::render('Staff/Scholarships/ScholarRequirements', [
        ]);
    }


    public function scholar_scholarship_details()
    {
        return Inertia::render('Staff/Scholarships/Scholar_Scholarship-Details');
    }

    public function scholar_information(Scholar $scholar)
    {
        // Reload the scholar with the campus relationship
        $scholar = Scholar::with('campus', 'course')->find($scholar->id);

        $student = StudentRecord::where('scholar_id', $scholar->id)->first();
        $education = EducationRecord::where('student_record_id', $student->id)->first();
        $family = FamilyRecord::where('student_record_id', $student->id)->first();

        $siblings = SiblingRecord::where('family_record_id', $family->id)->get();
        $orgs = OrgRecord::where('student_record_id', $student->id)->get();
        $grades = Grade::where('scholar_id', $scholar->id)->with('school_year')->get();
        $user = User::where('id', $scholar->user_id)->first();

        return Inertia::render('Staff/Scholars/Scholar_Information', [
            'scholar' => $scholar,
            'student' => $student,
            'education' => $education,
            'family' => $family,
            'siblings' => $siblings,
            'orgs' => $orgs,
            'grades' => $grades,
            'user' => $user,
        ]);
    }
}
