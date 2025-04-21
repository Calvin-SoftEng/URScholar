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
use App\Models\ScholarshipTemplate;
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
        $scholarships = Scholarship::with(['requirements', 'batches'])
            ->where(function ($query) use ($userId, $userCampusId) {
                $query->whereHas('batches', function ($subQuery) use ($userCampusId) {
                    $subQuery->where('campus_id', $userCampusId);
                })
                    ->orWhereHas('applicant_tracks', function ($subQuery) use ($userCampusId) {
                        $subQuery->where('campus_id', $userCampusId);
                    })
                    ->orWhere('user_id', $userId);
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
                ->whereIn('status', ['Active', 'Pending', 'Accomplished', 'Inactive'])
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


        $schoolyear = SchoolYear::find($batch->school_year_id);

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
            'schoolyear' => $schoolyear,
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

                        if ($firstSemBatch->status == 'Inactive') {
                            // Skip creating a new batch if the first semester batch is inactive
                            continue;
                        }

                        $newBatch = Batch::create([
                            'scholarship_id' => $firstSemBatch->scholarship_id,
                            'campus_id' => $firstSemBatch->campus_id,
                            'batch_no' => $firstSemBatch->batch_no,
                            'school_year_id' => $firstSemBatch->school_year_id,
                            'total_scholars' => $firstSemBatch->total_scholars,
                            'sub_total' => 0, // Reset sub_total for new semester
                            'semester' => $semester, // Use 2nd semester
                            'status' => 'Pending', // Set initial status
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

                                            if ($oldGrantee->student_status == 'Enrolled') {
                                                // Check if the grantee already exists in the new batch
                                                Grantees::create([
                                                    'scholarship_id' => $oldGrantee->scholarship_id,
                                                    'batch_id' => $newBatch->id, // Use the new batch ID
                                                    'scholar_id' => $scholarEnrolled->id,
                                                    'school_year_id' => $school_year,
                                                    'semester' => $semester,
                                                    'student_status' => $scholarEnrolled->student_status,
                                                    'status' => 'Pending',
                                                ]);
                                            }

                                            if ($newBatch->campus_id == Auth::user()->campus_id) {
                                                // Get all grantees for this new batch
                                                $batchGrantees = Grantees::where('batch_id', $newBatch->id)->get();

                                                // Check if there are grantees and if all of them have "Enrolled" status
                                                if (
                                                    $batchGrantees->isNotEmpty() && $batchGrantees->every(function ($grantee) {
                                                        return $grantee->student_status === 'Enrolled';
                                                    })
                                                ) {
                                                    // Update batch status to Validated
                                                    $newBatch->update([
                                                        'status' => 'Pending',
                                                        'validated' => true
                                                    ]);
                                                }
                                            }

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

            // REMOVED: Filter out batches where all grantees are dropped or graduated

            if ($campusBatches->isNotEmpty()) {
                $batchesByCampus[$campus->id] = [
                    'campus' => $campus,
                    'batches' => $campusBatches
                ];
            }
        }

        // All filtered batches
        $allBatchesUnfiltered = $batchesQuery->orderBy('batch_no')->get();

        // REMOVED: Filter out batches where all grantees are dropped or graduated
        $batches = $allBatchesUnfiltered;


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
                $newStatus = 'Pending';
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

        $allBatchesOriginal = Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->get();

        $inactiveBatches = $allBatchesOriginal->every(fn($batch) => $batch->status === 'Inactive');

        $accomplishedBatches = $allBatchesOriginal->every(fn($batch) => in_array($batch->status, ['Accomplished', 'Inactive']));


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

        // REMOVED: Filter out batches where all grantees are dropped or graduated
        $allBatches = $allBatchesUnfiltered;

        $allBatcheswithoutmeUnfiltered = Batch::where('scholarship_id', $scholarship->id)
            ->where('campus_id', '!=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // REMOVED: Filter out batches where all grantees are dropped or graduated
        $allBatcheswithoutme = $allBatcheswithoutmeUnfiltered;

        $allInactivewithoutme = Batch::where('scholarship_id', $scholarship->id)
            ->where('status', 'Inactive')
            ->where('campus_id', '!=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // REMOVED: Filter out batches where all grantees are dropped or graduated
        $allInactivewithoutmeeee = $allInactivewithoutme;

        $MybatchesUnfiltered = Batch::where('scholarship_id', $scholarship->id)
            ->where('campus_id', '=', Auth::user()->campus_id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->get();

        // REMOVED: Filter out batches where all grantees are dropped or graduated
        $Mybatches = $MybatchesUnfiltered;

        $myInactive = false;

        foreach ($Mybatches as $batch) {
            if (
                $batch && $batch->status == 'Inactive' || $batch->status == 'Accomplished'
            ) {
                $myInactive = true;
                break;
            }
        }

        $noScholars = false;

        foreach ($Mybatches as $batch) {
            if (
                $batch && $batch->status == 'Inactive'
            ) {
                $noScholars = true;
                break;
            }
        }

        $allInactive = false;

        foreach ($allInactivewithoutmeeee as $batch) {
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

        $valitedBatches = true;

        foreach ($MybatchesUnfiltered as $batch) {
            if ($batch->validated == true) {
                $valitedBatches = false;
                break;
            }
        }

        // Change the logic to check if all batches are validated
        $AllvalidationStatus = true; // Start by assuming all are validated

        foreach ($allBatches as $batch) {
            if ($batch->validated == false) {
                $AllvalidationStatus = false; // If any batch is not validated, set to false
                break; // No need to continue checking
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

        $allBatchesInactive = $allBatches->every(fn($batch) => in_array($batch->status, ['Inactive', 'Accomplished']));


        // $payout_Batches = $batchesQuery->with([
        //     'disbursement',
        //     'campus:id,name'
        // ])
        //     ->orderBy('batch_no')
        //     ->get();

        // // Compute claimed and not claimed for each batch
        // $payoutBatches = $payout_Batches->map(function ($batch) {
        //     $claimed = $batch->disbursement->where('status', 'Claimed')->count();
        //     $notClaimed = $batch->disbursement->whereIn('status', ['Pending', 'Not Claimed'])->count();

        //     return array_merge($batch->toArray(), [
        //         'claimed_count' => $claimed,
        //         'not_claimed_count' => $notClaimed
        //     ]);
        // });


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
                    ($grantee->scholar->student_status === 'Dropped' || $grantee->scholar->student_status === 'Graduated' || $grantee->scholar->student_status === 'Transferred'))
            ) {
                $valitedScholars = true;
                break;
            }
        }

        $granteeInactive = false;

        foreach ($grantees as $grantee) {
            if (
                $grantee && $grantee->status == 'Accomplished' && $grantee->campus_id == Auth::user()->campus_id
            ) {
                $granteeInactive = true;
                break;
            }
        }

        $validationStatus = false;

        foreach ($grantees as $grantee) {
            if ($grantee && $grantee->scholar && $grantee->scholar->campus_id == Auth::user()->campus_id) {
                $status = $grantee->scholar->student_status;
                if ($status === 'Enrolled' || $status === 'Graduated' || $status === 'Dropped' || $status === 'Transferred') {
                    $validationStatus = true;
                    break; // We can exit the loop once we find a valid status
                }
            }
        }


        foreach ($grantees as $grantee) {
            if ($grantee && $grantee->scholar) {
                $status = $grantee->scholar->student_status;
                if ($status === 'Enrolled' || $status === 'Graduated' || $status === 'Dropped') {
                    $validationStatus = true;
                    break; // We can exit the loop once we find a valid status
                }
            }
        }

        $total_verified_grantees = $grantees->filter(function ($grantee) {
            return $grantee && $grantee->student_status === 'Enrolled';
        })->count();

        $total_unverified_grantees = $grantees->filter(function ($grantee) {
            return $grantee && $grantee->student_status && (
                $grantee->scholar->status === 'Dropped' ||
                $grantee->scholar->status === 'Unenrolled' ||
                $grantee->scholar->status === 'Graduated'
            );
        })->count();


        $totalSubTotal = $batches->sum('sub_total') ?? 0;

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'total_verified_grantees' => $total_verified_grantees,
            'total_unverified_grantees' => $total_unverified_grantees,
            'scholarship' => $scholarship,
            'batches' => $batches,
            'totalSubTotal' => $totalSubTotal,
            'batchesByCampus' => $batchesByCampus,
            'allBatchesInactive' => $allBatchesInactive,
            'noScholars' => $noScholars,
            'total_scholars' => $total_scholars,
            'total_approved' => $total_approved,
            'payoutsByCampus' => $payoutsByCampus,
            'approvedCount' => $approvedCount,
            'requirements' => $requirements,
            'hasActiveGrantees' => $hasActiveGrantees,
            'grantees' => $grantees,
            'completedBatches' => $completedBatches,
            'inactiveBatches' => $inactiveBatches,
            'accomplishedBatches' => $accomplishedBatches,
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
            'AllvalidationStatus' => $AllvalidationStatus,
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
            ->where('semester', $selectedSem)
            ->where('campus_id', $selectedCampus)
            ->get();

        // Update each batch individually using the requested format
        foreach ($batches as $batch) {
            Batch::where('id', $batch->id)->update([
                'status' => 'Accomplished'
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

        $ready = false;
        foreach ($batches as $batch) {
            if ($batch->total_scholars == $batch->sub_total) {
                $ready = true;
                break;
            }
        }

        // Process each batch individually
        foreach ($batches as $batch) {
            // Only update status if it's not already "Inactive"
            if ($batch->status !== 'Inactive') {
                $newStatus = $ready ? 'Accomplished' : 'Inactive';

                // Update batch status
                Batch::where('id', $batch->id)->update([
                    'status' => $newStatus,
                    'validated' => true
                ]);
            } else {
                // For "Inactive" batches, only update validated flag
                Batch::where('id', $batch->id)->update([
                    'validated' => true
                ]);
            }

            // Update grantees status (this happens regardless of batch status)
            Grantees::where('batch_id', $batch->id)
                ->where('school_year_id', $schoolYearId)
                ->where('semester', $selectedSem)
                ->update([
                    'status' => 'Active'
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
            ->where('semester', $selectedSem)
            ->where('school_year_id', $schoolYearId)
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

        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->input('selectedYear'))
            ->where('semester', $request->input('selectedSem'))
            ->first();

        if ($batch) {
            return redirect()->route('scholarship.onetime_batch', [
                'scholarshipId' => $scholarship->id,
                'batchId' => $batch->id,
                'selectedYear' => $request->input('selectedYear'),
                'selectedSem' => $request->input('selectedSem')
            ]);
        }

        // Get the batch for the selected semester and school year
        $applicantTrack = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->input('selectedYear'))
            ->where('semester', $request->input('selectedSem'))
            ->when($currentUser->usertype !== 'super_admin', function ($query) use ($currentUser) {
                // Only filter by campus if user is not a super_admin
                return $query->where('campus_id', $currentUser->campus_id);
            })
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
                'campus_id' => $scholar->campus_id, // Make sure campus_id is included
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

        // Add filtering based on user type in the backend
        if ($currentUser->usertype !== 'super_admin') {
            // Filter scholars by campus_id if not super_admin
            $scholars = $scholars->filter(function ($scholar) use ($currentUser) {
                return $scholar['campus_id'] == $currentUser->campus_id;
            })->values(); // Re-index the collection
        }

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

    public function publishApplicantList(Request $request, $scholarshipId)
    {
        // Validate the request
        $validated = $request->validate([
            'school_year_id' => 'required',
            'semester' => 'required|string'
        ]);

        // Find the scholarship by ID
        $scholarship = Scholarship::findOrFail($scholarshipId);
        $currentUser = Auth::user();

        // Get all applicant tracks for the selected semester and school year across all campuses
        $applicantTracks = ApplicantTrack::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $request->input('school_year_id'))
            ->where('semester', $request->input('semester'))
            ->get();

        if ($applicantTracks->isEmpty()) {
            return back()->with('error', 'No applicant tracks found for this scholarship.');
        }

        $totalApprovedCount = 0;
        $batchesCreated = [];

        // Process each campus's applicant track
        foreach ($applicantTracks as $applicantTrack) {
            // Get all approved applicants for this track
            $approvedApplicants = Applicant::where('applicant_track_id', $applicantTrack->id)
                ->where('status', 'Approved')
                ->with('scholar')
                ->get();

            if ($approvedApplicants->isEmpty()) {
                // Skip this campus if no approved applicants
                continue;
            }

            // Get the latest batch number for this scholarship
            $latestBatch = Batch::where('scholarship_id', $scholarship->id)
                ->orderBy('batch_no', 'desc')
                ->first();

            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;

            // Create a new batch for this campus
            $batch = Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => $batchNo,
                'school_year_id' => $request->input('school_year_id'),
                'semester' => $request->input('semester'),
                'campus_id' => $applicantTrack->campus_id,
                'total_scholars' => $approvedApplicants->count(),
                'sub_total' => $approvedApplicants->count(),
                'status' => 'Accomplished',
                'validated' => true,
                'read' => false,
            ]);

            // Create grantees for each approved applicant
            foreach ($approvedApplicants as $applicant) {
                Grantees::create([
                    'scholarship_id' => $scholarship->id,
                    'batch_id' => $batch->id,
                    'scholar_id' => $applicant->scholar_id,
                    'school_year_id' => $request->input('school_year_id'),
                    'semester' => $request->input('semester'),
                    'student_status' => 'Enrolled', // Default status
                    'status' => 'Active',
                ]);
            }

            // Update the applicant track status to inactive after publishing
            $applicantTrack->update([
                'status' => 'Inactive'
            ]);

            $totalApprovedCount += $approvedApplicants->count();
            $batchesCreated[] = $batch->id;
        }

        if ($totalApprovedCount === 0) {
            return back()->with('error', 'No approved applicants found for this scholarship in any campus.');
        }

        // If we're dealing with the current user's campus only
        if (count($batchesCreated) === 1 && $applicantTracks->contains('campus_id', $currentUser->campus_id)) {
            return redirect()->route('scholarship.onetime_batch', [
                'scholarshipId' => $scholarship->id,
                'batchId' => $batchesCreated[0],
                'selectedYear' => $request->input('school_year_id'),
                'selectedSem' => $request->input('semester'),
            ])->with('success', 'Applicant list has been published successfully.');
        }

        // If multiple campuses were processed
        return redirect()->route('scholarship.onetime_batch', [
            'scholarshipId' => $scholarship->id,
            'batchId' => $batch->id,
            'selectedYear' => $request->input('school_year_id'),
            'selectedSem' => $request->input('semester'),

        ])->with('success', 'Applicant list has been published successfully.');
    }

    public function showBatch(Request $request, $scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);
        $batch = Batch::with(['grantees.scholar', 'school_year', 'campus'])->findOrFail($batchId);

        // Get payout information for this batch
        $payout = Payout::where('scholarship_id', $scholarshipId)
            ->where('school_year_id', $batch->school_year_id)
            ->where('semester', $batch->semester)
            ->first();

        $DonePayout = Payout::where('scholarship_id', $scholarshipId)
            ->where('school_year_id', $batch->school_year_id)
            ->where('semester', $batch->semester)
            ->where('status', 'Inactive')
            ->count();

        // Get all disbursements for this payout and batch
        $disbursements = [];
        if ($payout) {
            $disbursements = Disbursement::where('payout_id', $payout->id)
                ->where('batch_id', $batchId)
                ->with('scholar')  // Load scholar relationship
                ->get();
        }

        // Get all scholars for reference
        $scholars = Scholar::with('campus')->get();

        // Get campuses and school year data
        $campus = Campus::all();
        $schoolyear = SchoolYear::where('id', $batch->school_year_id)->first();

        return Inertia::render('Staff/Scholarships/One-Time/ListOfGrantees', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'grantees' => $batch->grantees->map(function ($grantee) {
                $scholar = $grantee->scholar;

                $user = User::where('id', $scholar->user_id)->first();
                return [
                    'id' => $grantee->id,
                    'scholar_id' => $scholar->id,
                    'urscholar_id' => $scholar->urscholar_id,
                    'full_name' => $scholar->last_name . ', ' . $scholar->first_name . ' ' . $scholar->middle_name,
                    'campus' => $scholar->campus->name ?? 'N/A',
                    'course' => $scholar->course->name ?? 'N/A',
                    'year_level' => $scholar->year_level,
                    'student_status' => $grantee->student_status,
                    'picture' => $user->picture,
                    'status' => $grantee->status,
                ];
            }),
            'disbursements' => $disbursements,  // Pass disbursements to the view
            'scholars' => $scholars,
            'payout' => $payout,  // Pass payout data to the view
            'schoolyear' => $schoolyear,
            'campus' => $campus,
            'selectedSem' => $batch->semester,
            'DonePayout' => $DonePayout,
        ]);
    }


    public function store(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'sponsor_id' => 'required|int',
            'name' => 'required|string|max:255',
            'scholarshipType' => 'required|string|max:255',
        ]);

        Scholarship::create([
            'name' => $request->name,
            'sponsor_id' => $request->sponsor_id,
            'user_id' => Auth::user()->id,
            'scholarshipType' => $request->scholarshipType,
        ]);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Scholarship created ' . $request['name'],
        ]);

        return redirect()->route('scholarships.index')->with('success', 'Added New Scholarship');

        // return inertia('Staff/Scholarships/ViewScholarships')->with('success', 'Scholarship created successfully.');
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
            'templates.*' => 'nullable|file|max:10240', // Allow up to 10MB per file
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
        $requirementsMap = []; // To store requirement ID mappings for templates

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
                $requirementsMap[$requirement] = $existingRequirement->id;
            } else {
                // Insert new record
                $newRequirement = Requirements::create([
                    'scholarship_id' => $scholarship->id,
                    'requirements' => $requirement,
                    'date_start' => $validated['application'],
                    'date_end' => $validated['deadline'],
                    'total_scholars' => $total_recipients,
                ]);
                $requirementsMap[$requirement] = $newRequirement->id;
            }
        }

        // Handle template uploads
        if ($request->hasFile('templates')) {
            foreach ($request->file('templates') as $index => $file) {
                // Get original filename
                $originalName = $file->getClientOriginalName();

                // Generate unique filename
                $filename = $originalName;

                // Store the file in storage/app/public/templates
                $path = $file->storeAs('templates', $filename, 'public');

                // Match template with requirement (if possible)
                // This assumes file index matches requirement index
                $requirementId = null;
                if (isset($validated['requirements'][$index])) {
                    $requirementName = $validated['requirements'][$index];
                    $requirementId = $requirementsMap[$requirementName] ?? null;
                }

                // Save to scholarship_templates table
                ScholarshipTemplate::insert([
                    'scholarship_id' => $scholarship->id,
                    'requirement_id' => $requirementId,
                    'filename' => $originalName,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'created_at' => now(),
                    'updated_at' => now(),
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

        return back()->with('success', 'Scholarship recipients, requirements and templates saved successfully');
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
            'school_year_id' => 'required',
            'semester' => 'required',
        ]);

        $scholarshipId = $request->input('scholarship_id');
        $grantees = $request->input('grantees');
        $batchIds = $request->input('batch_ids');
        $user = Auth::user();

        // Create Activity Log
        $activityLog = ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Forward',
            'description' => 'Active scholars forwarded to cashiers by campus',
        ]);

        // Get users to notify (similar to original function)
        $users = User::where('usertype', 'head_cashier')->first();

        // Create Notification
        $notification = Notification::create([
            'title' => 'Scholars Ready for Disbursement',
            'message' => 'A new batch of active scholars has been forwarded and is now ready for payout processing.',
            'type' => 'payout_forward',
        ]);


        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for new notification
        event(new NewNotification($notification));

        // return redirect()->back()->with('success', 'MOA uploaded successfully.');

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


