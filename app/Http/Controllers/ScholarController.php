<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Scholar;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

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
        // Check if file exists in the request
        $file = $request->file('file');

        \Log::info('Request data:', ['files' => $request->all()]);

        
        if (!$file) {
            return response()->json(['message' => 'No file uploaded'], 400);
        }

        // Proceed with file processing
        try {
            $data = array_map('str_getcsv', file($file->getRealPath()));
            $header = array_shift($data);
            $requiredHeaders = ['first_name', 'last_name', 'email', 'course'];

            if (array_diff($requiredHeaders, $header)) {
                return response()->json(['message' => 'Invalid CSV format'], 422);
            }

            $insertData = [];
            foreach ($data as $row) {
                $rowData = array_combine($header, $row);
                $insertData[] = [
                    'first_name' => $rowData['first_name'],
                    'last_name' => $rowData['last_name'],
                    'email' => $rowData['email'],
                    'course' => $rowData['course'],
                    'scholarship_id' => $scholarship->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Scholar::insert($insertData);

            return redirect()->back()->with('success', 'Scholars added to the scholarship!');
        } catch (\Exception $e) {
            \Log::error('Error during file upload: ' . $e->getMessage());
            return response()->json(['message' => 'Error during file upload'], 500);
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
