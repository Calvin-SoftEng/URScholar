<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Course;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SystemAdminController extends Controller
{
    public function dashboard() {
        return Inertia::render('MIS/Dashboard');
    }

    // univ settings
    public function course() {
        $campuses = Campus::with('courses')->get();

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

        $course = $campuses->courses;

        return Inertia::render('MIS/Univ_Settings/CourseConfig', [
            'campuses' => $campuses,
            'courses' => $course,
        ]);
    }

    public function store_course_config(Request $request) {

        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
        ]);

        Course::create([
            'campus_id' => $request->id,
            'name' => $request->name,
        ]);

        return redirect()->route('mis.course_config');
    }

    public function sy_term() {
        return Inertia::render('MIS/Univ_Settings/SY_Term');
    }
}
