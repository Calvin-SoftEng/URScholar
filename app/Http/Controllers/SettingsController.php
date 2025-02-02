<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Sponsor;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{

    public function index()
    {

        $sponsors = Sponsor::all();
        
        return Inertia::render('Super_Admin/Settings/Settings_Sponsor', 
            ['sponsors' => $sponsors]
        );
    }

    public function adding()
    {

        $students = Student::all();
        return Inertia::render('Super_Admin/Settings/Adding_Students', 
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

        // Proceed with file processing
        try {
            $data = array_map('str_getcsv', file($file->getRealPath()));
            $header = array_shift($data);
            $requiredHeaders = ['first_name', 'last_name', 'email', 'course', 'campus', 'year_level'];

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
                    'campus' => $rowData['campus'],
                    'year_level' => $rowData['year_level'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // dd($insertData);
            Student::insert($insertData);

            return redirect()->back()->with('success', 'Scholars added to the scholarship!');
        } catch (\Exception $e) {
            \Log::error('Error during file upload: ' . $e->getMessage());
            return response()->json(['message' => 'Error during file upload'], 500);
        }
    }
}
