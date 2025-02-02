<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Scholar;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class ScholarController extends Controller
{
    public function show(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Super_Admin/Scholarships/Scholars', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    // public function upload(Request $request, Scholarship $scholarship)
    // {
    //     $file = $request->file('file');

    //     dd($file);

    //     $data = array_map('str_getcsv', file($file->getRealPath()));
    //     $header = array_shift($data);
    //     $requiredHeaders = ['first_name', 'last_name', 'email', 'course'];

    //     if (array_diff($requiredHeaders, $header)) {
    //         return response()->json(['message' => 'Invalid CSV format'], 422);
    //     }

    //     $insertData = [];
    //     foreach ($data as $row) {
    //         $rowData = array_combine($header, $row);
    //         $insertData[] = [
    //             'first_name' => $rowData['first_name'],
    //             'last_name' => $rowData['last_name'],
    //             'email' => $rowData['email'],
    //             'course' => $rowData['course'],
    //             'scholarship_id' => $scholarship->id,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ];
    //     }

    //     Scholar::insert($insertData);

    //     // return response()->json(['message' => 'Scholars uploaded successfully!']);
    //     return redirect()->back()->with('success', 'Scholars added to the scholarship!');
    // }

    public function upload(Request $request, Scholarship $scholarship)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048'
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

            foreach ($records as $record) {
                $insertData[] = [
                    'scholarship_id' => $scholarship->id,
                    'hei_name' => $record['HEI NAME'] ?? null,
                    'campus' => $record['CAMPUS'] ?? null,
                    'grant' => $record['GRANT'] ?? null,
                    'batch_no' => $record['BATCH NO.'] ?? null,
                    'app_no' => $record['APP NO'] ?? null,
                    'award_no' => $record['AWARD NO.'] ?? null,
                    'lastname' => $record['LASTNAME'] ?? null,
                    'firstname' => $record['FIRSTNAME'] ?? null,
                    'extname' => $record['EXTNAME'] ?? null,
                    'middlename' => $record['MIDDLENAME'] ?? null,
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


            dd($insertData);
            
            return response()->json([
                'message' => "Successfully imported {$insertedCount} records",
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing CSV file: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    // public function upload(Request $request, Scholarship $scholarship)
    // {
    //     // Check if file exists in the request
    //     $file = $request->file('file');


    //     \Log::info('Request data:', ['files' => $request->all()]);

        
    //     if (!$file) {
    //         return response()->json(['message' => 'No file uploaded'], 400);
    //     }

    //     // Proceed with file processing
    //     try {
    //         $data = array_map('str_getcsv', file($file->getRealPath()));
    //         $header = array_shift($data);
    //         $requiredHeaders = ['HEI NAME', 'CAMPUS', 'GRANT', 'BATCH NO.', 'APP NO', 'AWARD NO.' , 'LASTNAME', 'FIRSTNAME', 'EXTNAME' ,'MIDDLENAME', 'SEX', 'BIRTHDATE', 'COURSE/PROGRAM ENROLLED', 'YEAR LEVEL', 'TOTAL UNITS ENROLLED', 'STREET', 'MUNICIPALITY', 'PROVINCE', 'CLASSIFICATION OF PWD'];

    //         if (array_diff($requiredHeaders, $header)) {
    //             return response()->json(['message' => 'Invalid CSV format'], 422);
    //         }

    //         $insertData = [];
    //         foreach ($data as $row) {
    //             $rowData = array_combine($header, $row);
    //             $insertData[] = [
    //                 'first_name' => $rowData['first_name'],
    //                 'last_name' => $rowData['last_name'],
    //                 'email' => $rowData['email'],
    //                 'course' => $rowData['course'],
    //                 'scholarship_id' => $scholarship->id,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }

    //         dd($insertData);
    //         Scholar::insert($insertData);

    //         return redirect()->back()->with('success', 'Scholars added to the scholarship!');
    //     } catch (\Exception $e) {
    //         \Log::error('Error during file upload: ' . $e->getMessage());
    //         return response()->json(['message' => 'Error during file upload'], 500);
    //     }
    // }


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
