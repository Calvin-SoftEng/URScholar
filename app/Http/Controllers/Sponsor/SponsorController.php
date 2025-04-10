<?php

namespace App\Http\Controllers\Sponsor;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Payout;
use App\Models\SchoolYear;
use App\Models\Requirements;
use App\Models\SubmittedRequirements;
use App\Models\PayoutSchedule;
use App\Models\Disbursement;
use App\Models\User;
use App\Models\Batch;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function sponsor_dashboard()
    {
        //Scholarships Listing
        $sponsor = Sponsor::where('assign_id', Auth::user()->id)
            ->first();

        $scholarship = $sponsor->scholarship;

        // Disbursement Listing
        $payout = Payout::whereIn('scholarship_id', $sponsor->scholarship->pluck('id'))
            ->with(['campus'])
            ->get();

        $school_year = SchoolYear::with('academic_year')
            ->orderBy('id', 'asc')  // Sort by ID in ascending order (assuming lower IDs are older years)
            ->get();

        return Inertia::render('Sponsor/Dashboard', [
            'sponsor' => $sponsor,
            'scholarships' => $scholarship,
            'payouts' => $payout,  // Don't forget to pass this to your view
            'schoolyears' => $school_year,
        ]);

    }

    public function index()
    {
        $sponsors = Sponsor::with('scholarship')->get();
        $campuses = Campus::all();
        $courses = Course::all();


        return inertia('Staff/Scholarships/Index', [
            'sponsors' => $sponsors,
            'campuses' => $campuses,
            'courses' => $courses,
        ]);
    }

    public function show(Sponsor $sponsor)
    {
        $scholarship = $sponsor->scholarship;

        $campuses = Campus::all();
        $courses = Course::all();

        return Inertia::render('Staff/Scholarships/CreateScholarships', [
            'sponsors' => $sponsor,
            'scholarships' => $scholarship,
            'campuses' => $campuses,
            'courses' => $courses,
        ]);
    }

    public function create(Sponsor $sponsor)
    {

        return Inertia::render('Staff/Scholarships/CreateSponsor', [
            'sponsor' => $sponsor,
        ]);

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file',
            'description' => 'required|string',
            'img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'imgName' => 'required|string',
            'abbreviation' => 'required|string|max:255',
            'since' => 'required|string|max:255',
        ]);



        // Store the logo file in the local directory with a known path
        $logoFile = $request->file('img');
        // $logoFileName = $request->imgName;
        $originalFileName = $logoFile->getClientOriginalName();
        Storage::disk('public')->putFileAs('sponsor/logo', $logoFile, $originalFileName);

        // Store the MOA file
        $filePath = $request->file('file')->store('sponsor/moa', 'public');


        // dd($originalFileName);
        // Save sponsor record in the database
        Sponsor::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'since' => $request->since,
            'moa_file' => $filePath,
            'description' => $request->description,
            'logo' => $originalFileName, // Save only the filename in the database
        ]);

        return redirect()->route('sponsor.index')->with('success', 'Sponsor added successfully!');
    }

    public function view_scholars(Request $request, $scholarshipId)
    {
        // Get the scholarship
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get the authenticated user
        $user = Auth::user();

        // Get user campus IDs (null for admin, array for cashier and other roles)
        $userCampusIds = $user->usertype === 'admin'
            ? null
            : [$user->campus_id]; // Convert to array for non-admin users including cashiers

        // Get selected filters
        $selectedSemester = $request->input('selectedSem');
        $selectedYearId = $request->input('selectedYear');

        // Set up payout query for this scholarship, filtered by campus if needed
        $payoutsQuery = Payout::where('scholarship_id', $scholarship->id)
            ->where('semester', $selectedSemester)
            ->where('school_year_id', $selectedYearId)
            ->where('status', '!=', ['Active, Pending']);

        if ($userCampusIds) {
            $payoutsQuery->whereIn('campus_id', $userCampusIds);
        }

        // Get the latest payout
        $payout = $payoutsQuery->with([
            'campus:id,name',
            'disbursement.scholar:id,first_name,last_name'
        ])
            ->orderBy('created_at', 'desc')
            ->first();

        // Process payout information
        $processedPayouts = null;
        $canForward = false;
        $payout_schedule = null;

        if ($payout) {
            $canForward = $payout->total_scholars == $payout->sub_total;
            $payout_schedule = PayoutSchedule::where('payout_id', $payout->id)->first();

            $processedPayouts = [
                'payout' => $payout,
                'canForward' => $canForward,
                'payout_schedule' => $payout_schedule
            ];
        }

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        // Get all batches for this scholarship
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->when($selectedYearId, function ($query, $year) {
                return $query->where('school_year_id', $year);
            })
            ->where('status', '!=', ['Active, Pending'])
            ->orderBy('batch_no', 'desc')
            ->with(['school_year', 'disbursement', 'campus:id,name'])
            ->get();

        // Process all batches and their scholars
        $processedBatches = $batches->map(function ($batch) use ($scholarship, $totalRequirements, $selectedSemester, $selectedYearId) {
            // Get active grantees from this batch, filtered by semester
            $grantees = $scholarship->grantees()
                ->where('batch_id', $batch->id)
                ->when($selectedSemester, function ($query, $semester) {
                    return $query->where('semester', $semester);
                })
                ->when($selectedYearId, function ($query, $yearId) {
                    return $query->where('school_year_id', $yearId);
                })
                ->with('scholar.campus', 'scholar.course', 'school_year')
                ->get();

            // Count scholars with complete submissions
            $completeSubmissionsCount = 0;

            // Get disbursements for this batch
            $disbursements = Disbursement::where('batch_id', $batch->id)->get();

            // Count claimed and not claimed disbursements
            $claimed = $disbursements->where('status', 'Claimed')->count();
            $notClaimed = $disbursements->whereIn('status', ['Pending', 'Not Claimed'])->count();

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

                if ($scholar->user_id !== null) {
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
                        'semester' => $grantee->semester, // Include semester from grantee
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
                        'status' => $status,
                        'submittedRequirements' => $approvedRequirements,
                        'totalRequirements' => $totalRequirements,
                        'progress' => $progress,
                        'user' => [
                            'picture' => $scholar->user->picture ?? null // Include user picture
                        ],
                        'semester' => $grantee->semester, // Include semester from grantee
                    ];
                }

            })->filter()->values();

            // Update the batch to mark as read
            $batch->update([
                'read' => 1
            ]);

            return [
                'batch' => $batch,
                'scholars' => $scholars,
                'completeSubmissionsCount' => $completeSubmissionsCount,
                'claimed_count' => $claimed,
                'not_claimed_count' => $notClaimed,
                'filtered_semester' => $selectedSemester,  // Include selected semester
            ];
        });

        return Inertia::render('Sponsor/Scholars/ScholarsTable', [
            'scholarship' => $scholarship,
            'processedBatches' => $processedBatches,
            'payout' => $payout,
            'processedPayouts' => $processedPayouts,
            'requirements' => $requirements,
            'schoolyear' => $selectedYearId
                ? SchoolYear::find($selectedYearId)
                : null,
            'selectedSem' => $selectedSemester,
            'user_campus_ids' => $userCampusIds ?? [],
            'user_type' => $user->usertype, // Added user type for frontend access control
        ]);
    }

    public function scholar_description()
    {

        return Inertia::render('Sponsor/Scholars/Scholar_Scholarship-Details');

        // return Inertia::render('Coordinator/Scholarships/CreateScholarships');
    }
}
