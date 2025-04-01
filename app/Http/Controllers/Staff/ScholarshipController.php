<?php

namespace App\Http\Controllers\Staff;

use App\Events\GeneralNotification;
use App\Events\NewNotification;
use App\Models\Condition;
use App\Models\Course;
use App\Models\Eligibility;
use App\Models\Grantees;
use Inertia\Inertia;
use App\Models\Scholarship;
use App\Models\SchoolYear;
use App\Models\Batch;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Applicant;
use App\Models\Campus;
use App\Models\CampusRecipients;
use App\Models\Criteria;
use App\Models\Disbursement;
use App\Models\Eligible;
use App\Models\Grade;
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

        //                 // Use relationships to get applicants and their related scholars
        //     $applicants = $scholarship->applicants()
        //     ->where('batch_id', $batch->id)
        //     ->with('scholar.campus', 'scholar.course')
        //     ->get();

        // // Count scholars with complete submissions
        // $completeSubmissionsCount = 0;

        // // Process scholars data using the relationship
        // $scholars = $applicants->map(function ($applicant) use ($totalRequirements, &$completeSubmissionsCount , $request) {
        //     // Skip if there's no related scholar
        //     if (!$applicant->scholar) {
        //         return null;
        //     }

        //     $scholar = $applicant->scholar;
        $grantees = $scholarship->grantees()
            ->where('batch_id', $batch->id)
            ->with('scholar.campus', 'scholar.course')
            ->get();

        // Count scholars with complete submissions
        $completeSubmissionsCount = 0;

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
        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('semester', $request->input('selectedSem'))
            ->where('school_year', $request->input('selectedYear'))
            ->first();

        if ($scholarship->scholarshipType == 'One-time Payment' && $batch) {
            return redirect()->route('scholarship.onetime_list', [
                'scholarshipId' => $scholarship->id,
                'selectedYear' => $request->input('selectedYear'),
                'selectedSem' => $request->input('selectedSem')
            ]);
        }

        $user = Auth::user();
        $userType = $user->usertype;

        $campuses = $userType == 'super_admin'
            ? Campus::all()
            : Campus::where('id', $user->campus_id)->get();

        if ($userType == 'coordinator') {
            if (!$request->has('selectedCampus')) {
                $request->merge(['selectedCampus' => $user->campus_id]);
            }

            if ($request->input('selectedCampus') != $user->campus_id) {
                return redirect()->back()->with('error', 'You can only view your assigned campus.');
            }
        }

        $batchesQuery = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'grantees.scholar' => function ($query) {
                    $query->orderBy('last_name')->orderBy('first_name');
                }
            ]);

        if ($request->input('selectedYear')) {
            $batchesQuery->where('school_year', $request->input('selectedYear'));
        }

        if ($request->input('selectedSem')) {
            $batchesQuery->where('semester', $request->input('selectedSem'));
        }

        if ($request->input('selectedCampus')) {
            $campusId = $request->input('selectedCampus');

            $batchesQuery->whereHas('grantees.scholar', function ($query) use ($campusId) {
                $query->where('campus_id', $campusId);
            });
        }

        $batches = $batchesQuery->orderBy('batch_no', 'desc')->get();

        $schoolyear = $request->input('selectedYear')
            ? SchoolYear::find($request->input('selectedYear'))
            : null;

        $courses = Course::all();
        $students = Student::all();
        $requirements = Requirements::where('scholarship_id', $scholarship->id)->get();

        $grantees = $scholarship->grantees()
            ->where('batch_id', $batch?->id)
            ->with('scholar.campus', 'scholar.course')
            ->get();

        $total_scholars = $grantees->map(fn($grantee) => $grantee->scholar)->filter();

        $scholarsWithAllApproved = Scholar::whereIn('id', $total_scholars->pluck('id'))
            ->whereHas('submittedRequirements', function ($query) {
                $query->where('status', 'Approved');
            })
            ->whereDoesntHave('submittedRequirements', function ($query) {
                $query->whereIn('status', ['Pending', 'Returned']);
            });

        if ($userType == 'coordinator') {
            $scholarsWithAllApproved->where('campus_id', $user->campus_id);
        } elseif ($request->input('selectedCampus')) {
            $scholarsWithAllApproved->where('campus_id', $request->input('selectedCampus'));
        }

        $scholarsWithAllApproved = $scholarsWithAllApproved->get();

        Batch::where('scholarship_id', $scholarship->id)
            ->when($request->input('selectedYear'), fn($query, $year) => $query->where('school_year', $year))
            ->when($request->input('selectedSem'), fn($query, $sem) => $query->where('semester', $sem))
            ->update(['sub_total' => $scholarsWithAllApproved->count()]);

        $completedBatches = Batch::where('scholarship_id', $scholarship->id)
            ->whereRaw('total_scholars = sub_total')
            ->when($request->input('selectedYear'), fn($query, $year) => $query->where('school_year', $year))
            ->when($request->input('selectedSem'), fn($query, $sem) => $query->where('semester', $sem))
            ->count();

        $allBatches = Batch::where('scholarship_id', $scholarship->id)
            ->with([
                'grantees.scholar' => function ($query) {
                    $query->orderBy('last_name')->orderBy('first_name');
                }
            ])
            ->orderBy('batch_no', 'desc')
            ->get();

        $scholarship->update(['read' => 1]);

        event(new GeneralNotification(
            'Scholarship marked as read',
            'scholarship_read',
            ['scholarship_id' => $scholarship->id, 'read' => true]
        ));

        return Inertia::render('Staff/Scholarships/Scholarship', [
            'scholarship' => $scholarship,
            'batches' => $batches,
            'total_scholars' => $total_scholars,
            'requirements' => $requirements,
            'grantees' => $grantees,
            'completedBatches' => $completedBatches,
            'schoolyear' => $schoolyear,
            'selectedSem' => $request->input('selectedSem', ''),
            'selectedCampus' => $request->input('selectedCampus', ''),
            'campuses' => $campuses,
            'courses' => $courses,
            'students' => $students,
            'scholarship_form' => ScholarshipForm::find(2),
            'scholarship_form_data' => ScholarshipFormData::where('scholarship_form_id', 2)->get(),
            'elibigilities' => Eligibility::all(),
            'conditions' => Condition::all(),
            'userType' => $userType,
            'userCampusId' => $userType == 'coordinator' ? $user->campus_id : null,
            'allBatches' => $allBatches,
            'payouts' => Payout::where('scholarship_id', $scholarship->id)->first(),
        ]);
    }


    public function onetime_list(Request $request, $scholarshipId)
    {
        // Find the scholarship by ID
        $scholarship = Scholarship::findOrFail($scholarshipId);

        // Get the batch for the selected semester and school year
        $batch = Batch::where('scholarship_id', $scholarship->id)
            ->where('semester', $request->input('selectedSem'))
            ->where('school_year', $request->input('selectedYear'))
            ->firstOrFail();

        // Check if scholar's payment claimed
        $payout = Payout::where('scholarship_id', $scholarshipId)
            ->where('status', 'claimed')
            ->get();

        // Get all requirements for this scholarship
        $requirements = Requirements::where('scholarship_id', $scholarshipId)->get();
        $totalRequirements = $requirements->count();

        // Use relationships to get applicants and their related scholars
        $applicants = $scholarship->applicants()
            ->where('batch_id', $batch->id)
            ->with('scholar.campus', 'scholar.course')
            ->get();

        // Count scholars with complete submissions
        $completeSubmissionsCount = 0;

        // Process scholars data using the relationship
        $scholars = $applicants->map(function ($applicant) use ($totalRequirements, &$completeSubmissionsCount, $request) {
            // Skip if there's no related scholar
            if (!$applicant->scholar) {
                return null;
            }

            $scholar = $applicant->scholar;

            // Get the scholar's grade for the selected semester and school year
            $grade = Grade::where('scholar_id', $scholar->id)
                ->where('semester', $request->input('selectedSem'))
                ->where('school_year', $request->input('selectedYear'))
                ->first();

            $userPicture = User::where('id', $scholar->user_id)
                ->first();

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
                'picture' => $userPicture->picture,
                'urscholar_id' => $scholar->urscholar_id,
                'first_name' => $scholar->first_name,
                'last_name' => $scholar->last_name,
                'middle_name' => $scholar->middle_name,
                'campus' => $scholar->campus->name ?? 'N/A',
                'course' => $scholar->course->name ?? 'N/A',
                'year_level' => $scholar->year_level,
                'grant' => $scholar->grant,
                'status' => $status,
                'submittedRequirements' => $approvedRequirements,
                'totalRequirements' => $totalRequirements,
                'progress' => $progress,
                'grade' => $grade ? $grade->grade : null,
                'cog' => $grade ? $grade->cog : null,
                'grade_path' => $grade ? $grade->path : null,
            ];
        })->filter(); // Remove any null values

        return Inertia::render('Staff/Scholarships/One-Time/OneTime_Applicants', [
            'scholarship' => $scholarship,
            'batch' => $batch,
            'applicants' => $applicants,
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
            'conditions' => 'required|array',
            'conditions.*' => 'exists:conditions,id',
            'semester' => 'required',
            'school_year' => 'required',
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

        // For eligible
        foreach ($validated['conditions'] as $condition_id) {
            // Check if record exists
            $existingEligible = Eligible::where('scholarship_id', $scholarship->id)
                ->where('condition_id', $condition_id)
                ->first();

            if ($existingEligible) {
                // Update existing record
                $existingEligible->update([
                    'condition_id' => $condition_id,
                ]);
            } else {
                // Create new record
                Eligible::create([
                    'scholarship_id' => $scholarship->id,
                    'condition_id' => $condition_id,
                ]);
            }

        }

        $batch = Batch::where('scholarship_id', $scholarship->id)->first();

        if (!$batch) {
            Batch::create([
                'scholarship_id' => $scholarship->id,
                'batch_no' => '1',
                'school_year' => $request->school_year,
                'semester' => $request->semester,
            ]);
        }

        //Update Scholarship Status
        $scholarship->update([
            'status' => 'Active'
        ]);


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


