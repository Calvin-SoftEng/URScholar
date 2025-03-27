<?php

namespace App\Http\Controllers\Staff;

use App\Models\Sponsor;
use App\Models\SubmittedRequirements;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Requirements;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
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

        return Inertia::render('Staff/Dashboard', [
            'sponsors' => $sponsor,
            'scholarships' => $activeScholarships,
            'scholars' => $latestSubmissions
        ]);
    }
}
