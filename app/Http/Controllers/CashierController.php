<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Events\NewNotification;
use App\Mail\SendEmail;
use App\Models\ActivityLog;
use App\Models\Disbursement;
use App\Models\PayoutSchedule;
use App\Models\Payout;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Notification;
use App\Models\Scholar;
use App\Models\Sponsor;
use App\Models\AcademicYear;
use App\Models\SchoolYear;
use App\Models\Course;
use App\Models\User;
use App\Models\Campus;
use App\Models\Student;
use App\Models\SubmittedRequirements;
use App\Http\Controllers\Controller;
use App\Models\Grantees;
use App\Models\Requirements;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CashierController extends Controller
{
    public function dashboard()
    {
        $sponsor = Sponsor::all();
        $activeScholarships = Scholarship::where('status', 'active')->get();

        // Get the latest disbursements
        $latestDisbursements = Disbursement::with(['payout.scholarship', 'batch', 'scholar.campus', 'scholar.course', 'claimedBy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($disbursement) {
                $scholar = $disbursement->scholar;
                $payout = $disbursement->payout;
                $scholarship = $payout->scholarship;

                return [
                    'id' => $disbursement->id,
                    'urscholar_id' => $scholar->urscholar_id,
                    'first_name' => $scholar->first_name,
                    'last_name' => $scholar->last_name,
                    'campus' => $scholar->campus->name ?? 'N/A',
                    'scholarship_name' => $scholarship->name,
                    'grant_type' => $scholarship->type, // Assuming there's a 'type' field indicating Grant-Based or One-time
                    'status' => $disbursement->status, // 'Claimed', 'Pending', or 'Not Claimed'
                    'submission_date' => $disbursement->claimed_at,
                    'remarks' => $disbursement->reasons_of_not_claimed,
                    'claimed_by' => $disbursement->claimedBy ? $disbursement->claimedBy->name : 'N/A'
                ];
            });

        $active_scholars = Grantees::where('status', 'Active')
            ->get();

        $enrolled = Student::all();

        $activity_logs = ActivityLog::where('user_id', Auth::user()->id)->get();

        $academic_year = AcademicYear::where('status', 'Active')
            ->with('school_year')
            ->first();

        $univ_students = Student::where('academic_year_id', $academic_year->id)
            ->where('campus_id', Auth::user()->campus_id)
            ->count();

        return Inertia::render('Cashier/Dashboard', [
            'sponsors' => $sponsor,
            'scholarships' => $activeScholarships,
            'disbursements' => $latestDisbursements,
            'active_scholars' => $active_scholars,
            'activity_logs' => $activity_logs,
            'academic_year' => $academic_year,
            'univ_students' => $univ_students,
        ]);
    }

    public function scholarships()
    {
        // Check if the user is a university cashier
        if (Auth::user()->usertype === 'head_cashier') {
            // Get the appropriate scholarship to show
            // You might need to adjust this logic based on your requirements

            // Redirect to the show method instead
            return $this->view_scholarship();

        }

        // Original code for other user types
        $scholarships = Scholarship::all();
        $sponsors = Sponsor::all();
        $payouts = Payout::where('campus_id', Auth::user()->campus_id)
            ->where('status', '!=', 'Inactive')
            ->get();

        return Inertia::render('Cashier/Scholarships/Active_Scholarships', [
            'scholarships' => $scholarships,
            'sponsors' => $sponsors,
            'payouts' => $payouts
        ]);
    }

    public function view_scholarship()
    {
        // Get the authenticated user's ID and campus_id
        $userId = Auth::user()->id;
        $userCampusId = Auth::user()->campus_id;

        // Get scholarships that either:
        // 1. Have batches matching the user's campus_id, OR
        // 2. Have applicant_tracks matching the user's campus_id, OR
        // 3. Were created by the authenticated user
        $scholarships = Scholarship::where('status', 'Active')->get();

        // $scholarships = Scholarship::where(function ($query) use ($userId, $userCampusId) {
        //     $query->whereHas('batches', function ($subQuery) use ($userCampusId) {
        //         $subQuery->where('campus_id', $userCampusId);
        //     })
        //         ->orWhereHas('applicant_tracks', function ($subQuery) use ($userCampusId) {
        //             $subQuery->where('campus_id', $userCampusId);
        //         })
        //         ->orWhere('user_id', $userId); // Add this condition to include scholarships created by the user
        // })
        // ->get();

        $sponsors = Sponsor::all();
        $school_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        return inertia('Cashier/Scholarships/ViewScholarships', [
            'sponsors' => $sponsors,
            'scholarships' => $scholarships,
            'schoolyears' => $school_year,
        ]);
    }

    public function all_payouts(Request $request, Scholarship $scholarship)
    {
        $messages = [
            'selectedSem' => 'Need to select a semester.',
            'selectedYear' => 'Passwords must match.',
        ];
        $request->validate([
            'selectedSem' => 'required',
            'selectedYear' => 'required',
        ], $messages);

        $campuses = Campus::all();
        $schoolyear = $request->input('selectedYear')
            ? SchoolYear::find($request->input('selectedYear'))
            : null;

        $currentUser = Auth::user();
        // Cast selectedCampus to integer if it's a non-empty string
        $selectedCampus = $request->input('selectedCampus');
        $selectedCampusId = !empty($selectedCampus) ? (int) $selectedCampus : null;

        // Get batches for all campuses that match the criteria
        $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
            ->where('status', 'Accomplished')
            ->where('school_year_id', $schoolyear->id)
            ->where('semester', $request->input('selectedSem'));

        // Apply campus filter if selected
        if ($selectedCampusId) {
            $batchesQuery->where('campus_id', $selectedCampusId);
        }

        $Allbatches = $batchesQuery->with([
            'grantees.scholar' => fn($q) => $q->orderBy('last_name')->orderBy('first_name'),
            'grantees.scholar.submittedRequirements',
            'disbursement',
            'campus:id,name'
        ])
            ->orderBy('batch_no')
            ->get();

        // Compute claimed and not claimed for each batch
        $batches = $Allbatches->map(function ($batch) {
            $claimed = $batch->disbursement->where('status', 'Claimed')->count();
            $notClaimed = $batch->disbursement->whereIn('status', ['Pending', 'Not Claimed'])->count();

            return array_merge($batch->toArray(), [
                'claimed_count' => $claimed,
                'not_claimed_count' => $notClaimed
            ]);
        });

        $payouts = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $schoolyear->id)
            ->where('semester', $request->input('selectedSem'))
            ->with('campus') // eager load the campus relation
            ->get();

        $payoutQuery = Payout::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($q, $year) => $q->where('school_year_id', $year))
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem));

        // Apply campus filter to payouts if selected
        if ($selectedCampusId) {
            $payoutQuery->where('campus_id', $selectedCampusId);
        }

        $payoutQuery->with('campus');
        $payoutsByCampus = $payoutQuery->get()->groupBy('campus_id');

        $allAccomplished = false;
        foreach ($Allbatches as $batch) {
            if ($batch && $batch->status == 'Accomplished') {
                $allAccomplished = true;
                break;
            }
        }

        $totalSubTotal = $payouts->sum('sub_total') ?? 0;
        $total_scholars = $payouts->sum('total_scholars') ?? 0;

        return Inertia::render('Cashier/Scholarships/Payroll_Scholarship', [
            'scholarship' => $scholarship,
            'schoolyear' => $schoolyear,
            'selectedSem' => $request->input('selectedSem', ''),
            'selectedCampus' => $selectedCampus, // Pass the selected campus to the frontend
            'batches' => $batches,
            'campuses' => $campuses,
            'currentUser' => $currentUser,
            'payouts' => $payouts,
            'totalSubTotal' => $totalSubTotal,
            'total_scholars' => $total_scholars,
            'payoutsByCampus' => $payoutsByCampus,
            'allAccomplished' => $allAccomplished,
        ]);
    }

    public function forward(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'batch_ids' => 'required|array',
            'batch_ids.*' => 'exists:batches,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
            'school_year_id' => 'required|exists:school_years,id',
            'semester' => 'required'
        ], [
            'date_start.required' => 'Set a Date start',
            'date_end.required' => 'Set a Date end',
        ]);


        $scholarshipId = $request->input('scholarship_id');
        $batchIds = $request->input('batch_ids');
        $user = Auth::user();

        // Get all the batches we need to process
        $batches = Batch::whereIn('id', $batchIds)
        ->where('status', '!=', 'Inactive')
        ->get();


        // Group batches by campus
        $batchesByCampus = $batches->groupBy('campus_id');

        // Create payouts for each campus and process disbursements
        $createdPayouts = [];

        foreach ($batchesByCampus as $campusId => $campusBatches) {
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

            $totalScholars = 0;
            $dataToInsert = [];

            // Process each batch for this campus
            foreach ($campusBatches as $batch) {
                // Find active grantees for this batch
                $grantees = Grantees::where('batch_id', $batch->id)
                    ->where('scholarship_id', $scholarshipId)
                    ->whereIn('student_status', ['Enrolled', 'Transferred'])
                    ->where('status', 'Active')
                    ->get();

                foreach ($grantees as $grantee) {
                    $dataToInsert[] = [
                        'payout_id' => $payout->id,
                        'batch_id' => $batch->id,
                        'scholar_id' => $grantee->scholar_id,
                        'school_year_id' => $request->input('school_year_id'),
                        'semester' => $request->input('semester'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $totalScholars++;
                }
            }

            // Bulk insert disbursements
            if (!empty($dataToInsert)) {
                Disbursement::insert($dataToInsert);
            }

            // Calculate sub_total based on scholarship amount if available
            $subTotal = null;
            $scholarship = Scholarship::find($scholarshipId);
            if ($scholarship && isset($scholarship->amount)) {
                $subTotal = $scholarship->amount * $totalScholars;
            }

            // Update payout with total scholars count and sub_total
            $payout->update([
                'total_scholars' => $totalScholars,
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

        // Get campus IDs from the batches
        $campusIds = $batchesByCampus->keys()->toArray();

        // Find cashiers for each campus where batches exist and all super_admins
        $cashiers = User::whereIn('campus_id', $campusIds)
            ->where('usertype', 'cashier')
            ->where('id', '!=', Auth::user()->id)
            ->get();

        $superAdmins = User::where('usertype', 'super_admin')
            ->where('id', '!=', Auth::user()->id)
            ->get();

        // Combine the users without duplicates
        $users = $cashiers->merge($superAdmins)->unique('id');

        // Create Notification
        $notification = Notification::create([
            'title' => 'New Payouts Forwarded',
            'message' => 'Active scholars forwarded to cashiers by campus by ' . $user->first_name . ' ' . $user->last_name,
            'type' => 'payout_forward',
        ]);

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for new notification
        event(new NewNotification($notification));

        return redirect()->back()->with('success', 'Batches have been successfully forwarded to cashiers.');
    }

    public function scheduling(Scholarship $scholarship)
    {
        $academic_year = AcademicYear::where('status', 'Active')->first();

        // Get batches related to the grantees for the specific scholarship
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->with('grantees') // Eager load grantees for the batches
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('semester', $academic_year->semester)
            ->where('school_year_id', $academic_year->school_year_id)
            ->get();


        // Get the payout for the specific scholarship
        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('status', '!=', 'Inactive')
            ->orderBy('created_at', 'desc')
            ->first();

        // Get disbursements related to the payout, with scholars and their grantees
        $disbursements = Disbursement::where('payout_id', $payout->id)
            ->whereHas('scholar', function ($query) {
                $query->whereIn('student_status', ['Enrolled', 'Transferred']);
            })
            ->with('scholar.grantees.batch')
            ->get();



        return Inertia::render('Cashier/Scholarships/Scheduling', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'payout' => $payout,
            'disbursements' => $disbursements,
        ]);
    }

    // return Inertia::render('Cashier/Scholarships/Scheduling',[
    //     'scholarship' => $scholarship,
    //     'batches' => $batches,
    //     'payout' => $payout,
    //     'disbursements' => $disbursements,
    // ]);

    public function payout_batches(Payout $payout)
    {
        $user = Auth::user();

        // Get user campus IDs (null for admin, array for cashier and other roles)
        $userCampusIds = $user->usertype === 'admin'
            ? null
            : [$user->campus_id]; // Convert to array for non-admin users including cashiers

        $scholarship = Scholarship::findOrFail($payout->scholarship_id);

        // Get batches related to the scholarship, filtered by campus if needed
        $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $payout->school_year_id)
            ->where('semester', $payout->semester);

        if ($userCampusIds) {
            $batchesQuery->whereIn('campus_id', $userCampusIds);
        }

        $batches = $batchesQuery->with([
            'disbursement' => function ($query) use ($payout) {
                // Make sure we get disbursements for the active payout only
                $query->where('payout_id', $payout->id);
            },
            'campus:id,name'
        ])
            ->orderBy('batch_no')
            ->get();

        // Count claimed and not claimed for each batch
        $batches = $batches->map(function ($batch) use ($payout) {
            // Filter disbursements to only include those for the current active payout
            $disbursements = $batch->disbursement->filter(function ($disbursement) use ($payout) {
                return $disbursement->payout_id == $payout->id;
            });

            $claimed = $disbursements->where('status', 'Claimed')->count();
            $notClaimed = $disbursements->whereIn('status', ['Pending', 'Not Claimed'])->count();

            return array_merge($batch->toArray(), [
                'claimed_count' => $claimed,
                'not_claimed_count' => $notClaimed
            ]);
        });

        // Get active payout for this scholarship and campus
        $activePayout = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $payout->school_year_id)
            ->where('semester', $payout->semester)
            ->where('status', 'Active');

        if ($userCampusIds) {
            $activePayout->whereIn('campus_id', $userCampusIds);
        }

        $activePayout = $activePayout->with([
            'campus:id,name',
            'disbursement' => function ($query) {
                $query->with('scholar:id,first_name,last_name');
            }
        ])
            ->orderBy('created_at', 'desc')
            ->first();

        // If no active payout found, use the passed payout
        if (!$activePayout) {
            $activePayout = $payout->load([
                'campus:id,name',
                'disbursement.scholar:id,first_name,last_name'
            ]);
        }

        $canForward = $activePayout->total_scholars == $activePayout->sub_total;

        $allDisbursementsClaimed = $activePayout->disbursement->every(function ($disbursement) {
            return $disbursement->status === 'Claimed';
        });




        $payout_schedule = PayoutSchedule::where('payout_id', $activePayout->id)
            ->first();

        return Inertia::render('Cashier/Scholarships/Payout_Batches', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'payouts' => $activePayout,
            'user_campus_ids' => $userCampusIds ?? [],
            'user_type' => $user->usertype,
            'canForward' => $canForward,
            'payout_schedule' => $payout_schedule,
            'AllClaimed' => $allDisbursementsClaimed,
        ]);
    }

    public function forward_payout($scholarshipId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('status', '!=', 'Inactive')
            ->first();

        $payout->status = 'Inactive';
        $payout->save();

        $currentUser = Auth::user();

        $campus = Campus::where('id', $currentUser->campus_id)->first();

        // Create notification for coordinator
        $notification = Notification::create([
            'title' => $campus->name . ' Payout forward',
            'message' => $campus->name . ' campus, Payouts now finished',
            'type' => 'forward_payout',
        ]);

        $super_admin = User::where('usertype', 'head_cashier')->first();

        // Attach the coordinator to the notification
        $notification->users()->attach($super_admin->id);

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for scholar notification
        event(new NewNotification($notification));

        // Create notification for scholars
        $scholarNotification = Notification::create([
            'title' => 'Payout Done',
            'message' => $campus->name . 'Payouts are now finished',
            'type' => 'payout_done',
        ]);

        // Attach scholars to the notification
        // Notify users from the same campus as the current user
        $campusUsers = User::where('campus_id', Auth::user()->campus_id)->pluck('id');
        $scholarNotification->users()->attach($campusUsers);

        // Broadcast the notification
        broadcast(new NewNotification($scholarNotification))->toOthers();

        // Trigger event for scholar notification
        event(new NewNotification($scholarNotification));


        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Forward Payouts',
            'description' => 'User forwarded the payout to University Cashier',
        ]);


        return redirect()->route('cashier.active_scholarships')->with('success', 'Forwarded Successfully');
    }


    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);


        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->orderBy('batch_no', 'desc')
            ->with('school_year')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('status', '!=', 'Inactive')
            ->where('semester', $batch->semester)
            ->where('school_year_id', $batch->school_year_id)
            ->with('school_year')
            ->first();

        // Optimize query to reduce N+1 problem
        $disbursements = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->whereHas('scholar', function ($query) {
                $query->whereIn('student_status', ['Enrolled', 'Transferred']);
            })
            ->with([
                'scholar' => function ($subQuery) {
                    $subQuery->with(['course', 'campus', 'user']);
                }
            ])
            ->get();

        // Count total claimed disbursements
        $totalClaimed = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->where('status', 'Claimed') // Assuming 'claimed' is the status for claimed disbursements
            ->count();

        $payout_schedule = PayoutSchedule::where('payout_id', $payout->id)
            ->first();

        return Inertia::render('Cashier/Scholarships/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'disbursements' => $disbursements,
            'payout' => $payout,
            'totalClaimed' => $totalClaimed, // Pass the total claimed count to the view
            'payout_schedule' => $payout_schedule,
        ]);
    }

    public function submitReason(Request $request)
    {
        $validated = $request->validate([
            'disbursement_id' => 'required|exists:disbursements,id',
            'reason' => 'required|string|max:1000',
            'document' => 'nullable|file|mimes:docx,png,jpg,jpeg,pdf|max:5120', // 5MB max
        ]);

        $disbursement = Disbursement::findOrFail($validated['disbursement_id']);

        // Check if disbursement is pending
        if ($disbursement->status !== 'Pending') {
            return back()->with('error', 'Only pending disbursements can have reasons added.');
        }

        // Handle document upload if provided
        $documentPath = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $documentPath = $file->storeAs('disbursement-reasons', $fileName, 'public');

            // If you want to store document path in database, you need to add this column

            $disbursement->path = $documentPath;
            $disbursement->file_name = $fileName;
        }

        // Update the disbursement with reason
        $disbursement->reasons_of_not_claimed = $validated['reason'];



        // Update status to 'Not Claimed' since we now have a reason
        $disbursement->claimed_at = now();
        $disbursement->claimed_by = Auth::user()->id;
        $disbursement->status = 'Not Claimed';
        $disbursement->save();

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Set Reason',
            'description' => 'User set a reason for no claim',
        ]);

        return redirect()->back()->with('success', 'Reason submitted successfully');
    }

    public function manualClaim(Request $request, $scholarshipId, $batchId)
    {
        $validated = $request->validate([
            'disbursement_id' => 'required|exists:disbursements,id',
        ]);

        $disbursement = Disbursement::findOrFail($validated['disbursement_id']);
        $disbursement->status = 'Claimed';
        $disbursement->claimed_at = now();
        $disbursement->claimed_by = Auth::id();
        $disbursement->save();

        $payout = Payout::findOrFail($disbursement->payout_id);
        // Get the scholar to verify permissions
        $scholar = Scholar::findOrFail($disbursement->scholar_id);
        $grantee = Grantees::where('batch_id', $disbursement->batch_id)
            ->where('scholar_id', $scholar->id)
            ->first();
        $grantee->status = 'Accomplished';
        $grantee->save();

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Disbursement Claim',
            'description' => 'User confirmed disbursement claim for Scholar ID: ' . $scholar->urscholar_id,
        ]);

        // Update payout total claimed count
        $total_claimed = Disbursement::where('payout_id', $payout->id)
            ->where('status', 'Claimed')->count();

        $payout->update([
            'sub_total' => $total_claimed,
        ]);

        return back()->with('success', 'Disbursement manually claimed successfully.');
    }

    public function payrolls()
    {
        return Inertia::render('Cashier/Payrolls/Payout_Records');
    }


    public function getScholarInfo(Request $request)
    {
        $request->validate([
            'scholar_id' => 'required|string',
        ]);

        try {
            // Find the scholar using the ID
            $scholar = Scholar::where('urscholar_id', $request->scholar_id)
                ->with('campus')
                ->first();

            if (!$scholar) {
                return response()->json(['error' => 'Scholar not found'], 404);
            }

            // Get the authenticated user's campus
            $userCampus = Auth::user()->campus_id;

            // Check if user and scholar are from the same campus
            if ($userCampus != $scholar->campus_id) {
                return response()->json(['error' => 'You can only process scholars from your own campus'], 403);
            }

            // Retrieve the scholar's picture
            $scholarPicture = User::where('email', $scholar->email)->first();

            // Check if the scholar has a pending payout
            $disbursement = Disbursement::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first();

            if (!$disbursement) {
                return response()->json(['error' => 'No pending payout found for this scholar'], 404);
            }

            // Map the scholar's information for the response
            $scholarData = [
                'id' => $scholar->id,
                'urscholar_id' => $scholar->urscholar_id,
                'name' => $scholar->name,
                'last_name' => $scholar->last_name,
                'first_name' => $scholar->first_name,
                'email' => $scholar->email,
                'campus' => $scholar->campus->name ?? 'N/A',
                'picture' => $scholarPicture ? $scholarPicture->picture : null,
                'disbursement_id' => $disbursement->id,
            ];

            return response()->json(['scholar' => $scholarData]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving scholar information: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process the disbursement claim after confirmation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmClaim(Request $request)
    {
        $request->validate([
            'disbursement_id' => 'required|integer',
        ]);

        try {
            // Find the disbursement
            $disbursement = Disbursement::findOrFail($request->disbursement_id);

            // Get the scholar to verify permissions
            $scholar = Scholar::findOrFail($disbursement->scholar_id);

            // Check if user and scholar are from the same campus
            if (Auth::user()->campus_id != $scholar->campus_id) {
                return response()->json(['error' => 'You can only process scholars from your own campus'], 403);
            }

            // Check if disbursement is still pending
            if ($disbursement->status !== 'Pending') {
                return response()->json(['error' => 'Disbursement is not in pending status'], 400);
            }

            // Get the payout for updating totals
            $payout = Payout::findOrFail($disbursement->payout_id);
            $grantee = Grantees::where('batch_id', $disbursement->batch_id)
                ->where('scholar_id', $scholar->id)
                ->first();
            $grantee->status = 'Accomplished';
            $grantee->save();

            // Update disbursement status
            $disbursement->update([
                'claimed_at' => now(),
                'claimed_by' => Auth::user()->id,
                'status' => 'Claimed',
            ]);

            // Log the activity
            ActivityLog::create([
                'user_id' => Auth::user()->id,
                'activity' => 'Disbursement Claim',
                'description' => 'User confirmed disbursement claim for Scholar ID: ' . $scholar->urscholar_id,
            ]);

            // Update payout total claimed count
            $total_claimed = Disbursement::where('payout_id', $payout->id)
                ->where('status', 'Claimed')->count();

            $payout->update([
                'sub_total' => $total_claimed,
            ]);

            // Retrieve the scholar's picture
            $scholarPicture = User::where('email', $scholar->email)->first();

            // Return success response with updated scholar data
            return response()->json([
                'success' => true,
                'message' => 'Disbursement successfully claimed',
                'scholar' => [
                    'id' => $scholar->id,
                    'name' => $scholar->name,
                    'last_name' => $scholar->last_name,
                    'first_name' => $scholar->first_name,
                    'email' => $scholar->email,
                    'campus' => $scholar->campus->name ?? 'N/A',
                    'picture' => $scholarPicture ? $scholarPicture->picture : null,
                    'status' => 'Claimed',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing claim: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify QR code and prepare for confirmation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyQr(Request $request)
    {

        $request->validate([
            'scanned_data' => 'required|string',
        ]);

        try {
            // Decode the scanned QR data
            $scannedData = json_decode($request->scanned_data, true);

            // Log the received QR data for debugging
            logger()->info("Received QR data:", $scannedData ?? ['error' => 'Invalid JSON']);

            if (!$scannedData || !isset($scannedData['id'], $scannedData['timestamp'])) {
                return response()->json(['error' => 'Invalid QR Code format'], 400);
            }

            // Find the scholar using the ID from the scanned QR
            $scholar = Scholar::where('urscholar_id', $scannedData['id'])->with('campus')->first();

            if (!$scholar) {
                return response()->json(['error' => 'Scholar not found'], 404);
            }

            // Get the authenticated user's campus
            $userCampus = Auth::user()->campus_id;

            // Check if user and scholar are from the same campus
            if ($userCampus != $scholar->campus_id) {
                return response()->json(['error' => 'You can only scan scholars from your own campus'], 403);
            }

            // Retrieve the scholar's picture
            $scholarPicture = User::where('email', $scholar->email)->first();

            // Check if the QR code filename matches the expected format
            $expectedQrFilename = $scholar->urscholar_id . '.png';

            if ($scholar->qr_code !== $expectedQrFilename) {
                return response()->json(['error' => 'QR Code does not match scholar records'], 400);
            }

            // Verify the QR Code matches the stored one
            if (!Storage::disk('public')->exists('qr_codes/' . $expectedQrFilename)) {
                return response()->json(['error' => 'QR Code file not found'], 404);
            }

            // Verify timestamp is not too old
            $qrTimestamp = Carbon::createFromTimestamp($scannedData['timestamp']);
            $currentTime = Carbon::now();

            if ($currentTime->diffInHours($qrTimestamp) > 24) {
                return response()->json(['error' => 'QR Code has expired'], 400);
            }

            // QR is valid, now check if the scholar has a pending payout
            $disbursement = Disbursement::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first();

            if (!$disbursement) {
                return response()->json(['error' => 'No pending payout found for this scholar'], 404);
            }

            // Return scholar data for confirmation
            $scholarData = [
                'id' => $scholar->id,
                'urscholar_id' => $scholar->urscholar_id,
                'name' => $scholar->name,
                'last_name' => $scholar->last_name,
                'first_name' => $scholar->first_name,
                'email' => $scholar->email,
                'campus' => $scholar->campus->name ?? 'N/A',
                'picture' => $scholarPicture ? $scholarPicture->picture : null,
                'disbursement_id' => $disbursement->id,
            ];

            return response()->json([
                'success' => true,
                'scholar' => $scholarData,
                'require_confirmation' => true
            ]);

        } catch (\Exception $e) {
            logger()->error('QR verification error: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing QR code: ' . $e->getMessage()], 500);
        }
    }

    public function payouts_index()
    {
        $scholarships = Scholarship::all();

        if (Auth::user()->usertype == 'head_cashier') {
            // Get payouts with date information
            $payouts = Payout::all();

            // Get batches with school year and campus information
            $batches = Batch::with(['school_year', 'campus'])
                ->get();
        } else {
            // Get payouts with date information
            $payouts = Payout::where('campus_id', Auth::user()->campus_id)->get();

            // Get batches with school year and campus information
            $batches = Batch::with(['school_year', 'campus'])
                ->where('campus_id', Auth::user()->campus_id)
                ->get();
        }


        // Get all disbursements to track claims
        $disbursements = Disbursement::all();

        // Get scheduled payout dates
        $payout_schedule = PayoutSchedule::all();

        $academic_years = AcademicYear::all();
        $campuses = Campus::all();

        return Inertia::render('Cashier/Payrolls/Payout_Records', [
            'scholarships' => $scholarships,
            'payouts' => $payouts,
            'batches' => $batches,
            'disbursements' => $disbursements,
            'payout_schedule' => $payout_schedule,
            'academic_years' => $academic_years,
            'campuses' => $campuses,
        ]);
    }

    public function payouts_disbursement($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->with('school_year')
            ->orderBy('batch_no', 'desc')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('school_year_id', $batch->school_year_id)
            ->where('semester', $batch->semester)
            ->where('campus_id', $batch->campus_id)
            ->first();

        $selectedSem = $batch->semester;

        dd($payout);


        // Optimize query to reduce N+1 problem
        $disbursements = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->with([
                'scholar' => function ($subQuery) {
                    $subQuery->with(['course', 'campus', 'user']);
                }
            ])
            ->get();

        $schoolyear = SchoolYear::where('id', $batch->school_year_id)->first();
        // Count total claimed disbursements
        $totalClaimed = Disbursement::where('payout_id', $payout->id)
            ->where('batch_id', $batchId)
            ->where('status', 'claimed') // Assuming 'claimed' is the status for claimed disbursements
            ->count();

        return Inertia::render('Cashier/Payrolls/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'disbursements' => $disbursements,
            'payout' => $payout,
            'schoolyear' => $schoolyear,
            'selectedSem' => $selectedSem,
            'totalClaimed' => $totalClaimed, // Pass the total claimed count to the view
        ]);
    }

    public function pending_payouts(Request $request, $scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year_id', $year);
            })
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->orderBy('batch_no', 'desc')
            ->with('school_year')
            ->first();


        $schoolyear = SchoolYear::where('id', $batch->school_year_id)->first();

        $grantees = $scholarship->grantees()
            ->whereIn('status', ['Active', 'Pending', 'Accomplished'])
            ->where('batch_id', $batch->id)
            ->where('semester', $request->input('selectedSem'))
            ->with('scholar.campus', 'scholar.course')
            ->get();

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year_id', $year);
            })
            ->when($request->input('selectedSem'), fn($q, $sem) => $q->where('semester', $sem))
            ->where('campus_id', $batch->campus_id) // Filter by campus
            ->with('school_year')
            ->first();

        $selectedYear = $request->input('selectedYear');
        $selectedSem = $request->input('selectedSem');



        $scholars = $grantees->map(function ($grantee) use ($payout) {
            // Skip if there's no related scholar
            if (!$grantee->scholar) {
                return null;
            }

            $scholar = $grantee->scholar;

            $userPicture = User::where('id', $scholar->user_id)
                ->first();

            if ($payout) {
                $disbursement = Disbursement::where('scholar_id', $scholar->id)
                    ->where('payout_id', $payout->id)
                    ->first();
            }


            if (!$userPicture) {
                $userPicture = null;
            }

            return [
                'id' => $scholar->id,
                'picture' => $userPicture,
                'urscholar_id' => $scholar->urscholar_id,
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'middle_name' => $scholar->middle_name,
                'campus' => $scholar->campus->name ?? 'N/A',
                'course' => $scholar->course->name ?? 'N/A',
                'year_level' => $scholar->year_level,
                'grant' => $scholar->grant,
                'scholar_status' => $scholar->status,
                'student_status' => $grantee->student_status,
                'claimed_time' => $disbursement->claimed_at ?? null,
                'claimed_status' => $disbursement->status ?? null,
                'user' => [
                    'picture' => $scholar->user->picture ?? null
                ],
            ];
        });

        if ($payout) {
            // Count total claimed disbursements
            $totalClaimed = Disbursement::where('payout_id', $payout->id)
                ->where('batch_id', $batchId)
                ->where('status', 'Claimed') // Assuming 'claimed' is the status for claimed disbursements
                ->count();

            $payout_schedule = PayoutSchedule::where('payout_id', $payout->id)
                ->first();
        }


        return Inertia::render('Cashier/Scholarships/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'scholars' => $scholars,
            'disbursements' => $scholars,
            'payout' => $payout,
            'schoolyear' => $schoolyear,
            'totalClaimed' => $totalClaimed ?? null,
            'payout_schedule' => $payout_schedule ?? null,
            'selectedSem' => $selectedSem,
        ]);
    }
}

