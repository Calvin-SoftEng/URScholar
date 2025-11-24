<?php

namespace App\Http\Controllers;

use App\Models\CampusRecipients;
use App\Models\Criteria;
use App\Models\PortalBranding;
use App\Models\Requirements;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Scholarship;
use App\Models\Sponsor;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class LandingPageController extends Controller
{

    public function test(): Response
    {

        return Inertia::render('Predict', []);
    }

    public function predict(Request $request)
    {
        $inputData = [
            (int) $request->age,
            (int) $request->salary
        ];

        $process = new Process([
            base_path('venv/Scripts/python.exe'),
            base_path('ml/ml_model.py'),
            json_encode($inputData)
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        return response()->json(json_decode($process->getOutput(), true));
    }

    public function index(): Response
    {
        $scholarships = Scholarship::where('scholarshipType', 'One-time Payment')->get();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();

        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => App::version(),
            'phpVersion' => PHP_VERSION,
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'schoolyears' => $schoolyear,
            'branding' => $branding,
        ]);
    }

    public function scholarship_apply_details(Scholarship $scholarship): Response
    {

        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $deadline = Requirements::where('scholarship_id', $scholarship->id)->first();

        $selectedCampus = CampusRecipients::where('scholarship_id', $scholarship->id)->first();

        $criteria = Criteria::where('scholarship_id', $scholarship->id)->with('scholarshipFormData')->get();
        $grade = Criteria::where('scholarship_id', $scholarship->id)->first();
        $branding = PortalBranding::where('status', 'Active')->first();

        return Inertia::render('ScholarshipApplyDetails', [
            'scholarship' => $scholarship,
            'sponsor' => $sponsor,
            'requirements' => $requirements,
            'deadline' => $deadline,
            'selectedCampus' => $selectedCampus,
            'criterias' => $criteria,
            'grade' => $grade,
            'branding' => $branding,
        ]);
    }
}
