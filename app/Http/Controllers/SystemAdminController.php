<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SystemAdminController extends Controller
{
    public function dashboard() {
        return Inertia::render('MIS/Dashboard');
    }

    // univ settings
    public function course() {
        $campuses = Campus::all();

        return Inertia::render('MIS/Univ_Settings/Course', [
            'campuses' => $campuses,
        ]);
    }

    public function store_campus(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
        ]);


        Campus::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('mis.campuses');
    }

    public function campuses() {

        $campuses = Campus::all();

        return Inertia::render('MIS/Univ_Settings/Campus', [
            'campuses' => $campuses,
        ]);
    }

    public function course_config(Campus $campuses) {

        return Inertia::render('MIS/Univ_Settings/CourseConfig', [
            'campuses' => $campuses,
        ]);
    }

    public function sy_term() {
        return Inertia::render('MIS/Univ_Settings/SY_Term');
    }
}
