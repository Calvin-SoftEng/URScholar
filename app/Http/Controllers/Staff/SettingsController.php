<?php

namespace App\Http\Controllers\Staff;

use App\Models\Campus;
use App\Models\Course;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\Sponsor;
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

        return Inertia::render(
            'Staff/Settings/Settings_Sponsor',
            ['sponsors' => $sponsors]
        );
    }

    public function adding()
    {

        $students = Student::with('campus', 'course')->get();
        return Inertia::render(
            'Staff/Settings/Adding_Students',
            ['students' => $students]
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

        // Store the MOA file
        $filePath = $request->file('file')->store('sponsor/moa', 'public');


        // dd($originalFileName);
        // Save sponsor record in the database
        Sponsor::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'since' => $request->since,
            'moa_file' => $filePath,
            'description' => $request->description,
            'logo' => $originalFileName, // Save only the filename in the database
        ]);


        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Created a new sponsor: ' . $request->name,
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
    
            // Prepare course lookup (similar to your existing code)
            $standardizedCourseLookup = $this->prepareCourseStandardizedLookup($courses);
    
            // Read CSV file
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
    
            // Validate CSV headers
            $requiredHeaders = ['first_name', 'last_name', 'email', 'course', 'campus', 'year_level', 'semester'];
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
    
            // Process each record
            foreach ($csv->getRecords() as $index => $record) {
                // Validate required fields
                $validationErrors = $this->validateStudentRecord($record);
                
                if (!empty($validationErrors)) {
                    $importErrors[] = [
                        'row' => $index + 2, // +2 because of header and 1-based indexing
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
    
                // Course matching logic (similar to your existing code)
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
                    'first_name' => $record['first_name'],
                    'last_name' => $record['last_name'],
                    'email' => $record['email'],
                    'campus_id' => $campusId,
                    'course_id' => $courseId,
                    'year_level' => $record['year_level'],
                    'semester' => $record['semester'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
    
                $successCount++;
            }
    
            // Bulk insert students
            if (!empty($insertData)) {
                Student::insert($insertData);
            }
    
            // Log the import activity
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => "Imported {$successCount} students (Skipped {$skipCount})",
            ]);
    
            // Prepare flash message
            $flashMessage = "Successfully imported {$successCount} students";
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
            return redirect()->back()->with('error', 'An unexpected error occurred during import. Please try again.');
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

    public function scholarship_forms()
    {

        $scholarship_form = ScholarshipForm::all();
        $scholarship_form_data = ScholarshipFormData::all();

        return Inertia::render('Staff/Settings/Scholarship_Forms', [
            'scholarship_form' => $scholarship_form,
            'scholarship_form_data' => $scholarship_form_data,
        ]);
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
}
