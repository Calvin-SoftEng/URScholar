<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Scholar;
use App\Models\Student;
use App\Models\Batch;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ScholarController extends Controller
{
    public function scholars() {
        return Inertia::render('Super_Admin/Scholars/Scholars');
    }

    
    public function show(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        $scholars = Scholar::with('batch')->get();

        $batch = Batch::where('scholarship_id', $scholarship->id)->first();

        return Inertia::render('Super_Admin/Scholarships/Scholars', [
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

        return Inertia::render(
            'Super_Admin/Scholarships/AddingScholars',
            [
                'scholarship' => $scholarship,
                'scholars' => $scholars,
                'schoolyear' => $schoolyear,
                'selectedSem' => $selectedSem,
            ]
        );
    }


    public function upload(Request $request, Scholarship $scholarship, )
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

            $records = $csv->getRecords();
            $insertedCount = 0;

            $batch = Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => 1,
                'school_year' => $request->schoolyear,
                'semester' => $request->semester,
            ]);


            foreach ($records as $record) {
                $insertData[] = [
                    'scholarship_id' => $scholarship->id,
                    'hei_name' => $record['HEI NAME'] ?? null,
                    'campus' => $record['CAMPUS'] ?? null,
                    'grant' => $record['GRANT'] ?? null,
                    'batch_id' => $batch->id ?? null,
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
                ];

                $insertedCount++;
            }

            // dd($insertData);
            Scholar::insert($insertData);

            $scholars = Scholar::where('batch_id', $batch->id)->get();


            // Fetch students
            $students = Student::all();

            // Match logic without direct relationship
            $matchedScholars = [];
            $unmatchedScholars = [];

            foreach ($scholars as $scholar) {
                $matched = $students->first(function ($student) use ($scholar) {
                    return $student->course === $scholar->course && $student->year_level == $scholar->year_level && $scholar->first_name == $student->first_name
                        && $scholar->last_name == $student->last_name && $scholar->campus == $student->campus;
                });

                if ($matched) {
                    $scholar->update([
                        'status' => 'Verified',
                    ]);
                    $matchedScholars[] = $scholar;
                } else {
                    $scholar->update(['status' => 'Unverified']);
                    $unmatchedScholars[] = $scholar;
                }

            }


            $schoolyear = SchoolYear::where('id', $request->schoolyear)->first();



            $selectedSem = $request->semester;


            $matchedScholars = count($matchedScholars);
            $unmatchedScholars = count($unmatchedScholars);

            // return redirect()->route('scholarship.show', $scholarship->id)->with('success', "Successfully imported {$insertedCount} records. Matched students: " . count($matchedScholars) . ". Unmatched students: " . count($unmatchedScholars) . ".");


            // return redirect()->route('scholarship.show', [
            //     'scholarship' => $scholarship,
            //     'scholars' => $scholars,
            //     'schoolyear' => $schoolyear,
            //     'selectedSem' => $selectedSem,

            //     'success' => "Successfully imported {$insertedCount} records. Matched students: " . $matchedScholars . ". Unmatched students: " . $unmatchedScholars . "."
            // ]);

            return Inertia::render('Super_Admin/Scholarships/Scholarship', [
                'scholarship' => $scholarship,
                'scholars' => $scholars,
                'schoolyear' => $schoolyear,
                'selectedSem' => $selectedSem,
            ])->with([
                        'flash' => [
                            'success' => "Successfully imported {$insertedCount} records. Matched students: " . $matchedScholars . ". Unmatched students: " . $unmatchedScholars . "."
                        ]
                    ]);

            // return Inertia::render('Super_Admin/Scholarships/Scholarship', [
            //     'scholarship' => $scholarship,
            //     'scholars' => $scholars,
            //     'schoolyear' => $schoolyear,
            //     'selectedSem' => $selectedSem,
            // ])->with('flash', [
            //     'success' => "Successfully imported {$insertedCount} records. Matched students: {$matchedScholars}. Unmatched students: {$unmatchedScholars}."
            // ]);

            
            

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


        return Inertia::render('Super_Admin/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function expand_requirements()
    {
        return Inertia::render('Super_Admin/Scholarships/ScholarRequirements', [
        ]);
    }


    // public function index()
    // {
    //     // Assuming your model is named Requirement
    //     $requirements = Requirement::select('id', 'requirements_json')->get();

    //     return response()->json($requirements);
    // }
}
