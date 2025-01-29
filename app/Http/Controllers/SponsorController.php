<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        
        return inertia('Super_Admin/Scholarships/Index', ['sponsors' => $sponsors]);
    }

    public function show(Sponsor $sponsor)
    {
        $scholarship = $sponsor->scholarship;

        return Inertia::render('Super_Admin/Scholarships/CreateScholarships', [
            'sponsors' => $sponsor,
            'scholarships' => $scholarship,
        ]);
    }

    public function create(Sponsor $sponsor)
    {

        return Inertia::render('Super_Admin/Scholarships/CreateSponsor', [
            'sponsor' => $sponsor,
        ]);

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'file' => 'required|file',
        //     'description' => 'required|string',
        //     'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        //     'imgName' => 'required|string',
        // ]);

        // dd($request->all());
        
        // $logoFile = $request->file('img');
        // $logoFileName = $request->imgName;
        // // $logoPath = $request->file('img')->store('sponsor/logo', 'public');

        // $logoPath = Storage::disk('public')->putFileAs('sponsor/logo', $logoFile, $logoFileName);

        // $filePath = $request->file('file')->store('sponsor/moa', 'public');
        

        // // $fileName = time() . '_' . $request->file('img')->getClientOriginalName();

        // Sponsor::create([
        //     'name' => $request->name,
        //     'moa_file' => $filePath,
        //     'description' => $request->description,
        //     'logo' => $logoFileName,
        //     // 'logo' => $fileName,
        // ]);

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
}
