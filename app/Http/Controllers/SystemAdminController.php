<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class SystemAdminController extends Controller
{
    public function dashboard() {
        return Inertia::render('MIS/Dashboard');
    }

    // univ settings
    public function course() {
        return Inertia::render('MIS/Univ_Settings/Course');
    }

    public function campuses() {
        return Inertia::render('MIS/Univ_Settings/Campus');
    }

    public function course_config() {
        return Inertia::render('MIS/Univ_Settings/CourseConfig');
    }

    public function sy_term() {
        return Inertia::render('MIS/Univ_Settings/SY_Term');
    }
}
