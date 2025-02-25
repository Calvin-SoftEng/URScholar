<?php

namespace App\Http\Controllers\Staff;

use Inertia\Inertia;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use App\Models\Requirements;
use App\Models\Payout;
use App\Models\Scholar;
use App\Models\SubmittedRequirements;
use App\Models\Sponsor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

        $scholarships = Scholarship::all();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();
        // $scholarships = $sponsors->scholarships;

        return inertia('Staff/Scholarships/ViewScholarships', [
            'sponsors' => $sponsors,
            'scholarships' => $scholarships,
            'schoolyears' => $schoolyear,
        ]);
    }

    public function batch(Request $request, $scholarshipId, $batchId)
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        $batch = Batch::where('id', $batchId)
            ->where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->orderBy('batch_no', 'desc')
            ->first();

        $scholars = $batch->scholars->map(function ($scholar) use ($totalRequirements, $scholarshipId) {
            // Get approved requirements for this scholar


            $approvedRequirements = SubmittedRequirements::where('scholar_id', $scholar->id)
                ->where('status', 'Approved')
                ->count();

            // Determine status
            $status = 'No submission';
            if ($totalRequirements > 0) {
                if ($approvedRequirements === 0) {
                    $status = 'No submission';
                } elseif ($approvedRequirements === $totalRequirements) {
                    $status = 'Complete';
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
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'middle_name' => $scholar->middle_name,
                'campus' => $scholar->campus,
                'grant' => $scholar->grant,
                'status' => $status,
                'submittedRequirements' => $approvedRequirements,
                'totalRequirements' => $totalRequirements,
                'progress' => $progress,
            ];
        });

        return Inertia::render('Staff/Scholarships/Scholarship_Batch', [
            'scholarship' => $scholarship,
            'batches' => $batch,
            'scholars' => $scholars,
            'requirements' => $requirements,
            'schoolyear' => $request->input('selectedYear')
                ? SchoolYear::find($request->input('selectedYear'))
                : null,
            'selectedSem' => $request->input('selectedSem', ''),
        ]);
    }


    public function show(Request $request, Scholarship $scholarship)
    {
        $batches = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'scholars' => function ($query) {
                    $query->orderBy('last_name')
                        ->orderBy('first_name');
                }
            ])
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->orderBy('batch_no', 'desc')
            ->get();

        $schoolyear = null;
        if ($request->input('selectedYear')) {
            $schoolyear = SchoolYear::find($request->input('selectedYear'));
        }

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'scholars' => $scholarship->scholars()
                ->whereDoesntHave('submittedRequirements', function ($query) {
                    $query->where('status', '!=', 'approved');
                })
                ->whereHas('submittedRequirements')
                ->get(),
            'schoolyear' => $schoolyear,
            'selectedSem' => $request->input('selectedSem', ''),
        ]);
    }


    public function store(Request $request, Sponsor $sponsor)
    {
        $request->validate([
            'sponsor_id' => 'required|int',
            'name' => 'required|string|max:255',
            'scholarshipType' => 'required|string|max:255',
            // 'requirements' => 'required|array'
            // 'application' => 'required|date',
            // 'deadline' => 'required|date',
        ]);

        //dd($request);
        Scholarship::create($request->all());

        return redirect()->route('sponsor.index')->with('success', 'Check out view scholarships');
    }

    public function send(Scholarship $scholarship)
    {
        $scholars = $scholarship->scholars;

        return Inertia::render('Staff/Scholarships/SendingAccess', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
        ]);
    }

    public function forward(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|integer',
            'scholars' => 'required|array', // Array of scholar IDs
            'batch_ids' => 'required|array', // Array of batch IDs
            'batch_ids.*' => 'integer',
        ]);

        $scholarshipId = $validated['scholarship_id'];
        $scholars = $validated['scholars']; // Array of scholar IDs
        $batchIds = $validated['batch_ids']; // Array of batch IDs

        $dataToInsert = [];

        foreach ($scholars as $scholarId) {
            $dataToInsert[] = [
                'scholarship_id' => $scholarshipId,
                'batch_id' => $scholarId['batch_id'],
                'scholar_id' => $scholarId['id'],
                'status' => 'pending', // Default status
                'created_at' => now(),
                'updated_at' => now(),
            ];

            //dd($dataToInsert);
            // Check if the scholar already has a payout record for this scholarship

        }

        // Insert all records at once
        Payout::insert($dataToInsert);

        return response()->json([
            'message' => 'Scholars successfully assigned to batches!',
            'inserted_count' => count($dataToInsert),
        ], 201);
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


