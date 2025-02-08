<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function index()
    {
        $sponsors = Sponsor::all();
        
        return inertia('Staff/Scholarships/Index', ['sponsors' => $sponsors]);
    }

    public function show(Sponsor $sponsor)
    {
        $scholarship = $sponsor->scholarship;

        return Inertia::render('Staff/Scholarships/CreateScholarships', [
            'sponsors' => $sponsor,
            'scholarships' => $scholarship,
        ]);
    }

    public function create(Sponsor $sponsor)
    {

        return Inertia::render('Staff/Scholarships/CreateSponsor', [
            'sponsor' => $sponsor,
        ]);

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }

    public function store(Request $request)
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
}
