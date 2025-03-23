<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Scholar;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Requirements;
use App\Models\SubmittedRequirements;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class ScholarController extends Controller
{
    public function scholars()
    {

        return Inertia::render('Staff/Scholars/Scholars', [
            'scholars' => Scholar::where('status', 'verified')->get(),
            'userType' => Auth::user()->usertype,
            'coordinatorCampus' => Auth::user()->usertype === 'coordinator' ? Auth::user()->campus : null
        ]);
    }

    public function scholar($id)
    {
        $scholar = Scholar::findOrFail($id);

        $scholarship = $scholar->scholarship;
        $batch = Batch::where('scholarship_id', $scholarship->id)->first();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->first();

        // Get the submitted requirements for this scholar
        $submittedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)->get();

        return Inertia::render('Staff/Scholarships/Scholar_Scholarship-Details', [
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
            ->where('school_year', $selectedYear)
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

    public function downloadFile(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'filePath' => 'required|string',
            'fileName' => 'required|string',
        ]);

        $filePath = $validated['filePath'];
        $fileName = $validated['fileName'];

        // Check if file exists
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Determine the MIME type
        $mimeType = Storage::mimeType($filePath);

        // Return file as a download
        return Storage::download($filePath, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function upload(Request $request, Scholarship $scholarship)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'semester' => 'required',
            'schoolyear' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid file format',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $firstRecord = $csv->fetchOne();

            // Get all records
            $records = iterator_to_array($csv->getRecords());

            // Check for existing scholars in the system
            $duplicateScholars = [];
            $duplicateStudents = [];

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

                // Add more variations as needed
            }

            // Rest of your duplicate checking code remains the same
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
                    'student' => 'Update mo naman ako lods',
                ])->withInput();
            }

            foreach ($records as $record) {
                $existingScholar = Scholar::where('scholarship_id', $scholarship->id)
                    ->where('last_name', $record['LASTNAME'] ?? '')
                    ->where('first_name', $record['FIRSTNAME'] ?? '')
                    ->where('middle_name', $record['MIDDLENAME'] ?? '')
                    ->where('year_level', $record['YEAR LEVEL'] ?? '')
                    ->first();

                if ($existingScholar) {
                    $duplicateScholars[] = $existingScholar;
                }
            }

            // If duplicates found, return error message
            if (count($duplicateScholars) > 0) {
                $insertData = [];

                foreach ($duplicateScholars as $scholar) {
                    $insertData[] = [
                        'scholarship_id' => $scholar->scholarship_id,
                        'batch_id' => $scholar->batch_id,
                        'scholar_id' => $scholar->id,
                        'school_year' => $request->school_year,
                        'semester' => $request->semester,
                        'status' => 'Active',
                    ];
                }

                dd($insertData);
            }

            $batch = Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => $firstRecord['BATCH NO.'],
                'school_year' => $request->schoolyear,
                'semester' => $request->semester,
            ]);

            // Get the next available urscholar_id
            $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
                ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
                ->value('urscholar_id');

            $nextId = 1; // Default starting number
            if ($highestId) {
                $currentNumber = (int) substr($highestId, 4);
                $nextId = $currentNumber + 1;
            }

            // Add this for debugging - collect course mapping results
            $courseMatching = [];

            $insertData = [];
            foreach ($records as $record) {
                // Generate urscholar_id with leading zeros (URS-0001 format)
                $urscholarId = 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                $nextId++;

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
                        // This handles cases where "BS in Information Technology" needs to match 
                        // "Bachelor of Science in Information Technology"
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

                $insertData[] = [
                    'scholarship_id' => $scholarship->id,
                    'batch_id' => $batch->id,
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

            // Uncomment for debugging
            // dd([
            //     'course_matching_results' => $courseMatching,
            //     'all_standardized_keys' => array_keys($standardizedCourseLookup),
            //     'sample_data' => array_slice($insertData, 0, 3)
            // ]);

            // Bulk insert scholars
            Scholar::insert($insertData);

            // Rest of your code remains the same
            $scholars = Scholar::where('batch_id', $batch->id)->get();
            $students = Student::all();
            $matchedCount = 0;
            $unmatchedCount = 0;


            //Update the total scholars in the batch
            $batch->update([
                'total_scholars' => count($scholars)
            ]);

            // Rest of your code remains the same
            $scholars = Scholar::where('batch_id', $batch->id)->get();
            $students = Student::all();
            $matchedCount = 0;
            $unmatchedCount = 0;

            foreach ($scholars as $scholar) {
                $matched = $students->first(function ($student) use ($scholar) {
                    return $student->course_id === $scholar->course_id &&
                        $student->campus_id === $scholar->campus_id &&
                        $scholar->year_level == $student->year_level &&
                        $scholar->first_name == $student->first_name &&
                        $scholar->last_name == $student->last_name;
                });

                // Update status directly without collecting in arrays
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

            $schoolyear = SchoolYear::find($request->schoolyear);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => 'Scholars added to ' . $scholarship->name . ' for ' . $schoolyear->name . ' ' . $request->semester,
            ]);

            return redirect()->to("/scholarships/{$scholarship->id}?selectedSem={$request->semester}&selectedYear={$request->schoolyear}")
                ->with('flash', [
                    'type' => 'success',
                    'message' => "Successfully imported " . count($records) . " records. Matched students: {$matchedCount}. Unmatched students: {$unmatchedCount}."
                ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing CSV file: ' . $e->getMessage(),
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
