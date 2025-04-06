<?php

namespace App\Http\Controllers\Staff;

use App\Events\GeneralNotification;
use App\Events\NewNotification;
use App\Models\Condition;
use App\Models\Course;
use App\Models\Eligibility;
use App\Models\Grantees;
use Inertia\Inertia;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\Campus;
use App\Models\CampusRecipients;
use App\Models\Criteria;
use App\Models\Disbursement;
use App\Models\Eligible;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\Requirements;
use App\Models\Payout;
use App\Models\Scholar;
use App\Models\ScholarshipForm;
use App\Models\ScholarshipFormData;
use App\Models\SubmittedRequirements;
use App\Models\Sponsor;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        return inertia('Staff/Scholarships/Index', ['scholarships' => $scholarships]);
    }

    public function scholarship(Sponsor $sponsors)
    {
        // Get the authenticated user's ID and campus_id
        $userId = Auth::user()->id;
        $userCampusId = Auth::user()->campus_id;

        // Get scholarships that either:
        // 1. Have batches matching the user's campus_id, OR
        // 2. Were created by the authenticated user
        $scholarships = Scholarship::with('requirements')
            ->where(function ($query) use ($userId, $userCampusId) {
                $query->whereHas('batches', function ($subQuery) use ($userCampusId) {
                    $subQuery->where('campus_id', $userCampusId);
                })
                    ->orWhere('user_id', $userId); // Add this condition to include scholarships created by the user
            })
            ->get();

        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();

        return inertia('Staff/Scholarships/ViewScholarships', [
            'sponsors' => $sponsors,
            'scholarships' => $scholarships,
            'schoolyears' => $schoolyear,
        ]);
    }

    public function batch(Request $request, $scholarshipId, $batchId)
    {
        // Checking if scholar's payment claimed
        $payout = 0;

        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year_id', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->orderBy('batch_no', 'desc')
            ->with(['scholars.campus', 'scholars.course', 'scholars.user']) // Added user relationship
            ->first();

        //                 // Use relationships to get applicants and their related scholars
        //     $applicants = $scholarship->applicants()
        //     ->where('batch_id', $batch->id)
        //     ->with('scholar.campus', 'scholar.course')
        //     ->get();

        // // Count scholars with complete submissions
        // $completeSubmissionsCount = 0;

        // // Process scholars data using the relationship
        // $scholars = $applicants->map(function ($applicant) use ($totalRequirements, &$completeSubmissionsCount , $request) {
        //     // Skip if there's no related scholar
        //     if (!$applicant->scholar) {
        //         return null;
        //     }

        //     $scholar = $applicant->scholar;
        $grantees = $scholarship->grantees()
            ->where('batch_id', $batch->id)
            ->with('scholar.campus', 'scholar.course')
            ->get();

        // Count scholars with complete submissions
        $completeSubmissionsCount = 0;

        $scholars = $grantees->map(function ($grantee) use ($totalRequirements, &$completeSubmissionsCount) {

            // Skip if there's no related scholar
            if (!$grantee->scholar) {
                return null;
            }

            $scholar = $grantee->scholar;

            $userPicture = User::where('id', $scholar->user_id)
                ->first();

            if (!$userPicture) {
                $userPicture = null;
            }


            // Get approved, returned, and total submitted requirements for this scholar
            $approvedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->where('status', 'Approved')
                ->count();

            $returnedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->where('status', 'Returned')
                ->count();

            $countRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->count();

            $userVerified = User::where('id', $scholar->user_id)->first();

            // Determine status
            $status = 'No submission';
            if ($totalRequirements > 0) {
                if ($countRequirements === 0) {
                    $status = 'No submission';
                } elseif ($approvedRequirements === $totalRequirements) {
                    $status = 'Complete';
                    $completeSubmissionsCount++; // Increment counter for complete submissions
                } elseif ($returnedRequirements > 0) {
                    $status = 'Returned';
                } elseif ($countRequirements > 0) {
                    $status = 'Submitted';
                } else {
                    $status = 'Incomplete';
                }
            }

            // Calculate progress percentage
            $progress = $totalRequirements > 0
                ? ($approvedRequirements / $totalRequirements) * 100
                : 0;

            if ($userVerified) {
                return [
                    'id' => $scholar->id,
                    'picture' => $userPicture,
                    'urscholar_id' => $scholar->urscholar_id,
                    'first_name' => $scholar->first_name,
                    'last_name' => $scholar->last_name,
                    'middle_name' => $scholar->middle_name,
                    'campus' => $scholar->campus->name ?? 'N/A', // Display campus name or N/A
                    'course' => $scholar->course->name ?? 'N/A', // Display course name or N/A
                    'year_level' => $scholar->year_level,
                    'grant' => $scholar->grant,
                    'status' => $status,
                    'submittedRequirements' => $approvedRequirements,
                    'totalRequirements' => $totalRequirements,
                    'progress' => $progress,
                    'user' => [
                        'picture' => $scholar->user->picture ?? null // Include user picture
                    ],
                    'userVerified' => $userVerified->email_verified_at,
                ];
            }
            else {
                return [
                    'id' => $scholar->id,
                    'picture' => $userPicture,
                    'urscholar_id' => $scholar->urscholar_id,
                    'first_name' => $scholar->first_name,
                    'last_name' => $scholar->last_name,
                    'middle_name' => $scholar->middle_name,
                    'campus' => $scholar->campus->name ?? 'N/A', // Display campus name or N/A
                    'course' => $scholar->course->name ?? 'N/A', // Display course name or N/A
                    'year_level' => $scholar->year_level,
                    'grant' => $scholar->grant,
                    'status' => $status,
                    'submittedRequirements' => $approvedRequirements,
                    'totalRequirements' => $totalRequirements,
                    'progress' => $progress,
                    'user' => [
                        'picture' => $scholar->user->picture ?? null // Include user picture
                    ],
                ];
            }


        });

        //Update the total scholars in the batch
        $batch->update([
            'read' => 1
        ]);


        return Inertia::render('Staff/Scholarships/Scholarship_Batch', [
            'scholarship' => $scholarship,
            'batches' => $batch,
            'scholars' => $scholars,
            'payout' => $payout,
            'requirements' => $requirements,
            'schoolyear' => $request->input('selectedYear')
                ? SchoolYear::find($request->input('selectedYear'))
                : null,
            'selectedSem' => $request->input('selectedSem', ''),
        ]);
    }

    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);


        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', $batch->campus_id)
            ->where('semester', $batch->semester)
            ->where('school_year_id', $batch->school_year_id)
            ->first();



        // Optimize query to reduce N+1 problem
        $disbursements = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->with([
                'scholar' => function ($subQuery) {
                    $subQuery->with(['course', 'campus']);
                }
            ])
            ->get();

        // Count total claimed disbursements
        $totalClaimed = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->where('status', 'claimed') // Assuming 'claimed' is the status for claimed disbursements
            ->count();

        return Inertia::render('Staff/Scholarships/Scholarship_Payrolls', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'disbursements' => $disbursements,
            'payout' => $payout,
            'totalClaimed' => $totalClaimed, // Pass the total claimed count to the view
        ]);
    }

    public function show(Request $request, Scholarship $scholarship)
    {
        $user = Auth::user();
        $userType = $user->usertype;

        $campuses = $userType == 'super_admin'
            ? Campus::all()
            : Campus::where('id', $user->campus_id)->get();

        if ($userType == 'coordinator') {
            if (!$request->has('selectedCampus')) {
                $request->merge(['selectedCampus' => $user->campus_id]);
            }

            if ($request->input('selectedCampus') != $user->campus_id) {
                return redirect()->back()->with('error', 'You can only view your assigned campus.');
            }
        }

        // Filtered batch for One-time Payment redirect
        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->first();

        if ($scholarship->scholarshipType == 'One-time Payment' && $batch) {
            return redirect()->route('scholarship.onetime_list', [
                'scholarshipId' => $scholarship->id,
                'selectedYear' => $request->input('selectedYear'),
                'selectedSem' => $request->input('selectedSem')
            ]);
        }

        // Total batches for selected campus
        $totalBatches = Batch::where('scholarship_id', $scholarship->id)
            ->where('campus_id', $request->input('selectedCampus'))
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->count();

        // Batches base query
        $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->with([
                'grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name'),
                'grantees.scholar.submittedRequirements'
            ]);

        // Filtered campuses
        $filteredCampuses = $userType == 'coordinator'
            ? $campuses->where('id', $user->campus_id)
            : ($request->input('selectedCampus')
                ? $campuses->where('id', $request->input('selectedCampus'))
                : $campuses);

        // Group batches by campus
        $batchesByCampus = [];
        foreach ($filteredCampuses as $campus) {
            $campusBatches = (clone $batchesQuery)
                ->where('campus_id', $campus->id)
                ->orderBy('batch_no')
                ->get();

            if ($campusBatches->isNotEmpty()) {
                $batchesByCampus[$campus->id] = [
                    'campus' => $campus,
                    'batches' => $campusBatches
                ];
            }
        }

        // All filtered batches
        $batches = $batchesQuery->orderBy('batch_no')->get();

        $schoolyear = $request->input('selectedYear')
            ? SchoolYear::find($request->input('selectedYear'))
            : null;

        $courses = Course::all();
        $students = Student::all();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // Grantees query
        $granteesQuery = $scholarship->grantees()
            ->whereIn('batch_id', $batches->pluck('id'))
            ->with([
                'scholar.campus',
                'scholar.course',
                'scholar.user',
                'scholar.submittedRequirements' => fn($q) => $q->orderBy('created_at', 'desc')
            ]);

        if ($userType == 'coordinator') {
            $granteesQuery->whereHas('scholar', fn($q) => $q->where('campus_id', $user->campus_id));
        } elseif ($request->input('selectedCampus')) {
            $granteesQuery->whereHas('scholar', fn($q) => $q->where('campus_id', $request->input('selectedCampus')));
        }

        $grantees = $granteesQuery->get();

        $total_scholars = $grantees->map(fn($grantee) => $grantee->scholar)->filter()->unique('id');

        // Update batch status + sub_total
        foreach ($batches as $batch) {
            $batchScholars = $batch->grantees->map(fn($grantee) => $grantee->scholar)->filter()->unique('id');

            $approvedScholarsQuery = Scholar::whereIn('id', $batchScholars->pluck('id'))
                ->whereHas('submittedRequirements', fn($q) => $q->where('status', 'Approved'))
                ->whereDoesntHave('submittedRequirements', fn($q) => $q->whereIn('status', ['Pending', 'Returned']));

            if ($userType == 'coordinator') {
                $approvedScholarsQuery->where('campus_id', $user->campus_id);
            } elseif ($request->input('selectedCampus')) {
                $approvedScholarsQuery->where('campus_id', $request->input('selectedCampus'));
            }

            $approvedCount = $approvedScholarsQuery->count();

            $hasCompletedRequirements = $batchScholars->contains(
                fn($scholar) =>
                $scholar->submittedRequirements->where('status', 'Approved')->isNotEmpty()
            );

            $newStatus = $batch->status;
            if ($batch->status == 'Pending' && $hasCompletedRequirements && $approvedCount > 0) {
                $newStatus = 'Active';
            }

            Batch::where('id', $batch->id)
                ->where('school_year_id', $request->input('selectedYear'))
                ->where('semester', $request->input('selectedSem'))
                ->update([
                    'sub_total' => $approvedCount,
                    'status' => $newStatus
                ]);
        }

        $completedBatches = Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->whereRaw('total_scholars = sub_total')
            ->count();

        $allBatches = Batch::where('scholarship_id', $scholarship->id)
            ->with(['grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name')])
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        $scholarship->update(['read' => 1]);

        event(new GeneralNotification(
            'Scholarship marked as read',
            'scholarship_read',
            ['scholarship_id' => $scholarship->id, 'read' => true]
        ));

        // Check if all batches for campus are inactive
        $selectedCampusBatches = $batches->filter(
            fn($batch) =>
            $batch->campus_id == $request->input('selectedCampus')
        );

        $allBatchesInactive = $selectedCampusBatches->isNotEmpty() &&
            $selectedCampusBatches->every(fn($batch) => $batch->status === 'Inactive');

        // Payouts by campus
        $payoutQuery = Payout::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->with('campus');

        if ($userType == 'coordinator') {
            $payoutQuery->where('campus_id', $user->campus_id);
        } elseif ($request->input('selectedCampus')) {
            $payoutQuery->where('campus_id', $request->input('selectedCampus'));
        }

        $payoutsByCampus = $payoutQuery->get()->groupBy('campus_id');

        $mainPayout = Payout::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->first();

        $payoutBatches = $batches->map(function ($batch) {
            return array_merge($batch->toArray(), [
                'claimed_count' => $batch->disbursement->where('status', 'Claimed')->count(),
                'not_claimed_count' => $batch->disbursement->whereIn('status', ['Pending', 'Not Claimed'])->count()
            ]);
        });

        $grantees = collect();
        if ($payoutBatches->isNotEmpty()) {
            $grantees = $scholarship->grantees()
                ->whereIn('batch_id', $payoutBatches->pluck('id'))
                ->with('scholar.campus', 'scholar.course')
                ->get();
        }

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'batchesByCampus' => $batchesByCampus,
            'allBatchesInactive' => $allBatchesInactive,
            'total_scholars' => $total_scholars,
            'payoutsByCampus' => $payoutsByCampus,
            'requirements' => $requirements,
            'grantees' => $grantees,
            'completedBatches' => $completedBatches,
            'totalBatches' => $totalBatches,
            'schoolyear' => $schoolyear,
            'selectedSem' => $request->input('selectedSem', ''),
            'selectedCampus' => $request->input('selectedCampus', ''),
            'campuses' => $campuses,
            'courses' => $courses,
            'students' => $students,
            'scholarship_form' => ScholarshipForm::find(2),
            'scholarship_form_data' => ScholarshipFormData::where('scholarship_form_id', 2)->get(),
            'eligibilities' => Eligibility::all(),
            'conditions' => Condition::all(),
            'userType' => $userType,
            'userCampusId' => $userType == 'coordinator' ? $user->campus_id : null,
            'allBatches' => $allBatches,
            'payouts' => $mainPayout,
            'payoutBatches' => $payoutBatches,
        ]);
    }


    public function forward_coor($scholarshipId, Request $request)
    {
        // Find the scholarship
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get parameters from the request
        $selectedSem = $request->input('selectedSem');
        $schoolYearId = $request->input('school_year');
        $selectedCampus = $request->input('selectedCampus');

        // Find all matching batches
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->where('semester', $selectedSem)
            ->where('school_year_id', $schoolYearId)
            ->where('campus_id', $selectedCampus)
            ->get();

        // Update each batch individually using the requested format
        foreach ($batches as $batch) {
            Batch::where('id', $batch->id)->update([
                'status' => 'Inactive'
            ]);
        }

        return redirect()->back()->with('success', 'Forwarded Successfully');
    }


    public function onetime_list(Request $request, $scholarshipId)
    {
        // Find the scholarship by ID
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get the batch for the selected semester and school year
        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('semester', $request->input('selectedSem'))
            ->where('school_year_id', $request->input('selectedYear'))
            ->firstOrFail();

        // Check if scholar's payment claimed
        $payout = Payout::where('scholarship_id', $scholarshipId)
            ->where('status', 'claimed')
            ->get();

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        // Use relationships to get applicants and their related scholars
        $applicants = $scholarship->applicants()
            ->where('batch_id', $batch->id)
            ->with('scholar.campus', 'scholar.course')
            ->get();

        // Count scholars with complete submissions
        $completeSubmissionsCount = 0;

        // Process scholars data using the relationship
        $scholars = $applicants->map(function ($applicant) use ($totalRequirements, &$completeSubmissionsCount, $request) {
            // Skip if there's no related scholar
            if (!$applicant->scholar) {
                return null;
            }

            $scholar = $applicant->scholar;

            // Get the scholar's grade for the selected semester and school year
            $grade = Grade::where('scholar_id', $scholar->id)
                ->where('semester', $request->input('selectedSem'))
                ->where('school_year_id', $request->input('selectedYear'))
                ->first();

            $userPicture = User::where('id', $scholar->user_id)
                ->first();

            $approvedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->where('status', 'Approved')
                ->count();

            $returnedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->where('status', 'Returned')
                ->count();

            $countRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->count();

            // Determine status
            $status = 'No submission';
            if ($totalRequirements > 0) {
                if ($countRequirements === 0) {
                    $status = 'No submission';
                } elseif ($approvedRequirements === $totalRequirements) {
                    $status = 'Complete';
                    $completeSubmissionsCount++; // Increment counter for complete submissions
                } elseif ($returnedRequirements > 0) {
                    $status = 'Returned';
                } elseif ($countRequirements > 0) {
                    $status = 'Submitted';
                } else {
                    $status = 'Incomplete';
                }
            }

            // Calculate progress percentage
            $progress = $totalRequirements > 0
                ? ($approvedRequirements / $totalRequirements) * 100
                : 0;

            return [
                'id' => $scholar->id,
                'picture' => $userPicture->picture,
                'urscholar_id' => $scholar->urscholar_id,
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'middle_name' => $scholar->middle_name,
                'campus' => $scholar->campus->name ?? 'N/A',
                'course' => $scholar->course->name ?? 'N/A',
                'year_level' => $scholar->year_level,
                'grant' => $scholar->grant,
                'status' => $status,
                'submittedRequirements' => $approvedRequirements,
                'totalRequirements' => $totalRequirements,
                'progress' => $progress,
                'grade' => $grade ? $grade->grade : null,
                'cog' => $grade ? $grade->cog : null,
                'grade_path' => $grade ? $grade->path : null,
                'date_applied' => $applicant->created_at,
            ];
        })->filter(); // Remove any null values


        // Add this to fetch campus recipient data
        $campusRecipients = CampusRecipients::where('scholarship_id', $scholarshipId)
            ->get();

        // Calculate total slots across all campuses
        $totalSlots = $campusRecipients->sum('slots');

        return Inertia::render('Staff/Scholarships/One-Time/OneTime_Applicants', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'applicants' => $applicants,
            'scholars' => $scholars,
            'payout' => $payout,
            'requirements' => $requirements,
            'schoolyear' => $request->input('selectedYear')
                ? SchoolYear::find($request->input('selectedYear'))
                : null,
            'selectedSem' => $request->input('selectedSem', ''),
            'campusRecipients' => $campusRecipients,
            'totalSlots' => $totalSlots,
        ]);
    }


    public function store(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'sponsor_id' => 'required|int',
            'name' => 'required|string|max:255',
            'scholarshipType' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        Scholarship::create([
            'name' => $request->name,
            'sponsor_id' => $request->sponsor_id,
            'user_id' => Auth::user()->id,
            'scholarshipType' => $request->scholarshipType,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Scholarship created ' . $request['name'],
        ]);

        return redirect()->route('sponsor.index')->with('success', 'Check out view scholarships');
    }


    public function one_time(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'total_recipients' => 'required|integer|min:1',
            'campus_recipients' => 'required|array',
            'campus_recipients.*.campus_id' => 'required|exists:campuses,id',
            'campus_recipients.*.slots' => 'required|integer|min:1',
            'campus_recipients.*.remaining_slots' => 'required|integer|min:0',
            'campus_recipients.*.selected_campus' => 'required|json',
            'application' => 'required|date',
            'deadline' => 'required|date',
            'requirements' => 'required|array',
            'grade' => 'required|numeric|min:0|max:100',
            'criteria' => 'required|array',
            'criteria.*' => 'exists:scholarship_form_data,id',
            'conditions' => 'required|array',
            'conditions.*' => 'exists:conditions,id',
            'semester' => 'required',
            'school_year' => 'required',
        ]);

        $total_recipients = $validated['total_recipients'];
        $campus_recipients = $validated['campus_recipients'];

        // Calculate total remaining slots
        $total_remaining_slots = array_sum(array_column($campus_recipients, 'remaining_slots'));

        // Check if the total recipients don't match the sum of remaining slots
        if ($total_recipients != $total_remaining_slots) {
            dd('need to maximize slots');
        }

        foreach ($campus_recipients as $campusRecipient) {
            // Check if record exists
            $existingRecord = CampusRecipients::where('scholarship_id', $scholarship->id)
                ->where('campus_id', $campusRecipient['campus_id'])
                ->first();

            if ($existingRecord) {
                // Update existing record
                $existingRecord->update([
                    'selected_campus' => $campusRecipient['selected_campus'],
                    'slots' => $campusRecipient['slots'],
                    'remaining_slots' => max(0, $campusRecipient['remaining_slots'] - $campusRecipient['slots']),
                ]);
            } else {
                // Insert new record
                CampusRecipients::create([
                    'scholarship_id' => $scholarship->id,
                    'campus_id' => $campusRecipient['campus_id'],
                    'selected_campus' => $campusRecipient['selected_campus'],
                    'slots' => $campusRecipient['slots'],
                    'remaining_slots' => max(0, $campusRecipient['remaining_slots'] - $campusRecipient['slots']),
                ]);
            }
        }

        // For requirements
        foreach ($validated['requirements'] as $requirement) {
            // Check if record exists
            $existingRequirement = Requirements::where('scholarship_id', $scholarship->id)
                ->where('requirements', $requirement)
                ->first();

            if ($existingRequirement) {
                // Update existing record
                $existingRequirement->update([
                    'date_start' => $validated['application'],
                    'date_end' => $validated['deadline'],
                    'total_scholars' => $total_recipients,
                ]);
            } else {
                // Insert new record
                Requirements::create([
                    'scholarship_id' => $scholarship->id,
                    'requirements' => $requirement,
                    'date_start' => $validated['application'],
                    'date_end' => $validated['deadline'],
                    'total_scholars' => $total_recipients,
                ]);
            }
        }

        // For criteria
        foreach ($validated['criteria'] as $scholarship_form_data_id) {
            // Check if record exists
            $existingCriteria = Criteria::where('scholarship_id', $scholarship->id)
                ->where('scholarship_form_data_id', $scholarship_form_data_id)
                ->first();

            if ($existingCriteria) {
                // Update existing record
                $existingCriteria->update([
                    'grade' => $request->grade,
                ]);
            } else {
                // Create new record
                Criteria::create([
                    'scholarship_id' => $scholarship->id,
                    'scholarship_form_data_id' => $scholarship_form_data_id,
                    'grade' => $request->grade,
                ]);
            }
        }

        // For eligible
        foreach ($validated['conditions'] as $condition_id) {
            // Check if record exists
            $existingEligible = Eligible::where('scholarship_id', $scholarship->id)
                ->where('condition_id', $condition_id)
                ->first();

            if ($existingEligible) {
                // Update existing record
                $existingEligible->update([
                    'condition_id' => $condition_id,
                ]);
            } else {
                // Create new record
                Eligible::create([
                    'scholarship_id' => $scholarship->id,
                    'condition_id' => $condition_id,
                ]);
            }

        }

        $batch = Batch::where('scholarship_id', $scholarship->id)->first();

        if (!$batch) {
            Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => '1',
                'campus_id' => 2,
                'school_year_id' => $request->school_year,
                'semester' => $request->semester,
            ]);
        }

        //Update Scholarship Status
        $scholarship->update([
            'status' => 'Active'
        ]);


        return back()->with('success', 'Scholarship recipients and requirements saved successfully');
    }

    public function onetime_scholars()
    {
        // $scholars = $scholarship->scholars;

        // $requirements = Requirements::where('scholarship_id', $scholarship->id)->first();


        return Inertia::render('Staff/Scholarships/One-Time/One-TimeScholars', [
            // 'scholarship' => $scholarship,
            // 'scholars' => $scholars,
            // 'requirements' => $requirements,
        ]);
    }

    public function send(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->first();


        return Inertia::render('Staff/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
            'requirements' => $requirements,
        ]);
    }

    public function forward(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|integer',
            'grantees' => 'required|array',
            'batch_ids' => 'required|array',
            'batch_ids.*' => 'integer',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'school_year_id' => 'required',
            'semester' => 'required',
        ], [
            'date_start.required' => 'Set a Date start',
            'date_end.required' => 'Set a Date end',
        ]);

        $scholarshipId = $request->input('scholarship_id');
        $grantees = $request->input('grantees');
        $batchIds = $request->input('batch_ids');
        $user = Auth::user();

        // Group grantees by campus
        $granteesByCampus = [];
        foreach ($grantees as $grantee) {
            // Get the batch to find campus_id
            $batch = Batch::find($grantee['batch_id']);

            if (!$batch) {
                continue;
            }

            $campusId = $batch->campus_id;

            if (!isset($granteesByCampus[$campusId])) {
                $granteesByCampus[$campusId] = [];
            }

            $granteesByCampus[$campusId][] = $grantee;
        }

        // Create payouts for each campus and process disbursements
        $createdPayouts = [];

        foreach ($granteesByCampus as $campusId => $campusGrantees) {
            // Create payout for this campus
            $payout = Payout::create([
                'scholarship_id' => $scholarshipId,
                'campus_id' => $campusId,
                'date_start' => $request->input('date_start'),
                'date_end' => $request->input('date_end'),
                'school_year_id' => $request->input('school_year_id'),
                'semester' => $request->input('semester'),
                'status' => 'Pending',
            ]);

            // Prepare disbursement data for this campus
            $dataToInsert = [];
            foreach ($campusGrantees as $grantee) {
                $dataToInsert[] = [
                    'payout_id' => $payout->id,
                    'batch_id' => $grantee['batch_id'],
                    'scholar_id' => $grantee['scholar']['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert disbursements
            Disbursement::insert($dataToInsert);

            // Update payout with total scholars count
            $totalDisbursement = count($dataToInsert);

            // Calculate sub_total based on scholarship amount if available
            $subTotal = null;
            $scholarship = Scholarship::find($scholarshipId);
            if ($scholarship && isset($scholarship->amount)) {
                $subTotal = $scholarship->amount * $totalDisbursement;
            }

            $payout->update([
                'total_scholars' => $totalDisbursement,
                'sub_total' => $subTotal
            ]);

            $createdPayouts[] = $payout;
        }

        // Create Activity Log
        $activityLog = ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Forward',
            'description' => 'Scholars forwarded to cashiers by campus',
        ]);

        // Get users to notify (similar to original function)
        $users = User::whereIn('id', function ($query) use ($scholarshipId) {
            $query->select('user_id')
                ->from('scholarship_groups')
                ->where('scholarship_id', $scholarshipId);
        })
            ->where('id', '!=', Auth::user()->id)
            ->get();

        // Create Notification
        $notification = Notification::create([
            'title' => 'New Payouts Forwarded',
            'message' => 'Scholars forwarded to cashiers by campus by ' . $user->name,
            'type' => 'payout_forward',
        ]);

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for new notification
        event(new NewNotification($notification));

        // return response()->json([
        //     'message' => 'Scholars successfully assigned to payouts by campus!',
        //     'payouts' => $createdPayouts,
        //     'total_payouts' => count($createdPayouts),
        // ], 201);
    }

    // Add a method to get payouts by campus for a specific scholarship
    public function getPayoutsByCampus($scholarshipId)
    {
        $payouts = Payout::where('scholarship_id', $scholarshipId)
            ->with(['campus:id,name', 'disbursements.scholar'])
            ->get()
            ->groupBy('campus_id');

        $result = [];
        foreach ($payouts as $campusId => $campusPayouts) {
            $campus = Campus::find($campusId);
            if (!$campus) {
                continue;
            }

            $result[] = [
                'campus_id' => $campusId,
                'campus_name' => $campus->name,
                'payouts' => $campusPayouts,
                'total_scholars' => $campusPayouts->sum('total_scholars'),
                'total_amount' => $campusPayouts->sum('sub_total'),
            ];
        }

        return response()->json([
            'payouts_by_campus' => $result
        ]);
    }

    // Add a method to get payout details by campus
    public function getPayoutDetails($payoutId)
    {
        $payout = Payout::with([
            'campus:id,name',
            'scholarship:id,name,amount',
            'disbursements.scholar.user:id,name',
            'disbursements.batch:id,batch_no,semester'
        ])->findOrFail($payoutId);

        return response()->json([
            'payout' => $payout
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // dd($request);

        $scholarship = Scholarship::findOrFail($id);
        $scholarship->update($request->all());

        return redirect()->route('scholarships.index');
    }
    // public function destroy($id)
    // {
    //     try {
    //         $scholarship = Scholarship::findOrFail($id); // Find the scholarship by ID
    //         $scholarship->delete(); // Delete the scholarship

    //         return response()->json(['message' => 'Scholarship deleted successfully']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Failed to delete scholarship', 'details' => $e->getMessage()], 500);
    //     }
    // }

    public function requirementsTab(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Staff/Scholarships/ScholarshipTabs/Requirements', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function downloadBatchReport(Scholarship $scholarship, Batch $batch)
    {
        $scholars = $batch->scholars;

        $pdf = PDF::loadView('reports.scholarship-report', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars
        ]);

        return $pdf->stream("scholarship-report-batch-{$batch->batch_no}.pdf");
    }

    public function scholar_scholarship_details()
    {
        return Inertia::render('Staff/Scholarships/Scholar_Scholarship-Details');
    }
}


