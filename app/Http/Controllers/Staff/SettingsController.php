<?php

namespace App\Http\Controllers\Staff;

use App\Mail\SendEmail;
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
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Illuminate\Support\Str;

class SettingsController extends Controller
{

    public function index()
    {

        $sponsors = Sponsor::with('assign')->get();
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


        $currentUser = Auth::user();

        $school_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        $students = Student::with('campus', 'course')
            ->where('campus_id', $currentUser->campus_id)
            ->get();


        return Inertia::render(
            'Staff/Settings/Adding_Students',
            [
                'students' => $students,
                'current_year' => $current_year,
                'schoolyears' => $school_year,
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
            'sponsor_name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Store the logo file in the local directory with a known path
        $logoFile = $request->file('img');

        // $logoFileName = $request->imgName;
        $originalFileName = $logoFile->getClientOriginalName();


        Storage::disk('public')->putFileAs('sponsor/logo', $logoFile, $originalFileName);

        $moaFile = $request->file('file');
        $moa = $moaFile->getClientOriginalName();

        // Store the MOA file

        $filePath = Storage::disk('public')->putFileAs(
            'sponsor/moa',
            $request->file('file'),
            $moa
        );

        // dd($originalFileName);
        // Save sponsor record in the database

        $password = Str::random(8);

        $user = User::create([
            'name' => $request->sponsor_name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);

        // Sending Emails
        $mailData = [
            'title' => 'Welcome to the URScholar Portal',
            'body' => "Dear " . $request->sponsor_name . ",\n\n" .
                "Welcome to the URScholar Portal! We’re excited to have you on board as a valued sponsor in our scholarship program.\n\n" .
                "Below are your login credentials to access the system:\n\n" .
                "Email: " . $request->email . "\n" .
                "Password: " . $password . "\n\n" .
                "Please log in to your account to begin exploring the features and managing your sponsorship activities.\n\n" .
                "If you have any questions or need assistance, feel free to reach out to our support team.\n\n" .
                "Best regards,\n" .
                "URScholar Team"
        ];

        // Create mailable instance
        $email = new SendEmail($mailData);

        Mail::to($request->email)->send($email);

        $sponsor = Sponsor::create([
            'name' => $request->name,
            'created_id' => Auth::user()->id,
            'assign_id' => $user->id,
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

    public function storeMOA(Request $request)
    {

        $request->validate([
            'sponsor_id' => 'required|exists:sponsors,id',
            'moa_file' => 'required|file|max:4096', // Max 4MB
        ]);

        // dd($request->all());

        // Check if the current user is the assigned user for this sponsor
        $sponsor = Sponsor::findOrFail($request->sponsor_id);

        if (Auth::id() != $sponsor->created_id) {
            return redirect()->back()->with('error', 'You do not have permission to add MOAs for this sponsor.');
        }

        // Store the file
        $moaFile = $request->file('moa_file');
        $moaFileName = $moaFile->getClientOriginalName();
        $moaFile->storeAs('public/sponsor/moa', $moaFileName);

        // Create the MOA record
        $moa = new SponsorMoa();
        $moa->sponsor_id = $request->sponsor_id;
        $moa->moa = $moaFileName;
        $moa->status = 'Active';
        $moa->save();

        
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Store Sponsor MOA',
            'description' => 'Uploaded a new MOA for sponsor: ' . $sponsor->name,
        ]);

        return redirect()->back()->with('success', 'MOA uploaded successfully.');
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

        
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update Sponsor',
            'description' => 'Updated sponsor: ' . $sponsor->name,
        ]);

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
                'student_number', 'first_name', 'last_name', 'email', 'course',
                'campus', 'year_level', 'semester', 'age', 'religion',
                'birthplace', 'birthdate', 'civil_status', 'permanent_address',
                'facebook_account', 'contact_no',
            ];
    
            $headers = $csv->getHeader();
            $missingHeaders = array_diff($requiredHeaders, $headers);
    
            if (!empty($missingHeaders)) {
                // return redirect()->back()->with('error', 'Missing required columns: ' . implode(', ', $missingHeaders));
                return back()->withErrors([
                    'file' => 'Missing required columns: ' . implode(', ', $missingHeaders),
                ])->withInput();
            }
    
            // Prepare insert data and tracking
            $insertData = [];
            $importErrors = [];
            $successCount = 0;
            $skipCount = 0;
    
            // Get current academic year
            $current_year = AcademicYear::where('status', 'Active')->first();
            if (!$current_year) {
                return redirect()->back()->with('error', 'No active academic year found.');
            }
    
            // Get the current user's campus ID
            $userCampusId = Auth::user()->campus_id;
    
            // Process each record for student import
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
    
            // Get all the student numbers we just imported
            $importedStudentNumbers = array_column($insertData, 'student_number');
    
            // Handle scholar matching after all students have been inserted
            $matchedScholars = 0;
            $unmatchedScholars = 0;
            $school_year = AcademicYear::where('status', 'Active')->first();
    
            if ($school_year && $userCampusId) {
                // First, mark unmatched scholars as Unverified/Unenrolled
                $unmatchedCampusScholars = Scholar::where('campus_id', $userCampusId)
                    ->where(function($query) use ($importedStudentNumbers) {
                        $query->where('status', '!=', 'Verified')
                            ->whereNull('student_number')
                            ->orWhereNotIn('student_number', $importedStudentNumbers);
                    })
                    ->get();
    
                foreach ($unmatchedCampusScholars as $scholar) {
                    // Update scholar status
                    $scholar->status = 'Unverified';
                    $scholar->student_status = 'Unenrolled';
                    $scholar->save();
    
                    // Update grantee status
                    Grantees::where('scholar_id', $scholar->id)
                        ->where('school_year_id', $school_year->school_year_id)
                        ->update(['status' => 'Pending']);
    
                    $unmatchedScholars++;
                }
    
                // Now process imported students to match them with scholars
                foreach ($insertData as $studentData) {
                    // Look for exact match first
                    $matchingScholar = Scholar::where('first_name', $studentData['first_name'])
                        ->where('last_name', $studentData['last_name'])
                        ->where('campus_id', $studentData['campus_id'])
                        ->where('course_id', $studentData['course_id'])
                        ->first();
    
                    if ($matchingScholar && $matchingScholar->campus_id == $userCampusId) {
                        // Update matched scholar
                        $matchingScholar->status = 'Verified';
                        $matchingScholar->student_status = 'Enrolled';
                        $matchingScholar->student_number = $studentData['student_number'];
                        $matchingScholar->email = $studentData['email'];
                        $matchingScholar->save();
    
                        // Handle grantee relationship
                        $this->updateOrCreateGrantee($matchingScholar, $school_year);
                        
                        $matchedScholars++;
                    } else {
                        // Try fuzzy matching for potential scholars
                        $potentialScholars = Scholar::where('campus_id', $studentData['campus_id'])
                            ->where('course_id', $studentData['course_id'])
                            ->where(function ($query) use ($studentData) {
                                $query->where('last_name', 'like', $studentData['last_name'] . '%')
                                    ->orWhere('first_name', 'like', $studentData['first_name'] . '%');
                            })
                            ->where('status', '!=', 'Verified')
                            ->where('campus_id', $userCampusId)
                            ->get();
    
                        foreach ($potentialScholars as $scholar) {
                            // Mark potential matches as needing verification
                            $scholar->status = 'Unverified';
                            $scholar->student_status = 'Unenrolled';
                            $scholar->save();
    
                            Grantees::where('scholar_id', $scholar->id)
                                ->where('school_year_id', $school_year->school_year_id)
                                ->update(['status' => 'Pending']);
                        }
                    }
                }
            }
    
            // Log the import activity
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => "Imported {$successCount} students)",
            ]);
    
