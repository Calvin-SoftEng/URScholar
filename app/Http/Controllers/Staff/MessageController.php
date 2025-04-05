<?php

namespace App\Http\Controllers\Staff;

use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Scholarship;
use App\Events\NewMessage;
use App\Events\MessageSent;
use App\Models\Notification;
use App\Models\ScholarshipGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;



class MessageController extends Controller
{
    // public function conversation()
    // {
    //     return Inertia::render('Coordinator/Messaging/Messaging');
    // }
    public function index(User $user)
    {
        // Get the authenticated user
        $currentUser = Auth::user();

        // Get scholarships with relationships that the current user has
        $scholarships = Scholarship::with([
            'latestMessage.user',
            'users' => function ($query) {
                $query->select('users.id', 'users.name');
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            'scholarshipGroups.batch',
            'scholarshipGroups.campus'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        // Organize scholarships by batch and campus
        $organizedScholarships = [];

        foreach ($scholarships as $scholarship) {
            $scholarshipData = [
                'id' => $scholarship->id,
                'name' => $scholarship->name,
                'description' => $scholarship->description,
                'status' => $scholarship->status,
                'latest_message' => $scholarship->latestMessage,
                'users_count' => $scholarship->users_count,
                'batches' => []
            ];

            // Group scholarship groups by batch
            $groupedByBatch = $scholarship->scholarshipGroups->groupBy('batch_id');

            foreach ($groupedByBatch as $batchId => $batchGroups) {
                if (!$batchId)
                    continue; // Skip if batch_id is null

                $batch = $batchGroups->first()->batch;
                if (!$batch)
                    continue; // Skip if batch not found

                $batchData = [
                    'id' => $batch->id,
                    'batch_no' => $batch->batch_no,
                    'semester' => $batch->semester,
                    'school_year_id' => $batch->school_year_id,
                    'total_scholars' => $batch->total_scholars ?? 0,
                    'campuses' => []
                ];

                // Group by campus within this batch
                $groupedByCampus = $batchGroups->groupBy('campus_id');

                foreach ($groupedByCampus as $campusId => $campusGroups) {
                    if (!$campusId)
                        continue; // Skip if campus_id is null

                    $campus = $campusGroups->first()->campus;
                    if (!$campus)
                        continue; // Skip if campus not found

                    $batchData['campuses'][] = [
                        'id' => $campus->id,
                        'name' => $campus->name,
                        'group_id' => $campusGroups->first()->id // Include the scholarship_group id for reference
                    ];
                }

                if (count($batchData['campuses']) > 0) {
                    $scholarshipData['batches'][] = $batchData;
                }
            }

            if (count($scholarshipData['batches']) > 0) {
                $organizedScholarships[] = $scholarshipData;
            }
        }

        // Return the chat page using Inertia
        return Inertia::render('Staff/Communication/Communication', [
            'messages' => [],
            'currentUser' => $currentUser,
            'scholarships' => $scholarships, // Original scholarships data
            'organizedScholarships' => $organizedScholarships, // New organized structure
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
            },
            'scholarshipGroups' => function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            },
            'scholarshipGroups.batch',
            'scholarshipGroups.campus'
        ])
            ->whereHas('users', function ($query) use ($currentUser) {
                $query->where('users.id', $currentUser->id);
            })
            ->withCount('users')
            ->get();

        $selectedScholarship = $scholarship;


        // Return the chat page using Inertia, passing the messages and user data
        return Inertia::render('Staff/Messaging/Messaging', [
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
            'scholarship_id' => 'required',
            'batch_id' => 'required',
        ]);

        dd($request);
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
