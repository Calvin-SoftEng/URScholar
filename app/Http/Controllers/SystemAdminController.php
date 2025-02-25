<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Course;
use App\Models\User;
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

    // campus ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

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

    public function assign_campus(Request $request, Campus $campus) {

        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'coor_id' => '',
            'cashier_id' => '',
        ]);

        $campus = Campus::findOrFail($request['campus_id']);
        
        $campus->update([
            'coordinator_id' => $request['coor_id'],
            'cashier_id' => $request['cashier_id']
        ]);

        return redirect()->back()
            ->with('message', 'Focal persons assigned successfully');
    }

    public function campuses() {

        $campuses = Campus::all();


        
        $coor = User::whereIn('usertype', ['coordinator'])
            ->select('id', 'name', 'usertype')
            ->orderBy('name')
            ->get();

        $cashier = User::whereIn('usertype', ['cashier'])
            ->select('id', 'name', 'usertype')
            ->orderBy('name')
            ->get();

            
        return Inertia::render('MIS/Univ_Settings/Campus', [
            'campuses' => Campus::with(['coordinator', 'cashier'])->get(),
            'coor' => $coor,
            'cashier' => $cashier,
        ]);
    }

    // course ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

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

    // users ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function roles() {
        return Inertia::render('MIS/User_Roles/Roles');
    }

    public function users() {
        return Inertia::render('MIS/User_Roles/Users');
    }
}