            // Prepare success message
            $flashMessage = "Successfully imported {$successCount} students";
            // if ($matchedScholars > 0) {
            //     $flashMessage .= ", verified {$matchedScholars} scholars";
            // }
            // if ($unmatchedScholars > 0) {
            //     $flashMessage .= ", {$unmatchedScholars} scholars remain unverified";
            // }
            // if ($skipCount > 0) {
            //     $flashMessage .= " (Skipped {$skipCount} rows with errors)";
            // }
    
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
    
    /**
     * Helper method to update or create a grantee record
     */
    private function updateOrCreateGrantee($scholar, $school_year)
    {
        $grantee = Grantees::where('scholar_id', $scholar->id)
            ->where('school_year_id', $school_year->school_year_id)
            ->where('semester', $school_year->semester)
            ->first();
    
        if ($grantee) {
            // Update existing grantee
            $grantee->status = 'Pending';
            $grantee->student_status = 'Enrolled';
            $grantee->save();
        } else {
            // Create new grantee relationship
            Grantees::create([
                'scholarship_id' => $scholar->scholarship_id,
                'batch_id' => $scholar->batch_id,
                'scholar_id' => $scholar->id,
                'school_year_id' => $school_year->school_year_id,
                'semester' => $school_year->semester,
                'status' => 'Pending'
            ]);
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

        $eligibility = Eligibility::where('status', 'Active')->get();
        $condition = Condition::where('status', 'Active')->get();

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

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Created a new eligibility category: ' . $request->name,
        ]);
        

