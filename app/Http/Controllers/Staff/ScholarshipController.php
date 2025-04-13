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
use App\Models\AcademicYear;
use App\Models\CampusRecipients;
use App\Models\Criteria;
use App\Models\Disbursement;
use App\Models\Eligible;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\ApplicantTrack;
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
        // 2. Have applicant_tracks matching the user's campus_id, OR
        // 3. Were created by the authenticated user
        $scholarships = Scholarship::with('requirements')
            ->where(function ($query) use ($userId, $userCampusId) {
                $query->whereHas('batches', function ($subQuery) use ($userCampusId) {
                    $subQuery->where('campus_id', $userCampusId);
                })
                    ->orWhereHas('applicant_tracks', function ($subQuery) use ($userCampusId) {
                        $subQuery->where('campus_id', $userCampusId);
                    })
                    ->orWhere('user_id', $userId); // Add this condition to include scholarships created by the user
            })
            ->get();

        $sponsors = Sponsor::all();
        $school_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        return inertia('Staff/Scholarships/ViewScholarships', [
            'sponsors' => $sponsors,
            'scholarships' => $scholarships,
            'schoolyears' => $school_year,
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
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->first();

        // Check if all payouts are inactive
        $totalRequirements = $requirements->count();

        $grantees = null;
        if ($totalRequirements) {
            $grantees = $scholarship->grantees()
                ->whereIn('status', ['Active', 'Pending', 'Accomplished'])
                ->where('batch_id', $batch->id)
                ->where('semester', $request->input('selectedSem')) // Add this line
                ->with('scholar.campus', 'scholar.course')
                ->get();

        } else {
            $grantees = $scholarship->grantees()
                ->where('batch_id', $batch->id)
                ->where('semester', $request->input('selectedSem')) // Add this line
                ->with('scholar.campus', 'scholar.course')
                ->get();
        }


        $totalScholars = $scholarship->grantees()
            ->where('status', 'Active')
            ->where('semester', $request->input('selectedSem')) // Add this line
            ->where('batch_id', $batch->id)
            ->with('scholar.campus', 'scholar.course')
            ->count();

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
                    'scholar_status' => $scholar->status,
                    'student_status' => $grantee->student_status,
                    'status' => $status,
                    'submittedRequirements' => $approvedRequirements,
                    'totalRequirements' => $totalRequirements,
                    'progress' => $progress,
                    'user' => [
                        'picture' => $scholar->user->picture ?? null // Include user picture
                    ],
                    'userVerified' => $userVerified->email_verified_at,
                ];
            } else {
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
                    'scholar_status' => $scholar->status,
                    'student_status' => $grantee->student_status,
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
            'totalScholars' => $totalScholars,
        ]);
    }

    public function student_payouts(Request $request, $scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);


        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->orderBy('batch_no', 'desc')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', $batch->campus_id)
            ->where('semester', $request->input('selectedSem', ''))
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
        $messages = [
            'selectedSem' => 'Need to select a semester.', // Generic for all required fields
            'selectedYear' => 'Passwords must match.',
        ];

        $request->validate([
            'selectedSem' => 'required',
            'selectedYear' => 'required',
        ], $messages);

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
        } else {
            $school_year = $request->input('selectedYear');
            $semester = $request->input('selectedSem');

            if ($semester == '2nd') {
                $semester1 = '1st';
                $school_year = $request->input('selectedYear');

                // Find all 1st semester batches for this scholarship and school year
                $firstSemBatches = Batch::where('scholarship_id', $scholarship->id)
                    ->where('school_year_id', $school_year)
                    ->where('semester', $semester1)
                    ->get();

                // Check if we already have 2nd semester batches
                $secondSemBatchesExist = Batch::where('scholarship_id', $scholarship->id)
                    ->where('school_year_id', $school_year)
                    ->where('semester', $semester)
                    ->exists();

                // Only create new batches if they don't already exist
                if (!$secondSemBatchesExist && $firstSemBatches->isNotEmpty()) {
                    // Create a new 2nd semester batch for each 1st semester batch
                    foreach ($firstSemBatches as $firstSemBatch) {
                        $newBatch = Batch::create([
                            'scholarship_id' => $firstSemBatch->scholarship_id,
                            'campus_id' => $firstSemBatch->campus_id,
                            'batch_no' => $firstSemBatch->batch_no,
                            'school_year_id' => $firstSemBatch->school_year_id,
                            'total_scholars' => $firstSemBatch->total_scholars,
                            'sub_total' => 0, // Reset sub_total for new semester
                            'semester' => $semester, // Use 2nd semester
                            'status' => 'Active', // Set initial status
                        ]);
                    }
                }

                $selectedBatch = Batch::where('scholarship_id', $scholarship->id)
                    ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                    ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                    ->first();

                // After creating the new batch, copy all grantees from old batch to new batch
                if ($selectedBatch) {
                    // Get all payouts for this scholarship, school year, and semester
                    $payouts = Payout::where('scholarship_id', $scholarship->id)
                        ->where('school_year_id', $selectedBatch->school_year_id)
                        ->where('semester', $semester1)
                        ->get();

                    // Check if all payouts are inactive
                    $allPayoutsInactive = $payouts->count() > 0 && $payouts->every(function ($payout) {
                        return $payout->status === 'Inactive';
                    });

                    // Only proceed if all payouts are inactive
                    if ($allPayoutsInactive) {
                        // Find all grantees from the old batch
                        $oldGrantees = Grantees::where('scholarship_id', $scholarship->id)
                            ->where('school_year_id', $school_year)
                            ->where('semester', $semester1)
                            ->get();

                        // Check if the first grantee has semester 2nd
                        if ($oldGrantees->isNotEmpty()) {
                            $grantees2 = Grantees::where('scholarship_id', $scholarship->id)
                                ->where('school_year_id', $school_year)
                                ->where('semester', $request->input('selectedSem'))
                                ->get();

                            if ($grantees2->isEmpty()) {
                                $newsemBatch = Batch::where('scholarship_id', $scholarship->id)
                                    ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                                    ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                                    ->get();
                                foreach ($newsemBatch as $batch) {
                                    $batch->update([
                                        'status' => 'Active'
                                    ]);
                                }

                                // Process each grantee and associate with the correct new batch
                                foreach ($oldGrantees as $oldGrantee) {
                                    // Get the old batch for this grantee
                                    $oldBatch = Batch::find($oldGrantee->batch_id);

                                    // Find the matching new batch with same campus and batch number
                                    $newBatch = Batch::where('scholarship_id', $scholarship->id)
                                        ->where('school_year_id', $school_year)
                                        ->where('semester', $semester)
                                        ->where('campus_id', $oldBatch->campus_id)
                                        ->where('batch_no', $oldBatch->batch_no)
                                        ->first();

                                    if ($newBatch) {
                                        // Get the matching scholar record
                                        $scholarEnrolled = Scholar::where('id', $oldGrantee->scholar_id)
                                            ->where('student_status', 'Enrolled')
                                            ->first();

                                        $scholarUnenrolled = Scholar::where('id', $oldGrantee->scholar_id)
                                            ->whereIn('student_status', ['Graduated', 'Dropped', 'Unenrolled'])
                                            ->first();

                                        $granteeNow = Grantees::where('scholar_id', $oldGrantee->scholar_id)
                                            ->where('school_year_id', $school_year)
                                            ->where('semester', $semester1)
                                            ->first();

                                        if ($scholarEnrolled) {
                                            Grantees::create([
                                                'scholarship_id' => $oldGrantee->scholarship_id,
                                                'batch_id' => $newBatch->id, // Use the new batch ID
                                                'scholar_id' => $scholarEnrolled->id,
                                                'school_year_id' => $school_year,
                                                'semester' => $semester,
                                                'student_status' => $scholarEnrolled->student_status,
                                                'status' => 'Active',
                                            ]);
                                        } elseif ($scholarUnenrolled) {
                                            Grantees::create([
                                                'scholarship_id' => $oldGrantee->scholarship_id,
                                                'batch_id' => $newBatch->id, // Use the new batch ID
                                                'scholar_id' => $scholarUnenrolled->id,
                                                'school_year_id' => $school_year,
                                                'semester' => $semester,
                                                'status' => 'Pending',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Filtered batch for One-time Payment redirect
        $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem)) // Add condition for selectedSem
            ->first();

        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->with('school_year')
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->first();

        if ($scholarship->scholarshipType == 'One-time Payment' && $applicantTrack) {
            return redirect()->route('scholarship.onetime_list', [
                'scholarshipId' => $scholarship->id,
                'selectedYear' => $request->input('selectedYear'),
                'selectedSem' => $request->input('selectedSem')
            ]);
        }

        // Get all batches before filtering for counting
        $allBatchesForCounting = Batch::where('scholarship_id', $scholarship->id)
            ->where('campus_id', $request->input('selectedCampus'))
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->count();

        // Total batches for selected campus (without filtering by student status)
        $totalBatches = $allBatchesForCounting;

        $currentUser = Auth::user();
        // All filtered batches
        $batchesQuery = null;

        if ($currentUser->usertype == 'super_admin') {
            $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
                ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                ->with('school_year')
                ->with([
                    'grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name'),
                    'grantees.scholar.submittedRequirements'
                ]);
        } else {
            $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
                ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                ->where('campus_id', $currentUser->campus_id)
                ->with('school_year')
                ->with([
                    'grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name'),
                    'grantees.scholar.submittedRequirements'
                ]);
        }

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

            // Filter out batches where all grantees are dropped or graduated
            $campusBatches = $campusBatches->filter(function ($batch) {
                $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
                if ($totalGrantees === 0)
                    return true;

                $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                    ->whereIn('student_status', ['Dropped', 'Graduated'])
                    ->count();

                return $droppedOrGraduatedGrantees < $totalGrantees;
            });

            if ($campusBatches->isNotEmpty()) {
                $batchesByCampus[$campus->id] = [
                    'campus' => $campus,
                    'batches' => $campusBatches
                ];
            }
        }

        // All filtered batches
        $allBatchesUnfiltered = $batchesQuery->orderBy('batch_no')->get();

        // Filter out batches where all grantees are dropped or graduated
        $batches = $allBatchesUnfiltered->filter(function ($batch) {
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
            if ($totalGrantees === 0)
                return true;

            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            return $droppedOrGraduatedGrantees < $totalGrantees;
        });

        $schoolyear = $request->input('selectedYear')
            ? SchoolYear::find($request->input('selectedYear'))
            : null;

        $courses = Course::all();
        $academic_year = AcademicYear::where('school_year_id', $schoolyear->id)->first();

        $students = [];

        if ($academic_year) {
            $students = Student::where('academic_year_id', $academic_year->id)->get();
        }

        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        // Grantees query
        $granteesQuery = $scholarship->grantees()
            ->whereIn('batch_id', $batches->pluck('id'))
            ->where('semester', $request->input('selectedSem'))
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

        $total_scholars = $grantees->map(fn($grantee) => $grantee->scholar)
            ->filter()->unique('id');

        $total_approved = optional($batch)->id
            ? Grantees::where('batch_id', $batch->id)
                ->where('semester', $request->input('selectedSem'))
                ->whereIn('status', ['Active', 'Pending'])
                ->get()
            : collect(); // fallback to empty if batch is null

        $hasActiveGrantees = false;

        if ($batch && isset($batch->id)) {
            $noForward = Grantees::where('batch_id', $batch->id)
                ->where('status', 'Active')
                ->get();

            if ($noForward->isEmpty()) {
                $hasActiveGrantees = true;
            }
        }

        $approvedCount = null;
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

        $completedBatches = null;

        if ($currentUser->usertype == 'super_admin') {
            $completedBatches = Batch::where('scholarship_id', $scholarship->id)
                ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                ->whereRaw('total_scholars = sub_total')
                ->count();

        } else {
            $completedBatches = Batch::where('scholarship_id', $scholarship->id)
                ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
                ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
                ->where('campus_id', $currentUser->campus_id)
                ->whereRaw('total_scholars = sub_total')
                ->count();
        }

        $currentUser = Auth::user();

        $allBatchesOriginal = Batch::where('scholarship_id', $scholarship->id)->get();

        $inactiveBatches = $allBatchesOriginal->every(fn($batch) => $batch->status === 'Inactive');

        $allPayouts = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->input('selectedYear'))
            ->where('semester', $request->input('selectedSem'))
            ->get();

        $inactivePayouts = $allPayouts->every(fn($payout) => $payout->status === 'Inactive');

        $allBatchesUnfiltered = Batch::where('scholarship_id', $scholarship->id)
            ->with(['grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name')])
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // Filter out batches where all grantees are dropped or graduated
        $allBatches = $allBatchesUnfiltered->filter(function ($batch) {
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
            if ($totalGrantees === 0)
                return true;

            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            return $droppedOrGraduatedGrantees < $totalGrantees;
        });

        $allBatcheswithoutmeUnfiltered = Batch::where('scholarship_id', $scholarship->id)
            ->where('campus_id', '!=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // Filter out batches where all grantees are dropped or graduated
        $allBatcheswithoutme = $allBatcheswithoutmeUnfiltered->filter(function ($batch) {
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
            if ($totalGrantees === 0)
                return true;

            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            return $droppedOrGraduatedGrantees < $totalGrantees;
        });

        $allInactivewithoutme = Batch::where('scholarship_id', $scholarship->id)
            ->where('status', 'Inactive')
            ->where('campus_id', '!=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // Filter out batches where all grantees are dropped or graduated
        $allInactivewithoutme = $allInactivewithoutme->filter(function ($batch) {
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
            if ($totalGrantees === 0)
                return true;

            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            return $droppedOrGraduatedGrantees < $totalGrantees;
        });

        $MybatchesUnfiltered = Batch::where('scholarship_id', $scholarship->id)
            ->where('status', 'Inactive')
            ->where('campus_id', '=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // Filter out batches where all grantees are dropped or graduated
        $Mybatches = $MybatchesUnfiltered->filter(function ($batch) {
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();
            if ($totalGrantees === 0)
                return true;

            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            return $droppedOrGraduatedGrantees < $totalGrantees;
        });

        $myInactive = false;

        foreach ($Mybatches as $batch) {
            if (
                $batch && $batch->status == 'Inactive'
            ) {
                $myInactive = true;
                break;
            }
        }

        $allInactive = false;

        foreach ($allInactivewithoutme as $batch) {
            if (
                $batch && $batch->status == 'Inactive'
            ) {
                $allInactive = true;
                break;
            }
        }

        $checkValidated = false;

        foreach ($allBatcheswithoutme as $batch) {
            if (
                $batch && $batch->validated == true
            ) {
                $checkValidated = true;
                break;
            }
        }

        $valitedBatches = false;

        foreach ($allBatches as $batch) {
            if (
                $batch && $batch->validated == true
            ) {
                $valitedBatches = true;
                break;
            }
        }

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

        $allBatchesInactive = $allBatches->every(fn($batch) => $batch->status === 'Inactive');

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

        // Update payout batches with proper semester filtering
        $payoutBatches = $batches->map(function ($batch) use ($request) {
            return array_merge($batch->toArray(), [
                'claimed_count' => $batch->disbursement()
                    ->whereHas('payout', function ($query) use ($request) {
                        $query->where('semester', $request->input('selectedSem'));
                    })
                    ->where('status', 'Claimed')
                    ->count(),
                'not_claimed_count' => $batch->disbursement()
                    ->whereHas('payout', function ($query) use ($request) {
                        $query->where('semester', $request->input('selectedSem'));
                    })
                    ->whereIn('status', ['Pending', 'Not Claimed'])
                    ->count()
            ]);
        });

        // Update grantees query for payout section
        $grantees = collect();
        if ($payoutBatches->isNotEmpty()) {
            $grantees = $scholarship->grantees()
                ->whereIn('batch_id', $payoutBatches->pluck('id'))
                ->where('semester', $request->input('selectedSem'))
                ->with('scholar.campus', 'scholar.course')
                ->get();
        }

        $disableSendEmailButton = false;
        foreach ($grantees as $grantee) {
            if (
                $grantee->scholar &&
                $grantee->scholar->status === 'Unverified' &&
                $grantee->scholar->student_status === 'Unenrolled'
            ) {
                $disableSendEmailButton = true;
                break;
            }
        }

        $valitedScholars = false;

        foreach ($grantees as $grantee) {
            if (
                $grantee->scholar &&
                $grantee->scholar->campus_id == Auth::user()->campus_id && ($grantee->scholar->status === 'Verified' && $grantee->scholar->student_status === 'Enrolled' ||
                    ($grantee->scholar->student_status === 'Dropped' || $grantee->scholar->student_status === 'Graduated'))
            ) {
                $valitedScholars = true;
                break;
            }
        }

        $granteeInactive = false;

        foreach ($grantees as $grantee) {
            if (
                $grantee && $grantee->status == 'Accomplished'
            ) {
                $granteeInactive = true;
                break;
            }
        }

        $validationStatus = false;

        foreach ($grantees as $grantee) {
            if ($grantee && $grantee->scholar && $grantee->scholar->campus_id == Auth::user()->campus_id) {
                $status = $grantee->scholar->student_status;
                if ($status === 'Enrolled' || $status === 'Graduated' || $status === 'Dropped') {
                    $validationStatus = true;
                    break; // We can exit the loop once we find a valid status
                }
            }
        }

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'batchesByCampus' => $batchesByCampus,
            'allBatchesInactive' => $allBatchesInactive,
            'total_scholars' => $total_scholars,
            'total_approved' => $total_approved,
            'payoutsByCampus' => $payoutsByCampus,
            'approvedCount' => $approvedCount,
            'requirements' => $requirements,
            'hasActiveGrantees' => $hasActiveGrantees,
            'grantees' => $grantees,
            'completedBatches' => $completedBatches,
            'inactiveBatches' => $inactiveBatches,
            'allInactive' => $allInactive,
            'myInactive' => $myInactive,
            'inactivePayouts' => $inactivePayouts,
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
            'valitedScholars' => $valitedScholars,
            'valitedBatches' => $valitedBatches,
            'validationStatus' => $validationStatus,
            'checkValidated' => $checkValidated,
            'granteeInactive' => $granteeInactive,
            'disableSendEmailButton' => $disableSendEmailButton,
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
            ->where('school_year_id', $schoolYearId)
            ->where('campus_id', $selectedCampus)
            ->get();

        // Update each batch individually using the requested format
        foreach ($batches as $batch) {
            Batch::where('id', $batch->id)->update([
                'status' => 'Inactive'
            ]);
        }

        $currentUser = Auth::user();

        $campus = Campus::where('id', $currentUser->campus_id)->first();

        // Create notification for coordinator
        $notification = Notification::create([
            'title' => $campus->name . ' Batches forward',
            'message' => $campus->name . ' campus, Batches now finished',
            'type' => 'scholars_upload',
        ]);

        $super_admin = User::where('usertype', 'super_admin')->first();

        // Attach the coordinator to the notification
        $notification->users()->attach($super_admin->id);

        return redirect()->back()->with('success', 'Forwarded Successfully');
    }

    public function forward_sponsor($scholarshipId, Request $request)
    {
        // Find the scholarship
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get parameters from the request
        $selectedSem = $request->input('selectedSem');
        $schoolYearId = $request->input('school_year');

        $payouts = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $schoolYearId)
            ->where('semester', $selectedSem)
            ->first();

        // Find all matching batches
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $schoolYearId)
            ->where('semester', $selectedSem)
            ->get();

        if ($payouts) {
            $currentUser = Auth::user();
            $campus = Campus::where('id', $currentUser->campus_id)->first();

            // Update each batch individually using the requested format
            foreach ($batches as $batch) {
                // Update all grantees associated with this batch
                Grantees::where('batch_id', $batch->id)
                    ->where('school_year_id', $schoolYearId)
                    ->where('semester', $selectedSem)
                    ->update([
                        'status' => 'Accomplished'
                    ]);
            }

            // Create notification for coordinator
            $notification = Notification::create([
                'title' => 'University Payout Done',
                'message' => 'URS forwarded a list of payouts',
                'type' => 'forward_sponsor',
            ]);

            $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();
            $sponsorUser = User::where('id', $sponsor->assign_id)->first();

            // Attach the coordinator to the notification
            $notification->users()->attach($sponsorUser->id);

            return redirect()->back()->with('success', 'Forwarded Successfully');
        }

        // Update each batch individually using the requested format
        foreach ($batches as $batch) {
            // Update batch status
            Batch::where('id', $batch->id)->update([
                'status' => 'Inactive'
            ]);
        }



        $currentUser = Auth::user();
        $campus = Campus::where('id', $currentUser->campus_id)->first();

        // Create notification for coordinator
        $notification = Notification::create([
            'title' => 'University Batch Done',
            'message' => 'URS forwarded a list of batches',
            'type' => 'forward_sponsor',
        ]);

        $sponsor = Sponsor::where('id', $scholarship->sponsor_id)->first();
        $sponsorUser = User::where('id', $sponsor->assign_id)->first();

        // Attach the coordinator to the notification
        $notification->users()->attach($sponsorUser->id);

        return redirect()->back()->with('success', 'Forwarded Successfully');
    }

    public function forward_validate($scholarshipId, Request $request)
    {
        // Find the scholarship
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get parameters from the request
        $selectedSem = $request->input('selectedSem');
        $schoolYearId = $request->input('school_year');
        $selectedCampus = $request->input('selectedCampus');

        // Find all matching batches
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $schoolYearId)
            ->where('campus_id', $selectedCampus)
            ->get();

        foreach ($batches as $batch) {
            // Count total grantees for this batch
            $totalGrantees = Grantees::where('batch_id', $batch->id)->count();

            // Count dropped and graduated grantees for this batch
            $droppedOrGraduatedGrantees = Grantees::where('batch_id', $batch->id)
                ->whereIn('student_status', ['Dropped', 'Graduated'])
                ->count();

            // If all grantees are either dropped or graduated, set batch status to Inactive
            if ($totalGrantees > 0 && $droppedOrGraduatedGrantees == $totalGrantees) {
                Batch::where('id', $batch->id)->update([
                    'validated' => true,
                    'status' => 'Inactive'
                ]);
            } else {
                // Otherwise proceed with the original "Active" status
                Batch::where('id', $batch->id)->update([
                    'validated' => true,
                    'status' => 'Active'
                ]);
            }
        }

        $currentUser = Auth::user();
        $campus = Campus::where('id', $currentUser->campus_id)->first();

        // Create notification for coordinator
        $notification = Notification::create([
            'title' => $campus->name . ' Validated forward',
            'message' => $campus->name . ' campus, Validated now finished',
            'type' => 'validated_forward',
        ]);

        $super_admin = User::where('usertype', 'super_admin')->first();

        // Attach the coordinator to the notification
        $notification->users()->attach($super_admin->id);

        return redirect()->back()->with('success', 'Forwarded Successfully');
    }


    public function onetime_list(Request $request, $scholarshipId)
    {
        // Find the scholarship by ID
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $currentUser = Auth::user();

        // Get the batch for the selected semester and school year
        $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->input('selectedYear'))
            ->where('semester', $request->input('selectedSem'))
            ->where('campus_id', $currentUser->campus_id)
            ->firstOrFail();

        // Check if scholar's payment claimed
        $payout = Payout::where('scholarship_id', $scholarshipId)
            ->where('status', 'claimed')
            ->get();

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->where('semester', $request->input('selectedSem'))
            ->where('school_year_id', $request->input('selectedYear'))
            ->first();


        // Use relationships to get applicants and their related scholars
        $applicants = $scholarship->applicants()
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
                // ->where('semester', $request->input('selectedSem'))
                // ->where('school_year_id', $request->input('selectedYear'))
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
                'applicant_status' => $applicant->status,
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
            'applicantTrack' => $applicantTrack,
            'applicants' => $applicants,
            'scholars' => $scholars,
            'payout' => $payout,
            'currentUser' => $currentUser,
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
            'campus_recipients.*.slots' => 'required|integer|min:0', //need palitan to ha
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

            $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
                ->where('semester', $request->semester)
                ->where('school_year_id', $request->school_year)
                ->where('campus_id', $campusRecipient['campus_id'])
                ->first();

            if (!$applicantTrack) {
                ApplicantTrack::create([
                    'scholarship_id' => $scholarship->id,
                    'school_year_id' => $request->school_year,
                    'campus_id' => $campusRecipient['campus_id'],
                    'semester' => $request->semester,
                    'status' => 'Active',
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

    public function one_timebatch()
    {
        // $scholars = $scholarship->scholars;

        // $requirements = Requirements::where('scholarship_id', $scholarship->id)->first();


        return Inertia::render('Staff/Scholarships/One-Time/ListOfGrantees', [
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
            // Skip grantees that don't have 'Active' status
            if (!isset($grantee['status']) || $grantee['status'] !== 'Active') {
                continue;
            }


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
            'description' => 'Active scholars forwarded to cashiers by campus',
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
            'message' => 'Active scholars forwarded to cashiers by campus by ' . $user->name,
            'type' => 'payout_forward',
        ]);

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for new notification
        event(new NewNotification($notification));

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


