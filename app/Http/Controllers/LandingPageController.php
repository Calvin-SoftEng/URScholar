<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Scholarship;
use App\Models\Sponsor;
use App\Models\SchoolYear;

class LandingPageController extends Controller
{
    public function index(): Response
    {
        $scholarships = Scholarship::where('scholarshipType', 'One-time Payment')->get();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => App::version(),
            'phpVersion' => PHP_VERSION,
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'schoolyears' => $schoolyear,
        ]);
    }
    public function scholarship_apply_details()
    {
        return Inertia::render('ScholarshipApplyDetails');
    }
}
