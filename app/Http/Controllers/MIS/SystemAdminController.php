<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
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


    // system config ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function portal_branding() {
        return Inertia::render('MIS/System_Config/Portal_Branding');
    }

    // univ settings ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function courses() {
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

        return redirect()->route('sa.campuses');
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

        return redirect()->route('sa.course_config');
    }

    public function sy_and_term() {
        return Inertia::render('MIS/Univ_Settings/SY_Term');
    }

    // users ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // public function user_roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

    // public function roles() {
    //     return Inertia::render('MIS/User_Roles/Roles');
    // }

    public function system_user_roles() {
        return Inertia::render('MIS/User_Roles/Roles');
    }

    public function system_users() {
        return Inertia::render('MIS/User_Roles/Users');
    }

    public function activity_logs() {
        return Inertia::render('MIS/User_Roles/Activity_Logs');
    }
    
}
