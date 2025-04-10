<?php

namespace App\Http\Controllers\Staff;

use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Condition;
use App\Models\Course;
use App\Models\Eligibility;
use App\Models\SponsorMoa;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\Sponsor;
use App\Models\Scholar;
use App\Models\Grantees;
use App\Models\SchoolYear;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class SettingsController extends Controller
{

    public function index()
    {

        $sponsors = Sponsor::all();
        $moa = SponsorMoa::all();

        return Inertia::render(
            'Staff/Settings/Settings_Sponsor',
            ['sponsors' => $sponsors, 'moa' => $moa]
        );
    }

    public function adding()
    {

        $current_year = AcademicYear::where('status', 'Active')
            ->with('school_year')
            ->first();



        $students = Student::with('campus', 'course')
            ->where('academic_year_id', $current_year->id)
            ->get();


        return Inertia::render(
            'Staff/Settings/Adding_Students',
            [
                'students' => $students,
                'current_year' => $current_year,

            ]
        );
    }

    public function create_sponsor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file',
            'description' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imgName' => 'required|string',
            'abbreviation' => 'required|string|max:255',
            'since' => 'required|string|max:255',
        ]);




        // Store the logo file in the local directory with a known path
        $logoFile = $request->file('img');

        // $logoFileName = $request->imgName;
        $originalFileName = $logoFile->getClientOriginalName();


        Storage::disk('public')->putFileAs('sponsor/logo', $logoFile, $originalFileName);

        $moaFile = $request->file('file');
        $moa = $moaFile->getClientOriginalName();

        // Store the MOA file
        $filePath = $request->file('file')->store('sponsor/moa/' . $moa, 'public');

        // dd($originalFileName);
        // Save sponsor record in the database
        $sponsor = Sponsor::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
            'abbreviation' => $request->abbreviation,
            'since' => $request->since,
            'description' => $request->description,
            'logo' => $originalFileName, // Save only the filename in the database
        ]);

        SponsorMoa::create([
            'sponsor_id' => $sponsor->id,
            'moa' => $moa,
            'status' => 'Active',
        ]);


        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Created a new sponsor: ' . $request->name,
        ]);

        return redirect()->route('sponsor.index')->with('success', 'Sponsor added successfully!');
    }

    public function sponsor_update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
            'since' => 'required|string|max:255',
            'description' => 'nullable|string',
            'moa_file' => 'nullable|file|mimes:svg,png,jpg,jpeg,docx,doc,pdf|max:4096',
            'logo' => 'nullable|file|mimes:svg,png,jpg,jpeg|max:4096',
        ]);

        // Update basic sponsor information
        $sponsor->name = $validated['name'];
        $sponsor->abbreviation = $validated['abbreviation'];
        $sponsor->since = $validated['since'];
        $sponsor->description = $validated['description'] ?? $sponsor->description;

        // Handle MOA file upload
        if ($request->hasFile('moa_file')) {
            $moaFile = $request->file('moa_file');
            $moaFileName = time() . '_' . $moaFile->getClientOriginalName();
            $moaPath = $moaFile->storeAs('sponsor/moa', $moaFileName, 'public');

            // Create new MOA record
            SponsorMoa::create([
                'sponsor_id' => $sponsor->id,
                'moa' => $moaFileName,
                'moa_path' => $moaPath,
                'status' => 'Active'
            ]);

            // Update the sponsor's current MOA file reference
            $sponsor->moa_file = $moaFileName;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoFileName = time() . '_' . $logoFile->getClientOriginalName();
            $logoPath = $logoFile->storeAs('sponsor/logo', $logoFileName, 'public');
            $sponsor->logo = $logoFileName;
        }

        $sponsor->save();

        return redirect()->route('sponsor.index')->with('success', 'Sponsor added successfully!');
    }

    public function store_student(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ], [
            'file.required' => 'Please upload a CSV file.',
            'file.mimes' => 'The file must be a CSV file.'
        ]);

        // Check if file exists in the request
        $file = $request->file('file');

        // Log the initial file upload
        \Log::info('Attempting to import students from file', ['filename' => $file->getClientOriginalName()]);

        try {
            // Get all campuses for efficient lookup
            $campuses = Campus::all()->mapWithKeys(function ($campus) {
                return [strtolower($campus->name) => $campus->id];
            })->toArray();

            // Get all courses with their names and abbreviations
            $courses = Course::select('id', 'name', 'abbreviation')->get();

            // Prepare course lookup
            $standardizedCourseLookup = $this->prepareCourseStandardizedLookup($courses);

            // Read CSV file
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            // Validate CSV headers
            $requiredHeaders = [
                'student_number',
                'first_name',
                'last_name',
                'email',
                'course',
                'campus',
                'year_level',
                'semester',
                'age',
                'religion',
                'birthplace',
                'birthdate',
                'civil_status',
                'permanent_address',
                'facebook_account',
                'contact_no',
            ];

            $headers = $csv->getHeader();
            $missingHeaders = array_diff($requiredHeaders, $headers);

            if (!empty($missingHeaders)) {
                return redirect()->back()->with('error', 'Missing required columns: ' . implode(', ', $missingHeaders));
            }

            // Prepare insert data and tracking
            $insertData = [];
            $importErrors = [];
            $successCount = 0;
            $skipCount = 0;

            // Get current academic year
            $current_year = AcademicYear::where('status', 'Active')->first();

            // Process each record
            foreach ($csv->getRecords() as $index => $record) {
                // Validate required fields
                $validationErrors = $this->validateStudentRecord($record);

                if (!empty($validationErrors)) {
                    $importErrors[] = [
                        'row' => $index + 2,
                        'errors' => $validationErrors
                    ];
                    $skipCount++;
                    continue;
                }

                // Determine campus
                $campusName = strtolower(trim($record['campus'] ?? ''));
                $campusId = $campuses[$campusName] ?? null;

                if (!$campusId) {
                    $importErrors[] = [
                        'row' => $index + 2,
                        'errors' => ["Campus '{$record['campus']}' not found"]
                    ];
                    $skipCount++;
                    continue;
                }

                // Course matching logic
                $courseId = $this->matchCourse($record['course'], $standardizedCourseLookup, $courses);

                if (!$courseId) {
                    $importErrors[] = [
                        'row' => $index + 2,
                        'errors' => ["Course '{$record['course']}' not found"]
                    ];
                    $skipCount++;
                    continue;
                }

                // Prepare student data
                $insertData[] = [
                    'student_number' => $record['student_number'],
                    'first_name' => $record['first_name'],
                    'last_name' => $record['last_name'],
                    'email' => $record['email'],
                    'campus_id' => $campusId,
                    'course_id' => $courseId,
                    'year_level' => $record['year_level'],
                    'semester' => $record['semester'],
                    'age' => $record['age'],
                    'religion' => $record['religion'],
                    'birthplace' => $record['birthplace'],
                    'birthdate' => $record['birthdate'],
                    'civil_status' => $record['civil_status'],
                    'permanent_address' => $record['permanent_address'],
                    'facebook_account' => $record['facebook_account'],
                    'contact_no' => $record['contact_no'],
                    'academic_year_id' => $current_year->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $successCount++;
            }

            // Bulk insert students
            if (!empty($insertData)) {
                Student::insert($insertData);
            } else {
                return redirect()->back()->with('error', 'No valid student records found for import.');
            }

            // Now perform scholar matching after all students have been inserted
            $matchedScholars = 0;
            $unmatchedScholars = 0;
            $school_year = AcademicYear::where('status', 'Active')->first();

            if ($school_year) {
                // Process each inserted student for scholar matching
                foreach ($insertData as $studentData) {
                    try {
                        // Search for matching scholar
                        $matchingScholar = Scholar::where('email', $studentData['email'])
                            ->where('last_name', $studentData['last_name'])
                            ->where('campus_id', $studentData['campus_id'])
                            ->where('course_id', $studentData['course_id'])
                            ->first();

                        if ($matchingScholar) {
                            // Update scholar status to Verified and student_status to Enrolled
                            $matchingScholar->status = 'Verified';
                            $matchingScholar->student_status = 'Enrolled';
                            $matchingScholar->save();

                            // Check if a grantee relationship exists
                            $grantee = Grantees::where('scholar_id', $matchingScholar->id)
                                ->where('school_year_id', $school_year->school_year_id)
                                ->where('semester', $school_year->semester)
                                ->first();

                            if ($grantee) {
                                // Update existing grantee status
                                $grantee->status = 'Active';
                                $grantee->save();
                            } else {

                                $grantee = Grantees::where('scholar_id', $matchingScholar->id)
                                    ->where('school_year_id', $school_year->school_year_id)
                                    ->where('semester', $school_year->semester)
                                    ->first();

                                if (!$grantee) {
                                    // Create new grantee relationship
                                    Grantees::create([
                                        'scholarship_id' => $matchingScholar->scholarship_id,
                                        'batch_id' => $grantee->batch_id,
                                        'scholar_id' => $matchingScholar->id,
                                        'school_year_id' => $school_year->school_year_id,
                                        'semester' => $school_year->semester,
                                        'status' => 'Active'
                                    ]);
                                }
                            }

                            $matchedScholars++;
                        } else {
                            // Try to find scholars in the same campus and course but without exact name match
                            $potentialScholars = Scholar::where('campus_id', $studentData['campus_id'])
                                ->where('course_id', $studentData['course_id'])
                                ->where(function ($query) use ($studentData) {
                                    $query->where('last_name', 'like', $studentData['last_name'] . '%')
                                        ->orWhere('first_name', 'like', $studentData['first_name'] . '%');
                                })
                                ->where('status', '!=', 'Verified')
                                ->get();

                            foreach ($potentialScholars as $scholar) {
                                // Set them to Unverified and Unenrolled explicitly
                                $scholar->status = 'Unverified';
                                $scholar->student_status = 'Unenrolled';
                                $scholar->save();

                                // Set any existing grantee relationships to Pending
                                Grantees::where('scholar_id', $scholar->id)
                                    ->where('school_year_id', $school_year->school_year_id)
                                    ->update(['status' => 'Pending']);
                            }

                            $unmatchedScholars += $potentialScholars->count();
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error in scholar matching process', [
                            'message' => $e->getMessage(),
                            'student' => $studentData['student_number'] ?? 'N/A',
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Continue processing - don't let scholar matching errors stop student import
                    }
                }
            }

            // Log the import activity
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => "Imported {$successCount} students, matched {$matchedScholars} scholars (Skipped {$skipCount})",
            ]);

            // Prepare flash message
            $flashMessage = "Successfully imported {$successCount} students";
            if ($matchedScholars > 0) {
                $flashMessage .= ", verified {$matchedScholars} scholars";
            }
            if ($unmatchedScholars > 0) {
                $flashMessage .= ", {$unmatchedScholars} scholars remain unverified";
            }
            if ($skipCount > 0) {
                $flashMessage .= " (Skipped {$skipCount} rows with errors)";
            }

            // Prepare redirect with detailed information
            $redirect = redirect()->back()->with('success', $flashMessage);

            // Attach import errors if any
            if (!empty($importErrors)) {
                $redirect->with('importErrors', $importErrors);
            }

            return $redirect;

        } catch (\Exception $e) {
            // Log the full error for debugging
            \Log::error('Student import error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Return a user-friendly error message
            return redirect()->back()->with('error', 'An unexpected error occurred during import: ' . $e->getMessage());
        }
    }

    // Helper methods (to be added to the controller or a trait)
    private function validateStudentRecord($record)
    {
        $errors = [];

        // Basic validation
        if (empty(trim($record['first_name']))) {
            $errors[] = 'First name is required';
        }
        if (empty(trim($record['last_name']))) {
            $errors[] = 'Last name is required';
        }
        if (empty(trim($record['email'])) || !filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address';
        }
        // Add more validations as needed

        return $errors;
    }

    private function prepareCourseStandardizedLookup($courses)
    {
        $standardizedCourseLookup = [];

        foreach ($courses as $course) {
            // Similar to your existing implementation
            $standardizedCourseLookup[strtolower($course->name)] = $course->id;

            if (!empty($course->abbreviation)) {
                $standardizedCourseLookup[strtolower($course->abbreviation)] = $course->id;
            }

            // Add more standardization logic
            // ...
        }

        return $standardizedCourseLookup;
    }

    private function matchCourse($csvCourseName, $standardizedCourseLookup, $courses)
    {
        // Similar to your existing course matching logic
        $standardizedCsvName = strtolower(trim($csvCourseName));

        // Direct match first
        if (isset($standardizedCourseLookup[$standardizedCsvName])) {
            return $standardizedCourseLookup[$standardizedCsvName];
        }

        // Fuzzy matching logic
        $bestMatchScore = 0;
        $bestMatchId = null;

        foreach ($courses as $course) {
            // Extract core subject and match
            $coreSubject = preg_replace(
                '/^(bachelor of science in |bachelor of arts in |bs in |ba in |bs |ba )/',
                '',
                strtolower($course->name)
            );

            if (!empty($coreSubject) && stripos($standardizedCsvName, $coreSubject) !== false) {
                $score = strlen($coreSubject);
                if ($score > $bestMatchScore) {
                    $bestMatchScore = $score;
                    $bestMatchId = $course->id;
                }
            }
        }

        return $bestMatchId;
    }

    public function eligibilities_forms()
    {

        $eligibility = Eligibility::all();
        $condition = Condition::all();

        return Inertia::render('Staff/Settings/Eligibilities_Forms', [
            'eligibility' => $eligibility,
            'condition' => $condition,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function eligibilities_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        Eligibility::create($validated);

        return redirect()->back()->with('success', 'Eligibility category created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function eligibilities_update(Request $request, Eligibility $eligibility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'scholarship_id' => 'required|exists:scholarships,id',
        ]);

        $eligibility->update($validated);

        return redirect()->back()->with('success', 'Eligibility category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eligibilities_destroy(Eligibility $eligibility)
    {
        $eligibility->delete();

        return redirect()->back()->with('success', 'Eligibility category deleted successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function conditions_store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'eligibility_id' => 'required|exists:eligibilities,id',
        ]);

        Condition::create($validated);

        return redirect()->back()->with('success', 'Condition created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function conditions_update(Request $request, Condition $condition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'eligibility_id' => 'required|exists:eligibilities,id',
        ]);

        $condition->update($validated);

        return redirect()->back()->with('success', 'Condition updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function conditions_destroy(Condition $condition)
    {
        $condition->delete();

        return redirect()->back()->with('success', 'Condition deleted successfully');
    }

    public function verification_forms()
    {

        $scholarship_form = ScholarshipForm::all();
        $scholarship_form_data = ScholarshipFormData::all();

        return Inertia::render('Staff/Settings/Verification_Forms', [
            'scholarship_form' => $scholarship_form,
            'scholarship_form_data' => $scholarship_form_data,
        ]);
    }

    /**
     * Store a newly created scholarship form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ScholarshipForm::create($validated);

        return redirect()->back()->with('success', 'Scholarship form created successfully.');
    }

    /**
     * Update the specified scholarship form.
     */
    public function update(Request $request, ScholarshipForm $scholarshipForm)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $scholarshipForm->update($validated);

        return redirect()->back()->with('success', 'Scholarship form updated successfully.');
    }

    /**
     * Remove the specified scholarship form.
     */
    public function destroy(ScholarshipForm $scholarshipForm)
    {
        $scholarshipForm->delete();

        return redirect()->back()->with('success', 'Scholarship form deleted successfully.');
    }

    /**
     * Store a newly created scholarship form data.
     */
    public function storeData(Request $request)
    {
        $validated = $request->validate([
            'scholarship_form_id' => 'required|exists:scholarship_forms,id',
            'name' => 'required|string|max:255',
        ]);

        ScholarshipFormData::create($validated);

        return redirect()->back()->with('success', 'Criteria added successfully.');
    }

    /**
     * Update the specified scholarship form data.
     */
    public function updateData(Request $request, ScholarshipFormData $scholarshipFormData)
    {
        $validated = $request->validate([
            'scholarship_form_id' => 'required|exists:scholarship_forms,id',
            'name' => 'required|string|max:255',
        ]);

        $scholarshipFormData->update($validated);

        return redirect()->back()->with('success', 'Criteria updated successfully.');
    }

    /**
     * Remove the specified scholarship form data.
     */
    public function destroyData(ScholarshipFormData $scholarshipFormData)
    {
        $scholarshipFormData->delete();

        return redirect()->back()->with('success', 'Criteria deleted successfully.');
    }

    public function archives()
    {

        return Inertia::render(
            'Staff/Settings/Archives'
        );
    }

    public function user_activities()
    {
        $activity = ActivityLog::where('user_id', Auth::user()->id)->get();

        return Inertia::render(
            'Staff/Settings/User_Activities',
            [
                'activity' => $activity,
            ]
        );
    }
}
