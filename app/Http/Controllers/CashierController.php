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
            'scholars' => $latestSubmissions,
            'active_scholars' => $active_scholars,
            'activity_logs' => $activity_logs,
            'academic_year' => $academic_year,
            'univ_students' => $univ_students,
        ]);
    }

    public function scholarships()
    {
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
            ->with('scholar.grantees.batch') // Eager load grantees and their batch for each scholar
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

        $super_admin = User::where('usertype', 'super_admin')->first();

        // Attach the coordinator to the notification
        $notification->users()->attach($super_admin->id);

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Forward Payouts',
            'description' => 'User forwarded the payout to Head Administrator',
        ]);


        return redirect()->route('cashier.active_scholarships')->with('success', 'Forwarded Successfully');
    }


    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $payout = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('status', '!=', 'Inactive')
            ->with('school_year')
            ->first();

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->orderBy('batch_no', 'desc')
            ->with('school_year')
            ->firstOrFail(); // Use firstOrFail to handle cases where batch doesn't exist

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
            $fileName = time() . '_' . $file->getClientOriginalName();
            $documentPath = $file->storeAs('disbursement-reasons', $fileName, 'public');

            // If you want to store document path in database, you need to add this column
            $disbursement->path = $documentPath;
            $disbursement->file_name = $fileName;
        }

        // Update the disbursement with reason
        $disbursement->reasons_of_not_claimed = $validated['reason'];



        // Update status to 'Not Claimed' since we now have a reason
        $disbursement->status = 'Not Claimed';
        $disbursement->save();

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Set Reason',
            'description' => 'User set a reason for no claim',
        ]);

        return redirect()->back()->with('success', 'Reason submitted successfully');
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

        // Get payouts with date information
        $payouts = Payout::where('campus_id', Auth::user()->campus_id)->get();

        // Get batches with school year and campus information
        $batches = Batch::with(['school_year', 'campus'])
        ->where('campus_id', Auth::user()->campus_id)
        ->get();

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

        return Inertia::render('Cashier/Payrolls/Payouts', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'disbursements' => $disbursements,
            'payout' => $payout,
            'totalClaimed' => $totalClaimed, // Pass the total claimed count to the view
        ]);
    }
}

