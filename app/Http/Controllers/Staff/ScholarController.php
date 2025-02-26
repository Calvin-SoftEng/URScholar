<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        // dd($request);

        $requirement = SubmittedRequirements::findOrFail($request->submittedReq);
        $requirement->status = $request->status;

        if ($request->status === 'Returned') {
            $requirement->message = $request->message; // Save the return message
        } else {
            $requirement->message = null; // Clear remarks for other statuses
        }

        $requirement->save();

        return response()->json(['message' => 'Requirement status updated successfully!']);
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

            $batch = Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => $firstRecord['BATCH NO.'],
                'school_year' => $request->schoolyear,
                'semester' => $request->semester,
            ]);

            // Get all records after creating the batch
            $records = iterator_to_array($csv->getRecords());

            // Get the next available urscholar_id
            $highestId = Scholar::where('urscholar_id', 'LIKE', 'URS-%')
                ->orderByRaw('CAST(SUBSTRING(urscholar_id, 5) AS UNSIGNED) DESC')
                ->value('urscholar_id');

            $nextId = 1; // Default starting number
            if ($highestId) {
                $currentNumber = (int) substr($highestId, 4);
                $nextId = $currentNumber + 1;
            }

            $insertData = [];
            foreach ($records as $record) {
                // Generate urscholar_id with leading zeros (URS-0001 format)
                $urscholarId = 'URS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                $nextId++;

                $insertData[] = [
                    'scholarship_id' => $scholarship->id,
                    'batch_id' => $batch->id,
                    'urscholar_id' => $urscholarId, // Add the new urscholar_id field
                    'hei_name' => $record['HEI NAME'] ?? null,
                    'campus' => $record['CAMPUS'] ?? null,
                    'grant' => $record['GRANT'] ?? null,
                    'app_no' => $record['APP NO'] ?? null,
                    'award_no' => $record['AWARD NO.'] ?? null,
                    'last_name' => $record['LASTNAME'] ?? null,
                    'first_name' => $record['FIRSTNAME'] ?? null,
                    'extname' => $record['EXTNAME'] ?? null,
                    'middle_name' => $record['MIDDLENAME'] ?? null,
                    'sex' => $record['SEX'] ?? null,
                    'birthdate' => $record['BIRTHDATE'] ? date('Y-m-d', strtotime($record['BIRTHDATE'])) : null,
                    'course' => $record['COURSE/PROGRAM ENROLLED'] ?? null,
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

            // Bulk insert scholars
            Scholar::insert($insertData);

            // Get the newly inserted scholars for this batch
            $scholars = Scholar::where('batch_id', $batch->id)->get();

            // Verify scholars against existing students
            $students = Student::all();
            $matchedCount = 0;
            $unmatchedCount = 0;

            foreach ($scholars as $scholar) {
                $matched = $students->first(function ($student) use ($scholar) {
                    return $student->course === $scholar->course &&
                        $student->year_level == $scholar->year_level &&
                        $scholar->first_name == $student->first_name &&
                        $scholar->last_name == $student->last_name &&
                        $scholar->campus == $student->campus;
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
