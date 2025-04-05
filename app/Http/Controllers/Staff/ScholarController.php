<?php

namespace App\Http\Controllers\Staff;

use App\Events\NewNotification;
use App\Models\StudentNotifier;
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
use App\Models\SubmittedRequirements;
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

        $scholars = Scholar::with('user', 'campus', 'course', 'batch')->where('status', 'verified')->get();

        return Inertia::render('Staff/Scholars/Scholars', [
            'scholars' => $scholars,
            'userType' => Auth::user()->usertype,
            'coordinatorCampus' => Auth::user()->usertype === 'coordinator' ? Auth::user()->campus : null
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
        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)->get();

        $notify = StudentNotifier::where('scholar_id', $scholar->id)
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

    public function scholar_notifier($scholarID)
    {
        $scholar = Scholar::findOrFail($scholarID);

        StudentNotifier::create([
            'scholar_id' => $scholar->id,
            'title' => 'Grade Notification',
            'message' => 'You need to update your grade.',
            'type' => 'scholarship_notification',
        ]);

        return back()->with('success', 'Notify scholar successfully!');
    }

    public function scholar_onetime($id)
    {
        $scholar = Scholar::with('user', 'campus', 'course')->findOrFail($id);
        $applicant = Applicant::where('scholar_id', $scholar->id)->first();

        $scholarship = $applicant->scholarship;
        $batch = Batch::where('id', $applicant->batch_id)->first();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->first();

        // Get the submitted requirements for this scholar
        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)->get();

        return Inertia::render('Staff/Scholarships/One-Time/Applicant-Details', [
            'scholar' => $scholar,
            'scholarship' => $scholarship,
            'batch' => $batch,
            'requirements' => $requirements,
            'submittedRequirements' => $submittedRequirements
        ]);
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

            // dd($userToNotify);
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


        // Define status-specific messages for the response
        $statusMessage = $request->status === 'Approved'
            ? 'Requirement approved successfully!'
            : 'Requirement returned successfully!';

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

        $scholars = $scholarship->scholars;
        $selectedYear = $request->input('selectedYear', '');
        $selectedSem = $request->input('selectedSem', '');

        $schoolyear = SchoolYear::where('id', $selectedYear)->first();

        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $selectedYear)
            ->where('semester', $selectedSem)
            ->get();

        $campuses = Campus::all();
        $course = Course::all();

        return Inertia::render(
            'Staff/Scholarships/AddingScholars',
            [
                'scholarship' => $scholarship,
                'scholars' => $scholars,
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
                'nofile' => 'Wrong file ya',
            ])->withInput();
        }

        try {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $firstRecord = $csv->fetchOne();

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

            if (!$duplicateStudents) {
                return back()->withErrors([
                    'student' => 'Uy lods, outdated na yung student info. Paki-update naman 😅',
                ])->withInput();
            }

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

            // Track campusBatches
            $campusBatches = [];

            // Process each record from the CSV
            foreach ($records as $record) {
                // Check if scholar already exists
                $existingScholar = Scholar::where('last_name', $record['LASTNAME'] ?? '')
                    ->where('first_name', $record['FIRSTNAME'] ?? '')
                    ->where('middle_name', $record['MIDDLENAME'] ?? '')
                    ->first();

                if ($existingScholar) {
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

                return back()->withErrors([
                    'student' => 'The following scholars already exist in this scholarship for the selected semester and school year: ' . $names
                ])->withInput();
            }

            // Insert new scholars
            $createdScholars = [];
            if (!empty($newScholarsData)) {
                // Bulk insert new scholars
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

            // Collect all scholar IDs (both existing and newly created)
            $allScholars = [];

            // Add existing scholars
            if (!empty($existingScholarIds)) {
                $existingScholars = Scholar::whereIn('id', $existingScholarIds)->get();
                $allScholars = array_merge($allScholars, $existingScholars->toArray());
            }

            // Add newly created scholars
            if (!empty($createdScholars)) {
                foreach ($createdScholars as $scholar) {
                    $allScholars[] = $scholar->toArray();
                }
            }

            // Group scholars by campus
            $scholarsByCompus = [];
            foreach ($allScholars as $scholar) {
                $campusId = $scholar['campus_id'];
                if ($campusId) {
                    if (!isset($scholarsByCompus[$campusId])) {
                        $scholarsByCompus[$campusId] = [];
                    }
                    $scholarsByCompus[$campusId][] = $scholar['id'];
                }
            }

            // Create or find batches for each campus
            foreach ($scholarsByCompus as $campusId => $scholarIds) {
                // Check if batch already exists for this campus
                $existingBatch = Batch::where('scholarship_id', $scholarship->id)
                    ->where('batch_no', $firstRecord['BATCH NO.'])
                    ->where('school_year_id', $request->schoolyear)
                    ->where('semester', $request->semester)
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
                    ]);
                    $campusBatches[$campusId] = $batch;
                } else {
                    $campusBatches[$campusId] = $existingBatch;
                }

                // Create grantee entries for scholars in this campus
                $granteesData = [];
                foreach ($scholarIds as $scholarId) {
                    $granteesData[] = [
                        'scholarship_id' => $scholarship->id,
                        'batch_id' => $campusBatches[$campusId]->id,
                        'scholar_id' => $scholarId,
                        'school_year_id' => $request->schoolyear,
                        'semester' => $request->semester,
                        'status' => 'Active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert grantees for this campus
                if (!empty($granteesData)) {
                    Grantees::insert($granteesData);
                }

                // Update the batch total scholars
                $campusBatches[$campusId]->update([
                    'total_scholars' => count($scholarIds)
                ]);
            }

            // Student verification process for new scholars only
            $students = Student::all();
            $matchedCount = 0;
            $unmatchedCount = 0;

            foreach ($createdScholars as $scholar) {
                $matched = $students->first(function ($student) use ($scholar) {
                    return $student->course_id === $scholar->course_id &&
                        $student->campus_id === $scholar->campus_id &&
                        $scholar->year_level == $student->year_level &&
                        $scholar->first_name == $student->first_name &&
                        $scholar->last_name == $student->last_name;
                });

                // Update status directly
                if ($matched) {
                    // Update status and copy email from matched student
                    $scholar->update([
                        'status' => 'Verified',
                        'email' => $matched->email // Copy email from matched student
                    ]);
                    $matchedCount++;
                } else {
                    $scholar->update(['status' => 'Unverified']);
                    $unmatchedCount++;
                }
            }

            // Update Scholarship Status
            $scholarship->update([
                'status' => 'Active'
            ]);

            // Get campuses for all scholars
            $campusIds = array_keys($campusBatches);
            $campusesForGroups = Campus::whereIn('id', $campusIds)->get();

            // Create scholarship group for current user
            ScholarshipGroup::create([
                'user_id' => Auth::id(),
                'scholarship_id' => $scholarship->id,
            ]);

            // Create scholarship groups for coordinators and cashiers
            foreach ($campusesForGroups as $campus) {
                // Find coordinator
                $coordinator = User::find($campus->coordinator_id);

                // Find cashier
                $cashier = User::find($campus->cashier_id);

                // Insert messages for coordinator
                if ($coordinator) {
                    ScholarshipGroup::create([
                        'user_id' => $coordinator->id,
                        'scholarship_id' => $scholarship->id,
                    ]);
                }

                // Insert messages for cashier
                if ($cashier) {
                    ScholarshipGroup::create([
                        'user_id' => $cashier->id,
                        'scholarship_id' => $scholarship->id,
                    ]);
                }
            }

            $schoolyear = SchoolYear::find($request->schoolyear);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => 'Scholars added to ' . $scholarship->name . ' for ' . $schoolyear->name . ' ' . $request->semester,
            ]);

            $existingScholarsCount = count($existingScholarIds);
            $newScholarsCount = count($createdScholars);
            $totalCampuses = count($campusBatches);

            return redirect()->to("/scholarships/{$scholarship->id}?selectedSem={$request->semester}&selectedYear={$request->schoolyear}")
                ->with('flash', [
                    'type' => 'success',
                    'message' => "Successfully processed " . count($records) . " records. Added {$existingScholarsCount} existing scholars and created {$newScholarsCount} new scholars across {$totalCampuses} campuses. New scholars - Matched: {$matchedCount}, Unmatched: {$unmatchedCount}."
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing CSV file: ' . $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'status' => 'error'
            ], 500);
        }
    }

    public function manual(Request $request, Scholarship $scholarship)
    {
        $request->validate([
            'grant' => 'required',
            'batch_id' => 'required',
            'hei_name' => 'required',
            'campus' => 'required',
            'course' => 'required',
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
        ]);

        $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
            ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
            ->value('urscholar_id');

        $nextId = 1; // Default starting number
        if ($highestId) {
            $currentNumber = (int) substr($highestId, 4);
            $nextId = $currentNumber + 1;
        }

        $scholar = Scholar::create([
            'grant' => $request->grant,
            'batch_id' => $request->batch_id,
            'hei_name' => $request->hei_name,
            'urscholar_id' => 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT),
            'campus' => $request->campus,
            'course' => $request->course,
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
            'scholarship_id' => $scholarship->id,
        ]);

        dd($scholar);

        // You might want to handle any relationships here
        // For example, attaching the scholar to the scholarship if needed

        return redirect()->back()->with('success', 'Scholar added successfully!');

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

    public function scholar_information()
    {
        return Inertia::render('Staff/Scholars/Scholar_Information');
    }
}
