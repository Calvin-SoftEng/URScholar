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
use App\Models\User;
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
        return Inertia::render('Cashier/Dashboard');
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
        // Get batches related to the grantees for the specific scholarship
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->with('grantees') // Eager load grantees for the batches
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
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

    public function payout_batches(Scholarship $scholarship)
    {
        $user = Auth::user();

        // Get user campus IDs (null for admin, array for cashier and other roles)
        $userCampusIds = $user->usertype === 'admin'
            ? null
            : [$user->campus_id]; // Convert to array for non-admin users including cashiers

        // Get batches related to the scholarship, filtered by campus if needed
        $batchesQuery = Batch::where('scholarship_id', $scholarship->id);

        if ($userCampusIds) {
            $batchesQuery->whereIn('campus_id', $userCampusIds);
        }

        $batches = $batchesQuery->with([
            'scholars' => function ($query) {
                $query->orderBy('last_name')
                    ->orderBy('first_name');
            },
            'disbursement',
            'campus:id,name'
        ])
            ->orderBy('batch_no')
            ->get();

        // Count claimed and not claimed for each batch
        $batches = $batches->map(function ($batch) {
            $claimed = $batch->disbursement->where('status', 'Claimed')->count();
            $notClaimed = $batch->disbursement->whereIn('status', ['Pending', 'Not Claimed'])->count();

            return array_merge($batch->toArray(), [
                'claimed_count' => $claimed,
                'not_claimed_count' => $notClaimed
            ]);
        });

        // Fetch grantees only if batches exist
        $grantees = collect();
        if ($batches->isNotEmpty()) {
            $grantees = $scholarship->grantees()
                ->whereIn('batch_id', $batches->pluck('id'))
                ->with('scholar.campus', 'scholar.course')
                ->get();
        }

        // Get payouts for this scholarship, filtered by campus if needed
        $payoutsQuery = Payout::where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->where('status', '!=', 'Inactive');

        if ($userCampusIds) {
            $payoutsQuery->whereIn('campus_id', $userCampusIds);
        }

        $payouts = $payoutsQuery->with([
            'campus:id,name',
            'disbursement.scholar:id,first_name,last_name'
        ])
            ->orderBy('created_at', 'desc')
            ->first();

        $canForward = $payouts->total_scholars == $payouts->sub_total;


        

        $payout_schedule = PayoutSchedule::where('payout_id', $payouts->id)
        ->first();

        return Inertia::render('Cashier/Scholarships/Payout_Batches', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'grantees' => $grantees,
            'payouts' => $payouts,
            'user_campus_ids' => $userCampusIds ?? [],
            'user_type' => $user->usertype, // Added user type for frontend access control
            'canForward' => $canForward,
            'payout_schedule' => $payout_schedule,
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

        return redirect()->route('cashier.active_scholarships')->with('success', 'Forwarded Successfully');
    }


    public function student_payouts($scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        $payout = Payout::where('scholarship_id', $scholarship->id)
        ->where('campus_id', Auth::user()->campus_id) // Filter by campus
        ->where('status', '!=', 'Inactive')
        ->first();

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->where('campus_id', Auth::user()->campus_id) // Filter by campus
            ->orderBy('batch_no', 'desc')
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
        }

        // Update the disbursement with reason
        $disbursement->reasons_of_not_claimed = $validated['reason'];

        // If you want to store document path in database, you need to add this column
        $disbursement->path = $documentPath;
        $disbursement->file_name = $fileName;

        // Update status to 'Not Claimed' since we now have a reason
        $disbursement->status = 'Not Claimed';
        $disbursement->save();

        return redirect()->back()->with('success', 'Reason submitted successfully');
    }

    public function payouts()
    {
        return Inertia::render('Cashier/Scholarships/Payouts');
    }


    public function verify_qr(Request $request)
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
                return back()->withErrors(['message' => 'Invalid QR Code format']);
            }

            // Find the scholar using the ID from the scanned QR
            $scholar = Scholar::where('urscholar_id', $scannedData['id'])->with('campus')->first();

            if (!$scholar) {
                return back()->withErrors(['message' => 'Scholar not found']);
            }

            // Retrieve the scholar's picture
            $scholarPicture = User::where('email', $scholar->email)->first();


            // Check if the QR code filename matches the expected format
            $expectedQrFilename = $scholar->urscholar_id . '.png';

            if ($scholar->qr_code !== $expectedQrFilename) {
                return back()->withErrors(['message' => 'QR Code does not match scholar records']);
            }

            // Verify the QR Code matches the stored one (no hash checking needed now)
            if (!Storage::disk('public')->exists('qr_codes/' . $expectedQrFilename)) {
                return back()->withErrors(['message' => 'QR Code file not found']);
            }

            // Optional: Verify timestamp is not too old (e.g., 24 hours)
            $qrTimestamp = Carbon::createFromTimestamp($scannedData['timestamp']);
            $currentTime = Carbon::now();

            if ($currentTime->diffInHours($qrTimestamp) > 24) {
                return back()->withErrors(['message' => 'QR Code has expired']);
            }

            // QR is valid, now check if the scholar has a pending payout
            $disbursement = Disbursement::where('scholar_id', $scholar->id)
                ->where('status', 'Pending')
                ->first();

            if (!$disbursement) {
                return back()->withErrors(['message' => 'No pending payout found for this scholar']);
            } else {
                $payout = Payout::where('id', $disbursement->payout_id)->first();


                $disbursement->update([
                    'claimed_at' => now(),
                    'claimed_by' => Auth::user()->id,
                    'status' => 'Claimed',
                ]);

                $total_claimed = Disbursement::where('payout_id', $payout->id)
                    ->where('status', 'Claimed')->count();

                $payout->update([
                    'sub_total' => $total_claimed,
                ]);

            }

            // Map the scholar's information for the response
            $scholarData = [
                'id' => $scholar->id,
                'name' => $scholar->name,
                'last_name' => $scholar->last_name,
                'first_name' => $scholar->first_name,
                'email' => $scholar->email,
                'campus' => $scholar->campus->name ?? 'N/A', // Assuming campus has a 'name' attribute
                'picture' => $scholarPicture->picture,
                'status' => $disbursement ? $disbursement->status : 'No payout',
            ];

            // Return the scholar with their picture
            return back()->with([
                'success' => $scholarData,
                'scholarPicture' => $scholarPicture
            ]);

        } catch (\Exception $e) {
            logger()->error('QR verification error: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Error processing QR code: ' . $e->getMessage()]);
        }
    }

    public function messaging(User $user)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Return the chat page using Inertia
        return Inertia::render('Cashier/Communication/Communication', [
            'messages' => [],
            'currentUser' => $currentUser,
            'scholarships' => $scholarships,
            'selectedScholarship' => [],
        ]);
    }

    public function show(Scholarship $scholarship)
    {

        // Get all messages with the user who sent them (eager loading)
        $messages = Message::with(['user', 'scholarship'])
            ->where('scholarship_id', $scholarship->id)
            ->latest()
            ->get();

        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            }
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        $selectedScholarship = $scholarship;


        // Return the chat page using Inertia, passing the messages and user data
        return Inertia::render('Cashier/Messaging/Messaging', [
            'messages' => $messages,
            'currentUser' => Auth::user(),
            'scholarships' => $scholarships,
            'selectedScholarship' => $selectedScholarship,
        ]);
    }

    public function oldstore(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
            'scholarship_id' => 'required'
        ]);

        // dd($request);
        $user = Auth::user()->id;

        $message = Message::create([
            'user_id' => $user,
            'scholarship_id' => $request->scholarship_id,
            'content' => $request->content,
        ]);

        // MessageSent::dispatch($message);
        broadcast(new MessageSent($message))->toOthers();

        //Notifs
        // $user = Auth::user();

        // $notification = Notification::create([
        //     'creator_id' => $user->id,
        //     'title' => 'New Message',
        //     'message' => 'May nag text ngani ' . now()->format('H:i:s'),
        //     'type' => 'info',
        // ]);

        $scholarship = Scholarship::find($request->scholarship_id);

        $notification = Notification::create([
            'title' => 'New Group Chat Message!',
            'message' => 'You have a new message in the group chat' . $scholarship->name,
            'type' => 'group_chat',
        ]);

        $scholarshipId = $scholarship->id;

        // Get users who belong to the specified scholarship group
        $users = User::whereIn('id', function ($query) use ($scholarshipId) {
            $query->select('user_id')
                ->from('scholarship_groups')
                ->where('scholarship_id', $scholarshipId);
        })
            ->where('id', '!=', Auth::user()->id) // Add this line to exclude the current user
            ->get();

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));


        event(new NewNotification($notification));

        return back();
    }
}

