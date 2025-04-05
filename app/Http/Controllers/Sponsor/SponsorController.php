<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Payout;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function sponsor_dashboard()
    {
        //Scholarships Listing
        $sponsor = Sponsor::where('user_id', Auth::user()->id)
            ->first();

        $scholarship = $sponsor->scholarship;

        // Disbursement Listing
        $payout = Payout::whereIn('scholarship_id', $sponsor->scholarship->pluck('id'))
            ->with(['campus'])
            ->get();

        return Inertia::render('Sponsor/Dashboard', [
            'sponsor' => $sponsor,
            'scholarships' => $scholarship,
            'payouts' => $payout,  // Don't forget to pass this to your view
        ]);

    }

    public function index()
    {
        $sponsors = Sponsor::with('scholarship')->get();
        $campuses = Campus::all();
        $courses = Course::all();


        return inertia('Staff/Scholarships/Index', [
            'sponsors' => $sponsors,
            'campuses' => $campuses,
            'courses' => $courses,
        ]);
    }

    public function show(Sponsor $sponsor)
    {
        $scholarship = $sponsor->scholarship;

        $campuses = Campus::all();
        $courses = Course::all();

        return Inertia::render('Staff/Scholarships/CreateScholarships', [
            'sponsors' => $sponsor,
            'scholarships' => $scholarship,
            'campuses' => $campuses,
            'courses' => $courses,
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

    public function view_scholars()
    {

        return Inertia::render('Sponsor/Scholars/ScholarsTable');

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }

    public function scholar_description()
    {

        return Inertia::render('Sponsor/Scholars/Scholar_Scholarship-Details');

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }
}
