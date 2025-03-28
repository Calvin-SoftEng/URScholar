<?php

namespace App\Http\Controllers\Staff;

use App\Events\GeneralNotification;
use App\Events\NewNotification;
use App\Models\Course;
use Inertia\Inertia;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Campus;
use App\Models\CampusRecipients;
use App\Models\Criteria;
use App\Models\Disbursement;
use App\Models\Notification;
use App\Models\Requirements;
use App\Models\Payout;
use App\Models\Scholar;
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

        $scholarships = Scholarship::with('requirements')->get();
        $sponsors = Sponsor::all();
        $schoolyear = SchoolYear::all();


        return inertia('Staff/Scholarships/ViewScholarships', [
            'sponsors' => $sponsors,
            'scholarships' => $scholarships,
            'schoolyears' => $schoolyear,
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
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->orderBy('batch_no', 'desc')
            ->with(['scholars.campus', 'scholars.course', 'scholars.user']) // Added user relationship
            ->first();

        // Count scholars with complete submissions
        $completeSubmissionsCount = 0;

        $scholars = $batch->scholars->map(function ($scholar) use ($totalRequirements, &$completeSubmissionsCount) {
            // Get approved, returned, and total submitted requirements for this scholar
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
                ]
            ];
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
        ]);
    }



    public function show(Request $request, Scholarship $scholarship)
    {
        // If it's a one-time payment scholarship, redirect to the appropriate list
        if ($scholarship->scholarshipType == 'One-time Payment') {
            return redirect()->route('scholarship.onetime_list', [
                'scholarshipId' => $scholarship->id,
            ])->with([
                        'selectedYear' => $request->input('selectedYear'),
                        'selectedSem' => $request->input('selectedSem')
                    ]);
        }

        // Get the authenticated user
        $user = Auth::user();
        $userType = $user->usertype; // Assuming you have a user_type column

        // Get available campuses based on user type
        if ($userType == 'super_admin') {
            $campuses = Campus::all();
        } else if ($userType == 'coordinator') {
            // Assuming coordinator has a campus_id in users table
            $campuses = Campus::where('id', $user->campus_id)->get();

            // If no campus filter is specified, default to the coordinator's campus
            if (!$request->has('selectedCampus')) {
                $request->merge(['selectedCampus' => $user->campus_id]);
            }

            // Make sure coordinator can only access their own campus
            if ($request->input('selectedCampus') != $user->campus_id) {
                return redirect()->back()->with('error', 'You can only view your assigned campus.');
            }
        } else {
            // For other user types - adjust as needed
            $campuses = Campus::all();
        }

        // Filter batches based on scholarship and optional filters
        $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'scholars' => function ($query) {
                    $query->orderBy('last_name')
                        ->orderBy('first_name');
                }
            ]);

        // Apply filters for year and semester
        if ($request->input('selectedYear')) {
            $batchesQuery->where('school_year', $request->input('selectedYear'));
        }

        if ($request->input('selectedSem')) {
            $batchesQuery->where('semester', $request->input('selectedSem'));
        }

        // Filter by campus if specified
        if ($request->input('selectedCampus')) {
            $campusId = $request->input('selectedCampus');

            $batchesQuery->whereHas('scholars', function ($query) use ($campusId) {
                $query->where('campus_id', $campusId);
            });
        }

        $batches = $batchesQuery->orderBy('batch_no', 'desc')->get();

        // Remaining code for other data...
        $schoolyear = null;
        if ($request->input('selectedYear')) {
            $schoolyear = SchoolYear::find($request->input('selectedYear'));
        }

        $courses = Course::all();
        $students = Student::all();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();
        $total_scholars = Scholar::where('scholarship_id', $scholarship->id)->get();
        $scholarship_form = ScholarshipForm::all();
        $scholarship_form_data = ScholarshipFormData::all();
        $payouts = Payout::where('scholarship_id', $scholarship->id)->first();

        // Get all scholars with all requirements approved
        $scholarsWithAllApproved = Scholar::where('scholarship_id', $scholarship->id)
            ->whereHas('submittedRequirements', function ($query) {
                $query->where('status', 'Approved');
            })
            ->whereDoesntHave('submittedRequirements', function ($query) {
                $query->whereIn('status', ['Pending', 'Returned']);
            });

        // Apply campus filter for coordinator
        if ($userType == 'coordinator') {
            $scholarsWithAllApproved->where('campus_id', $user->campus_id);
        } else if ($request->input('selectedCampus')) {
            $scholarsWithAllApproved->where('campus_id', $request->input('selectedCampus'));
        }

        $scholarsWithAllApproved = $scholarsWithAllApproved->get();

        Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->update(['sub_total' => $scholarsWithAllApproved->count()]);

        $completedBatches = Batch::where('scholarship_id', $scholarship->id)
            ->whereRaw('total_scholars = sub_total')
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->count();

        // Get all batches for this scholarship (regardless of filters)
        $allBatches = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'scholars' => function ($query) {
                    $query->orderBy('last_name')
                        ->orderBy('first_name');
                }
            ])
            ->orderBy('batch_no', 'desc')
            ->get();

        $scholarship->update([
            'read' => 1
        ]);

        // Broadcast a general notification about the scholarship being read
        event(new GeneralNotification(
            'Scholarship marked as read',
            'scholarship_read',
            [
                'scholarship_id' => $scholarship->id,
                'read' => true
            ]
        ));

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'total_scholars' => $total_scholars,
            'requirements' => $requirements,
            'scholars' => $scholarsWithAllApproved,
            'completedBatches' => $completedBatches,
            'schoolyear' => $schoolyear,
            'selectedSem' => $request->input('selectedSem', ''),
            'selectedCampus' => $request->input('selectedCampus', ''),
            'campuses' => $campuses,
            'courses' => $courses,
            'students' => $students,
            'scholarship_form' => $scholarship_form,
            'scholarship_form_data' => $scholarship_form_data,
            'userType' => $userType, // Pass user type to frontend
            'userCampusId' => $userType == 'coordinator' ? $user->campus_id : null,
            'allBatches' => $allBatches,
            'payouts' => $payouts,
        ]);
    }

    public function onetime_list(Request $request, $scholarshipId)
    {
        // dd($scholarshipId);
        // Now we've changed the parameter to use Laravel's model binding
        $scholarship = Scholarship::where('id', $scholarshipId)->first();


        // Checking if scholar's payment claimed
        $payout = Payout::where('scholarship_id', $scholarshipId)
            ->where('status', 'claimed')
            ->get();

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        // Get scholars directly without batch relationship
        $scholars = Scholar::where('scholarship_id', $scholarshipId)
            ->when($request->input('selectedYear'), function ($query, $year) {
                return $query->where('school_year', $year);
            })
            ->when($request->input('selectedSem'), function ($query, $sem) {
                return $query->where('semester', $sem);
            })
            ->with(['campus', 'course']) // Eager load campus and course relationships
            ->get()
            ->map(function ($scholar) use ($totalRequirements) {
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
                ];
            });

        return Inertia::render('Staff/Scholarships/One-Time/OneTime_Applicants', [
            'scholarship' => $scholarship,
            'scholars' => $scholars,
            'payout' => $payout,
            'requirements' => $requirements,
            'schoolyear' => $request->input('selectedYear')
                ? SchoolYear::find($request->input('selectedYear'))
                : null,
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
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        //dd($request);
        Scholarship::create($request->all());

        ActivityLog::create([
            'user_id' => Auth::user()->id,
            'activity' => 'Create',
            'description' => 'Scholarship created ' . $request['name'],
        ]);

        return redirect()->route('sponsor.index')->with('success', 'Check out view scholarships');
    }


    public function one_time(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'total_recipients' => 'required|integer|min:1',
            'campus_recipients' => 'required|array',
            'campus_recipients.*.campus_id' => 'required|exists:campuses,id',
            'campus_recipients.*.slots' => 'required|integer|min:1',
            'campus_recipients.*.remaining_slots' => 'required|integer|min:0',
            'campus_recipients.*.selected_campus' => 'required|json',
            'application' => 'required|date',
            'deadline' => 'required|date',
            'requirements' => 'required|array',
            'grade' => 'required|numeric|min:0|max:100',
            'criteria' => 'required|array',
            'criteria.*' => 'exists:scholarship_form_data,id',
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
        }

        // For requirements
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
            } else {
                // Insert new record
                Requirements::create([
                    'scholarship_id' => $scholarship->id,
                    'requirements' => $requirement,
                    'date_start' => $validated['application'],
                    'date_end' => $validated['deadline'],
                    'total_scholars' => $total_recipients,
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


        return back()->with('success', 'Scholarship recipients and requirements saved successfully');
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
            'scholars' => 'required|array',
            'batch_ids' => 'required|array',
            'batch_ids.*' => 'integer',
            'date_start' => 'required|date',
            'date_end' => 'required|date'
        ], [
            'date_start.required' => 'Set a Date start',
            'date_end.required' => 'Set a Date end',
        ]);

        $scholarshipId = $request->input('scholarship_id');
        $scholars = $request->input('scholars');
        $batchIds = $request->input('batch_ids');
        $user = Auth::user();

        // Create payout first and get its ID
        $payout = Payout::create([
            'scholarship_id' => $scholarshipId,
            'date_start' => $request->input('date_start'),
            'date_end' => $request->input('date_end'),
            'status' => 'Pending',
        ]);

        // Prepare disbursement data
        $dataToInsert = [];
        foreach ($scholars as $scholar) {
            $dataToInsert[] = [
                'payout_id' => $payout->id,
                'batch_id' => $scholar['batch_id'],
                'scholar_id' => $scholar['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert disbursements
        Disbursement::insert($dataToInsert);

        $total_disbursement = Disbursement::where('payout_id', $payout->id)->count();

        $payout->update([
            'total_scholars' => $total_disbursement
        ]);

        // Create Activity Log
        $activityLog = ActivityLog::create([
            'user_id' => $user->id,
            'activity' => 'Forward',
            'description' => 'Scholars forwarded to cashiers',
        ]);

        // Get scholar IDs from the disbursements
        $scholarIds = collect($dataToInsert)->pluck('scholar_id');

        // Find users associated with these scholars
        // $users = User::whereIn('id', function ($query) use ($scholarIds) {
        //     $query->select('user_id')
        //         ->from('scholars')
        //         ->whereIn('id', $scholarIds);
        // })
        //     ->where('id', '!=', $user->id) // Exclude the current user
        //     ->get();

        $users = User::whereIn('id', function ($query) use ($scholarshipId) {
            $query->select('user_id')
                ->from('scholarship_groups')
                ->where('scholarship_id', $scholarshipId);
        })
            ->where('id', '!=', Auth::user()->id) // Add this line to exclude the current user
            ->get();

        // Create Notification
        $notification = Notification::create([
            'title' => 'New Payout Forwarded',
            'message' => 'Scholars forwarded to cashiers by ' . $user->name,
            'type' => 'payout_forward',
        ]);

        // Attach users to the notification
        $notification->users()->attach($users->pluck('id'));

        // Broadcast the notification
        broadcast(new NewNotification($notification))->toOthers();

        // Trigger event for new notification
        event(new NewNotification($notification));

        // return response()->json([
        //     'message' => 'Scholars successfully assigned to batches!',
        //     'inserted_count' => count($dataToInsert),
        // ], 201);
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


