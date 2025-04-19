<?php

namespace App\Http\Controllers\Staff;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\Sponsor;
use App\Models\Student;
use App\Models\SubmittedRequirements;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Grantees;
use App\Models\Requirements;
use App\Models\Scholarship;
use App\Models\Scholar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        if (Auth::check() && Auth::user()->email_verified_at === null) {
            return redirect()->route('staff.verify_account');
        }

        $sponsor = Sponsor::all();
        $activeScholarships = Scholarship::where('status', 'active')->get();

        // Get the latest 5 submitted requirements
        $latestSubmissions = SubmittedRequirements::with(['scholar', 'requirement.scholarship'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($submission) {
                $scholar = $submission->scholar;
                $requirement = $submission->requirement;
                $scholarship = $requirement->scholarship;

                // Get requirements info for need-based scholarships
                $totalRequirements = Requirements::where('scholarship_id', $scholarship->id)->count();
                $approvedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                    ->whereHas('requirement', function ($query) use ($scholarship) {
                        $query->where('scholarship_id', $scholarship->id);
                    })
                    ->where('status', 'Approved')
                    ->count();

                // Calculate progress percentage
                $progress = $totalRequirements > 0
                    ? ($approvedRequirements / $totalRequirements) * 100
                    : 0;

                return [
                    'id' => $scholar->id,
                    'urscholar_id' => $scholar->urscholar_id,
                    'first_name' => $scholar->first_name,
                    'last_name' => $scholar->last_name,
                    'campus' => $scholar->campus->name ?? 'N/A', // Display campus name or N/A
                    'course' => $scholar->course->name ?? 'N/A', // Display course name or N/A
                    'scholarship_id' => $scholarship->id,
                    'scholarship_name' => $scholarship->name,
                    'scholarshipType' => $scholarship->scholarshipType, // 'one-time' or 'need-based'
                    'status' => $submission->status, // 'Verified', 'Pending', or 'Rejected'
                    'submittedRequirements' => $approvedRequirements,
                    'totalRequirements' => $totalRequirements,
                    'progress' => $progress,
                    'submission_date' => $submission->created_at,
                    'remarks' => $submission->remarks
                ];
            });


            $enrolled = Student::all();

            $activity_logs = ActivityLog::where('user_id', Auth::user()->id)->get();

            $academic_year = AcademicYear::where('status', 'Active')
            ->with('school_year')
            ->first();

            $active_scholars = Grantees::where('status', 'Accomplished')
            ->where('semester', $academic_year->semester)
            ->where('school_year_id', $academic_year->school_year_id)
            ->get();

            $univ_students = Student::where('academic_year_id', $academic_year->id)
            ->where('campus_id', Auth::user()->campus_id)
            ->count();

        return Inertia::render('Staff/Dashboard', [
            'sponsors' => $sponsor,
            'scholarships' => $activeScholarships,
            'scholars' => $latestSubmissions,
            'active_scholars' => $active_scholars,
            'activity_logs' => $activity_logs,
            'academic_year' => $academic_year,
            'univ_students' => $univ_students,
        ]);
    }

    public function verify_account()
    {
        // if (Auth::user()->email_verified_at !== null) {
        //     return redirect()->route('staff.dashboard');
        // }

        return Inertia::render('Staff/Account/Verification', [

        ]);
    }
}
