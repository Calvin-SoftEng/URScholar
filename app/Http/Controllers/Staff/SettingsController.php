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
        // Check if file exists in the request
        $file = $request->file('file');

        \Log::info('Request data:', ['files' => $request->all()]);


        if (!$file) {
            return response()->json(['message' => 'No file uploaded'], 400);
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

            // Add more variations as needed
        }

        // Proceed with file processing
        try {
            // $data = array_map('str_getcsv', file($file->getRealPath()));
            // $header = array_shift($data);
            // $requiredHeaders = ['first_name', 'last_name', 'email', 'course', 'campus', 'year_level'];

            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $firstRecord = $csv->fetchOne();

            // Get all records
            $records = iterator_to_array($csv->getRecords());

            // if (array_diff($requiredHeaders, $header)) {
            //     return response()->json(['message' => 'Invalid CSV format'], 422);
            // }

            $insertData = [];
            foreach ($records as $record) {

                // Determine campus_id from campus name
                $campusName = strtolower($record['campus'] ?? null);
                $campusId = null;


                if ($campusName && isset($campuses[$campusName])) {
                    $campusId = $campuses[$campusName];
                }

                // MULTIPLE APPROACH COURSE MATCHING
                $csvCourseName = $record['course'] ?? null;
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
            }

            Student::insert($insertData);

            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Create',
                'description' => 'Added ' . count($insertData) . ' students',
            ]);

            return redirect()->back()->with('success', 'Scholars added to the scholarship!');
        } catch (\Exception $e) {
            \Log::error('Error during file upload: ' . $e->getMessage());
            return response()->json(['message' => 'Error during file upload'], 500);
        }
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
