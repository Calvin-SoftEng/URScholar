<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Grantees;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Campus;
use App\Models\Notification;
use App\Models\Page;
use App\Models\Posting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FeedController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        $batches = Batch::with('scholarship')->get();
        $campuses = Campus::all();

        return Inertia::render('Staff/Communication/Feed', [
            'scholarships' => $scholarships,
            'batches' => $batches,
            'campuses' => $campuses,
        ]);
    }

    public function grantee_feed()
    {
        $scholarships = Scholarship::all();
        $batches = Batch::with('scholarship')->get();
        $campuses = Campus::all();

        return Inertia::render('Student/Communication/Feed', [
            'scholarships' => $scholarships,
            'batches' => $batches,
            'campuses' => $campuses,
        ]);
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'scholarship_ids' => 'nullable|array',
            'batch_ids' => 'nullable|array',
            'campus_ids' => 'nullable|array',
        ]);

        // Create a page that stores the filter criteria
        $page = Page::create([
            'user_id' => Auth::id(),
            'scholarship_ids' => json_encode($request->scholarship_ids ?? []),
            'batch_ids' => json_encode($request->batch_ids ?? []),
            'campus_ids' => json_encode($request->campus_ids ?? []),
        ]);

        // Create the actual post associated with this page
        $posting = Posting::create([
            'page_id' => $page->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Query to find all relevant users based on the provided filters
        $userIds = collect();

        // If campus_ids provided, get users who are coordinators or cashiers for these campuses
        if (!empty($request->campus_ids)) {
            $campusStaffIds = Campus::whereIn('id', $request->campus_ids)
                ->whereNotNull('coordinator_id')
                ->orWhereNotNull('cashier_id')
                ->get()
                ->flatMap(function ($campus) {
                    return array_filter([$campus->coordinator_id, $campus->cashier_id]);
                });

            $userIds = $userIds->merge($campusStaffIds);
        }

        // If scholarship_ids provided, get scholars via the grantee relationship
        if (!empty($request->scholarship_ids)) {
            $scholarUserIds = Grantees::whereIn('scholarship_id', $request->scholarship_ids)
                ->join('scholars', 'grantees.scholar_id', '=', 'scholars.id')
                ->whereNotNull('scholars.user_id')
                ->pluck('scholars.user_id')
                ->unique();

            $userIds = $userIds->merge($scholarUserIds);
        }

        // If batch_ids provided, get scholars via the grantee relationship
        if (!empty($request->batch_ids)) {
            $batchUserIds = Grantees::whereIn('batch_id', $request->batch_ids)
                ->join('scholars', 'grantees.scholar_id', '=', 'scholars.id')
                ->whereNotNull('scholars.user_id')
                ->pluck('scholars.user_id')
                ->unique();

            $userIds = $userIds->merge($batchUserIds);
        }

        // Remove duplicates and get the actual User models
        $userIds = $userIds->unique()->values();

        // Only continue if we have users to notify
        if ($userIds->isNotEmpty()) {
            // Create notification
            $notification = Notification::create([
                'title' => 'New Message',
                'message' => 'New post posted',
                'type' => $request->group_type . '_chat',
            ]);

            // Attach users to the notification
            $notification->users()->attach($userIds);

            // Notify users
            event(new NewNotification($notification));
        }

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }

    public function getPosts()
    {
        $posts = Posting::with(['page', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) {
                // Get the scholarship, batch, and campus details
                $scholarshipIds = json_decode($post->page->scholarship_ids, true) ?? [];
                $batchIds = json_decode($post->page->batch_ids, true) ?? [];
                $campusIds = json_decode($post->page->campus_ids, true) ?? [];

                // Fetch the actual names instead of just IDs
                $scholarships = Scholarship::whereIn('id', $scholarshipIds)
                    ->pluck('name')
                    ->toArray();

                // For batches, get both batch_no and related scholarship name
                $batches = Batch::with('scholarship')
                    ->whereIn('id', $batchIds)
                    ->get()
                    ->map(function ($batch) {
                    return [
                        'name' => $batch->name ?? "Batch {$batch->batch_no}",
                        'scholarship' => $batch->scholarship ? $batch->scholarship->name : 'Unknown Scholarship'
                    ];
                })
                    ->toArray();

                $campuses = Campus::whereIn('id', $campusIds)
                    ->pluck('name')
                    ->toArray();

                return [
                    'id' => $post->id,
                    'content' => $post->content,
                    'user' => [
                        'name' => $post->user->name,
                        'avatar' => $post->user->profile_photo_path ?? null,
                    ],
                    'filters' => [
                        'scholarships' => $scholarships,
                        'batches' => $batches,
                        'campuses' => $campuses,
                    ],
                    'created_at' => $post->created_at->format('M. d, Y @ h:i A'),
                ];
            });

        return response()->json(['posts' => $posts]);
    }
}