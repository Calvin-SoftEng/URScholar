<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function scholarship_apply_details()
    {
        return Inertia::render('ScholarshipApplyDetails');
    }
}
