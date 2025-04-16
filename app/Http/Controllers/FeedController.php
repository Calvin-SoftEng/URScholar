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
use App\Models\Scholar;
use App\Models\User;
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
        // Get the authenticated user's scholar information
        $user = Auth::user();
        $scholar = Scholar::where('user_id', $user->id)->first();

        if (!$scholar) {
            return Inertia::render('Student/Communication/Feed', [
                'scholarships' => Scholarship::all(),
                'batches' => Batch::with('scholarship')->get(),
                'campuses' => Campus::all(),
                'posts' => []
            ]);
        }

        // Get the grantee records for this scholar
        $grantees = Grantees::where('scholar_id', $scholar->id)->get();

        // Collect the specific batch IDs and their associated campus IDs that this scholar belongs to
        $scholarBatchCampusPairs = [];
        foreach ($grantees as $grantee) {
            // Get the batch
            $batch = Batch::find($grantee->batch_id);
            if ($batch) {
                // Store batch ID and its associated campus ID
                $scholarBatchCampusPairs[] = [
                    'batch_id' => $batch->id,
                    'campus_id' => $batch->campus_id
                ];
            }
        }

        // Find pages that match the scholar's batch AND campus combinations
        $pages = Page::get()->filter(function ($page) use ($scholarBatchCampusPairs) {
            // Decode JSON arrays
            $pageBatchIds = json_decode($page->batch_ids);
            $pageCampusIds = json_decode($page->campus_ids);

            // Check for a match in any of the scholar's batch-campus pairs
            foreach ($scholarBatchCampusPairs as $pair) {
                // This page is relevant if it contains both the batch_id and its associated campus_id
                if (in_array($pair['batch_id'], $pageBatchIds) && in_array($pair['campus_id'], $pageCampusIds)) {
                    return true;
                }
            }
            return false;
        });

        // Format the pages data for the Vue component
        $formattedPosts = $pages->map(function ($page) {
            // Find the latest posting for this page
            $posting = Posting::where('page_id', $page->id)
                ->latest()
                ->first();

            // Get user who created the posting
            $user = $posting ? User::find($posting->user_id) : User::find($page->user_id);

            // Decode JSON arrays
            $scholarshipIds = json_decode($page->scholarship_ids);
            $batchIds = json_decode($page->batch_ids);
            $campusIds = json_decode($page->campus_ids);

            // Get scholarship names
            $scholarships = Scholarship::whereIn('id', $scholarshipIds)->pluck('name')->toArray();

            // Get batch details
            $batches = Batch::whereIn('id', $batchIds)->get()->map(function ($batch) {
                return [
                    'name' => 'Batch ' . $batch->batch_no,
                    'id' => $batch->id
                ];
            })->toArray();

            // Get campus names
            $campuses = Campus::whereIn('id', $campusIds)->pluck('name')->toArray();

            return [
                'id' => $page->id,
                'content' => $posting ? $posting->content : 'No content',
                'created_at' => $posting ? $posting->created_at->diffForHumans() : $page->created_at->diffForHumans(),
                'user' => [
                    'name' => $user->name ?? 'Unknown User',
                    'id' => $user->id
                ],
                'filters' => [
                    'scholarships' => $scholarships,
                    'batches' => $batches,
                    'campuses' => $campuses
                ]
            ];
        });

        return Inertia::render('Student/Communication/Feed', [
            'scholarships' => Scholarship::all(),
            'batches' => Batch::with('scholarship')->get(),
            'campuses' => Campus::all(),
            'posts' => $formattedPosts
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