        return redirect()->back()->with('success', 'Eligibility category created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function eligibilities_update(Request $request, Eligibility $eligibility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $eligibility->update($validated);

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update',
            'description' => 'Updated eligibility category: ' . $request->name,
        ]);

        return redirect()->back()->with('success', 'Eligibility category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eligibilities_destroy(Eligibility $eligibility)
    {
        $eligibility->update([
            'status' => 'Inactive',
            'user_id' => Auth::user()->id,
        ]);

        $eligibility->condition()->update([
            'status' => 'Inactive',
            'user_id' => Auth::user()->id,
        ]);

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Archive',
            'description' => 'Archived eligibility category: ' . $eligibility->name,
        ]);

        return redirect()->back()->with('success', 'Eligibility category archived successfully');
    }

    public function eligibilities_restore(Eligibility $eligibility)
    {
        
        $eligibility->update([
            'status' => 'Active',
            'user_id' => null,
        ]);

        
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Archive',
            'description' => 'Restored eligibility category: ' . $eligibility->name,
        ]);

        return redirect()->back()->with('success', 'Eligibility category restored successfully');
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

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Created a new condition: ' . $request->name,
        ]);

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

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update',
            'description' => 'Updated condition: ' . $request->name,
        ]);

        return redirect()->back()->with('success', 'Condition updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function conditions_destroy(Condition $condition)
    {
        $condition->update([
            'status' => 'Inactive',
            'user_id' => Auth::user()->id,
        ]);

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Archive',
            'description' => 'Archived condition: ' . $condition->name,
        ]);

        return redirect()->back()->with('success', 'Condition archived successfully');
    }

    public function conditions_restore(Condition $condition)
    {
        $condition->update([
            'status' => 'Active',
            'user_id' => null,
        ]);

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Archive',
            'description' => 'Restored condition: ' . $condition->name,
        ]);

        return redirect()->back()->with('success', 'Condition restored successfully');
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

        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Created a new scholarship form: ' . $request->name,
        ]);


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
        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Update',
            'description' => 'Updated scholarship form: ' . $request->name,
        ]);

        return redirect()->back()->with('success', 'Scholarship form updated successfully.');
    }

    /**
     * Remove the specified scholarship form.
     */
    public function destroy(ScholarshipForm $scholarshipForm)
    {
        $scholarshipForm->update([
            'status' => 'Inactive'
        ]);
        // Create a new activity log entry
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Archive',
            'description' => 'Archived scholarship form: ' . $scholarshipForm->name,
        ]);

        return redirect()->back()->with('success', 'Scholarship form arhived successfully.');
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
        $scholarshipFormData->update([
            'status' => 'Inactive'
        ]);

        return redirect()->back()->with('success', 'Criteria Archived successfully.');
    }

    public function archives()
    {
        
        $eligibility = Eligibility::where('status', 'Inactive')
        ->with('user')
        ->get();
        $condition = Condition::where('status', 'Inactive')
        ->with('user')
        ->get();

        return Inertia::render(
            'Staff/Settings/Archives', [
                'eligibility' => $eligibility,
                'condition' => $condition,
            ]
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
