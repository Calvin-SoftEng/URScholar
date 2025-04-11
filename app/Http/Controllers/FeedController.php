<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\Batch;
use App\Models\Campus;
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